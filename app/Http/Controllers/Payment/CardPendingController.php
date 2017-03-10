<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request, cURL;
use Session, CommonHelper, Auth, App\Models\LogChargeTelco;
use App\Http\Controllers\Controller;
use App\Helpers\Payments\PayDirect;
use App\Helpers\Logs\TopupLogHelper;
use App\Models\CashInfo;

class CardPendingController extends Controller
{
    /**
     * Responds to requests to GET /settings
     */
    public function getIndex()
    {
        $logs = LogChargeTelco::getLogCardPending()->paginate(10);

        return view('payment.card_pending', compact('logs'));
    }

    public function getUpdate(Request $request)
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

        return redirect()->route('card-pending.index');
    }
}