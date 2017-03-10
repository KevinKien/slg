<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\MerchantApp;
use App\Models\DeviceUser;
use Cache, Wrep\Notificato\Notificato, Storage;
use Session, Endroid\Gcm\Client;

class NotificationController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();

        $clients = [];

        $apps = MerchantApp::where('gametype', 1)->get();

        if (!$apps->isEmpty()) {
            foreach ($apps as $app) {
                $clients[$app->clientid] = $app->name;
            }
        }

        $client_types = [
            1 => 'Android',
            2 => 'iOS',
            //3 => 'Windows Phone',
        ];

        if ($request->isMethod('post')) {

            $this->validate($request, [
                'client_id' => 'required',
                'client_type' => 'required',
                'title' => 'required',
                'message' => 'required',
            ], [
                'client_id.required' => 'Trường "Game" là bắt buộc nhập.',
                'client_type.required' => 'Trường "Hệ điều hành" là bắt buộc nhập.',
                'title.required' => 'Trường "Tiêu đề" là bắt buộc nhập.',
                'message.required' => 'Trường "Nội dung" là bắt buộc nhập.',
            ]);

            $input = $request->all();

            $result = DeviceUser::whereIn('os_id', $input['client_type'])
                ->whereIn('client_id', $input['client_id'])->get();

            if ($result->isEmpty()) {
                Session::flash('flash_warning', 'Không tìm thấy thiết bị nào.');
            } else {
                $android_ids = [];
                $ios_ids = [];

                foreach ($result as $row) {
                    if ($row->os_id == 1) {
                        $android_ids[] = $row->device_id;
                    } elseif ($row->os_id == 2) {
                        $ios_ids[] = $row->device_id;
                    }
                }

                $message_flash = 'Đã gửi thông báo tới thiết bị: ';

                if (!empty($android_ids))
                {
                    $client = new Client('AIzaSyCgksbrVpkDw43n4erutIsVluVk_zopRRQ', 'https://gcm-http.googleapis.com/gcm/send');

                    $data = [
                        'title' => $input['title'],
                        'message' => $input['message'],
                    ];

                    $client->send($data, $android_ids);

                    $responses = $client->getResponses();

                    $android_success = 0;
                    $android_failure = 0;

                    foreach ($responses as $response) {
                        $message = json_decode($response->getContent());
                        if (isset($message->success)) {
                            $android_success += $message->success;
                        }

                        if (isset($message->failure)) {
                            $android_failure += $message->failure;
                        }
                    }

                    $message_flash .= count($android_ids) . " Android ($android_success Thành công, $android_failure Lỗi); ";
                }

                if (!empty($ios_ids))
                {
//                    $notificato = new Notificato(storage_path('app/apns.pem'));

                    $device = 'f670d84002d9762208d4c8e0acce26b175a8eed132da1d94ee9760c939283469';
                    $payload['aps'] = array('alert' => 'Hello I am testing the server code ....', 'badge' => 1, 'sound' => 'default');
                    $payload = json_encode($payload);

                    $options = array('ssl' => array(
                        'local_cert' => storage_path('app/apns.pem'),
                        'passphrase' => ''
                    ));



                    $streamContext = stream_context_create();
                    stream_context_set_option($streamContext, $options);
                    $apns = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);

                    $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $device)) . chr(0) . chr(strlen($payload)) . $payload;
                    fwrite($apns, $apnsMessage);
                    fclose($apns);

                    dd($apns);
//
//                    foreach ($ios_ids as $ios_id) {
//                        $message = $notificato->messageBuilder()
//                            ->setDeviceToken($ios_id)
//                            ->setBadge(1)
//                            ->build();
//
//                        $messageEnvelope = $notificato->send($message);
//
//                        $a = $messageEnvelope->getFinalStatusDescription();
//                        dd($a);
//                    }
                }

                Session::flash('flash_info', $message_flash);
            }
        }

        return view('notification', compact('clients', 'client_types'));
    }
}
