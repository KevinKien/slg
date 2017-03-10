<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\MerchantApp;

use Feeds, Cache, Response, Auth, Validator;

class NewsController extends Controller
{
    private static function getApps()
    {
        return Cache::get('list_game_active', function () {
            $apps = MerchantApp::where('status', 1)->whereNotNull('slug')->get();
            return $apps->isEmpty() ? null : $apps->toArray();
        });
    }

    /**
     * Push feed to cache.
     *
     * @return Response
     */
    public function push()
    {
        if (Auth::check() && Auth::user()->is('administrator|deploy')) {

            $apps = self::getApps();

            foreach ($apps as $app) {
                if (!filter_var($app['url_news'], FILTER_VALIDATE_URL) === false) {

                    $feed = Feeds::make($app['url_news'], true); // true: if RSS Feed has invalid mime types, force to read it.

                    if ($feed->get_item_quantity() > 0) {
                        $articles = [];

                        foreach ($feed->get_items() as $article) {
                            $articles[] = [
                                'title' => $article->get_title(),
                                'description' => $article->get_description(),
                                'date' => $article->get_date('d/m/Y H:i:s'),
                            ];
                        }

                        Cache::forever('news_' . $app['clientid'], $articles);
                    }
                }
            }

            return view('news');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function postIndex(Request $request) {
        $rules = [
            'client_id' => 'required|exists:merchant_app,clientid',
            'limit' => 'integer|min:1',
        ];

        $messages = [
            'required' => '401|The ":attribute" field is required.',
            'exists' => '402|The ":attribute" field is invalid.',
            'integer' => '406|The ":attribute" field must be integer.',
            'min' => '407|The ":attribute" field must be at least :min.',
        ];

        $input = array_map('trim', $request->all());

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $error = explode('|', $errors[0]);

            return Response::json([
                'data' => [],
                'error_code' => (int) $error[0],
                'message' => $error[1]
            ]);
        }

        $data = [];

        $key = 'news_' . $input['client_id'];

        if (Cache::has($key)) {
            $data = Cache::get($key);

            if ($request->has('limit')) {
                $data = array_slice($data, 0, $input['limit']);
            }

            $error_code = 200;
            $message = 'Data found.';
        } else {
            $error_code = 404;
            $message = 'Data not found.';
        }

        return Response::json([
            'data' => $data,
            'error_code' => $error_code,
            'message' => $message
        ]);
    }
}
