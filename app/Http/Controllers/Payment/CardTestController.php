<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Session, CommonHelper, Auth, App\Models\CardTest;
use App\Http\Controllers\Controller;

class CardTestController extends Controller
{
    /**
     * Responds to requests to GET /settings
     */
    public function getIndex(Request $request)
    {
        $cards = CardTest::whereNull('issued_by')->orderBy('created_at', 'desc')->paginate(10);

        $issued_cards = CardTest::whereNotNull('issued_by')->orderBy('issued_at', 'desc')->paginate(10);

        $tab = $request->get('tab');

        return view('payment.card_test', compact('cards', 'issued_cards', 'tab'));
    }

    public function postCreate(Request $request)
    {
        $request->flash();

        $this->validate($request, [
            'card-type' => 'required',
            'quantity' => 'required|integer|min:1|max:10',
            'amount' => 'required|integer|min:10000|max:500000',
        ]);

        $input = $request->all();

        $cards = [];

        if ($input['card-type'] == 'Mobifone') {
            $card_code_length = rand(7, 9);
            $card_seri_length = rand(9, 15);
        } elseif ($input['card-type'] == 'Vinaphone') {
            $card_code_length = rand(7, 9);
            $card_seri_length = rand(8, 15);
        } elseif ($input['card-type'] == 'Viettel') {
            $card_code_length = rand(8, 10);
            $card_seri_length = rand(11, 15);
        }

        for ($i = 1; $i <= $input['quantity']; $i++) {
            $cards[] = [
                'card_code' => 'test_' . CommonHelper::rand_str($card_code_length, true),
                'card_seri' => CommonHelper::rand_str($card_seri_length, true),
                'card_type' => $input['card-type'],
                'amount' => $input['amount'],
                'created_by' => Auth::id(),
                'created_at' => time(),
            ];
        }

        CardTest::insert($cards);

        Session::flash('flash_info', 'Tạo mới ' . $input['quantity'] . ' thẻ (' . number_format($input['amount'], 0, ',', '.') . ' VND) thành công.');

        return redirect()->route('card-test.index');
    }
}