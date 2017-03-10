<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\MerchantApp;
use App\Jobs\SendRemiderEmail;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\DeviceUser;
use Illuminate\Support\Collection;
use Cache, Wrep\Notificato\Notificato, Storage;
use Session, Endroid\Gcm\Client;
use Redis;

class MarketingMailController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $listgame =DB::table('merchant_app')
            ->get();
        return view('/marketing_mail', compact('listgame'));
    }

    public function postInsert(Request $request) {
        $keyword = trim($request->get('text_content'));
        $keyword1 = trim($request->get('text_subject'));
        $keyword4 = trim($request->get('game'));

        if($keyword4 == '1'){
            $email = ['subject' => $keyword1, 'content' => $keyword ];
////            print_r(Redis::EXISTS('Queue_MarketingMail_listmail'));die;
//         //   Redis::lpush('Queue_MarketingMail_content',json_encode($email));
            $countmail = DB::table('mail_test')
                ->select(DB::raw('count(mail) as countemail'))
                ->first();
            $offset = 0;
            for($i=0;$i<= $countmail->countemail;$i+=500){
                $listmail = DB::table('mail_test')
                    ->select('mail')
                    ->limit(500)
                    ->offset($offset)
                    ->get();
                Redis::lpush('Queue_MarketingMail_content',json_encode($email));
                Redis::lpush('Queue_MarketingMail_listmail', json_encode($listmail));
                $offset+=500;
            }
        }else{
            //print_r($keyword4);die;
//            $listmail = Db::table('user_game')
//                    ->join('users', 'users.id', '=', 'user_game.uid')
//                    ->where('app_id',$keyword4)
//                -select('email')
//                    ->get();
            $email = ['subject' => $keyword1, 'content' => $keyword ];
            $countmail = DB::table('user_game')
                ->join('users', 'users.id', '=', 'user_game.uid')
                ->select(DB::raw('count(email) as countemail'))
                ->where('user_game.app_id',$keyword4)
                ->where('email','not like','default%')
                ->where('email','!=','')
                ->first();
            $offset = 0;
            for($i=0;$i<= $countmail->countemail;$i+=500){
//                $ii++;
                $listmail = DB::table('user_game')
                    ->join('users', 'users.id', '=', 'user_game.uid')
                    ->select('email')
                    ->where('user_game.app_id',$keyword4)
                    ->where('email','not like','default%')
                    ->where('email','!=','')
                    ->limit(500)
                    ->offset($offset)
                    ->get();
//

//                print_r($listmail);
                Redis::lpush('Queue_MarketingMail_content',json_encode($email));
                Redis::lpush('Queue_MarketingMail_listmail', json_encode($listmail));
//                print_r($listmail);
                $offset+=500;
//                $ii++;
//        print_r($chunks->toArray());

            }

//            print_r($keyword4);die;
        }

        $time = time();
        $query = DB::table('mail')
            ->insert(
                array('subject' => $keyword1,
                    'content' => $keyword,
                    'created_at' => date('Y-m-d H:i:s',$time),
                    'game' => $keyword4
                ));

        Session::flash('flash_success', 'The send mail successfully.');
        return redirect()->route('marketingmail.index');


    }
}
