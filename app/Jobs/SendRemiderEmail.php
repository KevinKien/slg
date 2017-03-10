<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Redis;


class SendRemiderEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $countmail = DB::table('users')
            ->select(DB::raw('count(email) as countemail'))
            ->where('email','not like','default%')
            ->where('email','!=','')
            ->first();
        $offset = 0;
//            print_r($countmail);die;
//            $ii=0;
//            $listmail = DB::table('mail_test')
//                    ->select('mail','fullname')
//                    ->limit(500)
//            $listmail = [["mail"=>"hoangdat283@gmail.com","fullname"=>"dat"],["mail"=>"datth@vinhxuan.com.vn","fullname"=>"hoang dat"],
//                ["mail"=>"doan.dm1991@gmail.com","fullname"=>"manh doan"],["mail"=>"phuongnb@vinhxuan.com.vn","fullname"=>"Nguyen Phuong"]];
        for($i=0;$i<= $countmail->countemail;$i+=500){
//                $ii++;
            $listmail = DB::table('users')
                ->select('email')
                ->where('email','not like','default%')
                ->where('email','!=','')
                ->limit(500)
                ->offset($offset)
                ->get();
                 Redis::lpush('Queue_MarketingMail_listmail', json_encode($listmail));
            $offset+=500;
        }
    }
}
