<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\LogCoinTransfer;
use App\Models\LogChargeTelco;

use Cache, Response, Authorizer, CommonHelper, Validator;

class TransactionLogController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function postIndex(Request $request) {
        $rules = [
            'client_id' => 'required|exists:merchant_app,clientid',
            'uid' => 'required',
            'type' => 'required|in:topup,transfer',
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

        $raw_data = ($input['type'] == 'topup') ? LogChargeTelco::getLog($input['uid']) : LogCoinTransfer::getLog($input['uid'], $input['client_id']);

        if ($raw_data->isEmpty()) {
            $error_code = 404;
            $message = 'Data not found.';
        } else {
            if ($input['type'] == 'topup') {
                foreach ($raw_data as $value) {
                    $result = [];

                    $result['id'] = $value->id;
                    $result['type'] = ($value->card_type == 'VTT') ? 'Thẻ cào' : strtoupper($value->card_type);

                    if ($value->partner_type == 'EPAY') {
                        $response = json_decode($value->response);
                        $amount = $response->resAmount;
                    } else {
                        $amount = $value->amount;
                    }

                    $result['amount'] = number_format($amount, 0, ',', '.');
                    $result['coin'] = number_format($value->coin, 0, ',', '.');

                    $result['date'] = date('d/m/Y H:i:s', strtotime($value->created_at));

                    $data[] = $result;
                }
            } else {
                foreach ($raw_data as $value) {
                    $result = [];

                    $result['id'] = $value->id;
                    $result['game'] = !empty($value->game->name) ? $value->game->name : 'N/A';
                    $result['server'] = !empty($value->server->servername) ? $value->server->servername : 'N/A';
                    $result['amount'] = number_format($value->coin, 0, ',', '.');
                    $result['date'] = date('d/m/Y H:i:s', strtotime($value->request_time));

                    $data[] = $result;
                }
            }

            if ($request->has('limit')) {
                $data = array_slice($data, 0, $input['limit']);
            }

            $error_code = 200;
            $message = 'Data found.';
        }

        return Response::json([
            'data' => $data,
            'error_code' => $error_code,
            'message' => $message
        ]);
    }
}
