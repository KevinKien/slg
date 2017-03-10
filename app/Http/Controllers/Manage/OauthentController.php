<?php
namespace App\Http\Controllers\Manage;

use Auth;

use App\Models\Oauth_client_endpoints;
use App\Models\Oauth_clients;
use App\Http\Requests;
use App\Http\Requests\OauthentclientRequest;
use App\Http\Controllers\Controller;
use DB, Session, Cache;

class OauthentController extends Controller
{
    public function missingMethod($parameters = array())
    {
        return redirect('oauth_client_endpoints/index');
    }

    public function getIndex()
    {
        $list_total = Oauth_client_endpoints::All();
        $model_dau = new Oauth_client_endpoints();
        $list = $model_dau->list_game();
        return view('/oauthent_client/list_oauth_client_endpoints', compact('list', 'list_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getAdd()
    {
        //$oauth_client = Oauth_clients::All();
        // $data['oauth_client'] = $oauth_client;
        return view('/oauthent_client/add_oauth_client_endpoints');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postIndex(OauthentclientRequest $request)
    {
        $input = $request->all();
        $request->flashOnly('game', 'redirect_uri');
        $original_string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $id = rand(1000000000, 9999999999);
        $merchant1 = new Oauth_clients;
        $merchant1->id = $id;
        $merchant1->name = $input["game"];
        $merchant1->secret = get_random_string($original_string, 20);
        $merchant1->created_at = date('Y-m-d H:i:s', time());
        //Tiến hành lưu dữ liệu vào database
        $merchant1->save();
        // print_r($merchant1->id);die;
        $merchant = new Oauth_client_endpoints;
        //Lấy thông tin từ các input đưa vào
        $merchant->client_id = $id;
        $merchant->redirect_uri = $input["redirect_uri"];
        $merchant->created_at = date('Y-m-d H:i:s', time());
        //Tiến hành lưu dữ liệu vào database
        $merchant->save();

        if ($request->has('inreview_sdk_version') && $request->has('inreview_sdk_version')) {
            Cache::forever('inreview_sdk_' . $id, ['inreview_sdk_version' => $input['inreview_sdk_version'], 'inreview_operator' => $input['inreview_operator']]);
        }

        $key_wm = 'welcome_message_' . $id;

        if ($request->has('welcome-message')) {
            Cache::forever($key_wm, $input['welcome-message']);
        }

        Session::flash('flash_success', 'The client added successfully.');
        return redirect('oauth_client_endpoints');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function getEdit()
    {
        $cl_id = $_GET['client_id'];
        $id = $_GET['id'];
        $results = DB::table('oauth_client_endpoints')
            ->join('oauth_clients', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')
            ->select('oauth_client_endpoints.*', 'oauth_clients.name', 'oauth_clients.secret')
            ->where('oauth_client_endpoints.id', '=', $id)->get();
        //$clientid = DB::table('oauth_clients')->where('id', '!=', $cl_id)->
        //    get();

        $key = 'inreview_sdk_' . $cl_id;
        $inreview_sdk_version = '';
        $inreview_operator = '';

        if (Cache::has($key)) {
            $inreview_sdk = Cache::get($key);

            $inreview_sdk_version = $inreview_sdk['inreview_sdk_version'];
            $inreview_operator = $inreview_sdk['inreview_operator'];
        }

        $welcome_message = Cache::get('welcome_message_' . $cl_id, '');

        return view('/oauthent_client/edit_oauth_client_endpoints', compact('results', 'inreview_sdk_version', 'inreview_operator', 'welcome_message'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postEdit(OauthentclientRequest $request)
    {
        $id = $_GET['id'];
        $client_id = $_GET['client_id'];
        $secret = $_GET['secret'];
        $input = $request->all();


        DB::table('oauth_clients')->where('id', $client_id)->update(['name' => $input["game"],
            'secret' => $secret, 'updated_at' => date('Y-m-d H:i:s', time())]);

        DB::table('oauth_client_endpoints')->where('id', $id)->update([
            'client_id' => $client_id,
            'redirect_uri' => $input["redirect_uri"],
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);

        if ($request->has('inreview_sdk_version') && $request->has('inreview_sdk_version')) {
            Cache::forever('inreview_sdk_' . $client_id, ['inreview_sdk_version' => $input['inreview_sdk_version'], 'inreview_operator' => $input['inreview_operator']]);
        }

        $key_wm = 'welcome_message_' . $client_id;

        if ($request->has('welcome-message')) {
            Cache::forever($key_wm, $input['welcome-message']);
        } else {
            Cache::forget($key_wm);
        }

        Session::flash('flash_success', 'The client updated successfully.');

        return redirect('oauth_client_endpoints');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function getDelete()
    {
        $client_id = $_GET['id'];
        DB::table('oauth_client_endpoints')->where('client_id', '=', $client_id)->delete();
        DB::table('oauth_clients')->where('id', '=', $client_id)->delete();

        $key_wm = 'welcome_message_' . $client_id;

        if (Cache::has($key_wm)) {
            Cache::forget($key_wm);
        }

        Session::flash('flash_success', 'The client deleted successfully.');
        return redirect('oauth_client_endpoints');
    }
}

function get_random_string($valid_chars, $length)
{
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++) {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick - 1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}