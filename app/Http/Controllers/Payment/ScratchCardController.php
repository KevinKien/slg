<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request, cURL;
use Session, CommonHelper, Auth, App\Models\LogChargeTelco;
use App\Http\Controllers\Controller;
use App\Helpers\Payments\PayDirect;
use App\Helpers\Logs\TopupLogHelper;
use App\Models\CashInfo;

class ScratchCardController extends Controller
{
    /**
     * Responds to requests to GET /settings
     */
    public function getPending()
    {
        $logs = LogChargeTelco::getLogCardPending()->paginate(10);

        //return view('payment.card_pending', compact('logs'));
    }

    public function postUpdatePending(Request $request)
    {
        $logs = LogChargeTelco::getLogCardPending()->get();

        $updated = [];

        foreach ($logs as $log) {
            $payload = [
                'transRef' => $log->trans_id,
                'partnerCode' => PayDirect::$partnerCode,
                'password' => PayDirect::$password,
                'signature' => md5($log->trans_id . PayDirect::$partnerCode . PayDirect::$password . PayDirect::$secretKey),
            ];

            try {
                $_request = cURL::jsonPost(PayDirect::$transaction_url, $payload);
            } catch (\Exception $e) {
                continue;
            }

            $response = json_decode($_request->body, true);

            if (is_array($response) && !empty($response)) {
                $_response = json_encode($response);

                if ($response['status'] == '01') {

                    $coin = $response['amount'] / 100;

                    $added = CashInfo::incrementCoin($log->uid, $coin);

                    if ($added) {
                        $log->payment_status = 'Giao dịch thành công.';
                        $log->response = $_response;
                        $log->amount = $response['amount'];
                        $log->coin = $coin;
                        $log->save();

                        $arr = [
                            'cpid' => $log->card_type,
                            'uid' => $log->uid,
                            'telco' => $log->card_type,
                            'clientid' => '',
                            'serial' => $log->card_seri,
                            'amount' => $response['amount'],
                            'device_id' => '',
                            'os_id' => 0,
                            'code' => $log->card_code,
                            'response' => 200,
                        ];

                        $revenuelog = new TopupLogHelper;
                        $revenuelog->setTopup($arr);

                        $updated[] = $log->trans_id;
                    }
                } elseif (in_array($response['status'], ['08', '13', '41', '99'])) {
                    $log->response = $_response;
                    $log->save();
                } else {
                    $log->payment_status = PayDirect::getMessage($response['status']);
                    $log->response = $_response;
                    $log->amount = 0;
                    $log->coin = 0;
                    $log->save();

                    $updated[] = $log->trans_id;
                }
            }
        }

        if (empty($updated)) {
            $msg = 'Không có giao dịch nào thay đổi trạng thái.';
        } else {
            $msg = 'Có ' . count($updated) . ' giao dịch đã thay đổi trạng thái: ' . implode(', ', $updated);
        }

        $request->session()->flash('flash_info', $msg);

        //return redirect()->route('card-pending.index');
    }

    public function checkTransaction(Request $request)
    {
        $request->flash();

        $logs = null;
        $paygate_log = [];

        if ($request->isMethod('post')) {
            $this->validate($request, [
                'card_code' => 'min:8|max:15',
                'card_seri' => 'min:8|max:15',
            ], [], [
                'card_code' => 'Mã thẻ',
                'card_seri' => 'Số Serial',
            ]);

            $input = array_map('trim', $request->all());

            unset($input['_token']);

            if (empty($input['card_code']) && empty($input['card_seri'])) {
                $request->session()->flash('flash_error', 'Bắt buộc điền 1 trong 2 trường.');
                return redirect()->back()->withInput();
            }

            $local_log = new LogChargeTelco;

            $i = 0;

            foreach ($input as $key => $value) {
                if (!empty($value)) {
                    if ($i === 0) {
                        $local_log = $local_log->where($key, $value);
                        $i++;
                    } else {
                        $local_log = $local_log->orWhere($key, $value);
                    }
                }
            }

            $logs = $local_log->where('partner_type', 'PAYDIRECT')->get();

            $card_code = isset($input['card_code']) ? $input['card_code'] : '';
            $card_seri = isset($input['card_seri']) ? $input['card_seri'] : '';

            $paygate_log = PayDirect::getTransaction($card_code, $card_seri);
        }

        return view('payment.card_transaction', compact('logs', 'paygate_log'));
    }
}