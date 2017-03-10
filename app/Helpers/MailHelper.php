<?php

namespace App\Helpers;

use Mail;

class MailHelper
{
    public static function sendMailWelcome($user, $password)
    {
        $fullname = empty($user->fullname) ? $user->name : $user->fullname;

        Mail::send('emails.new', ['fullname' => $fullname, 'name' => $user->name, 'password' => $password], function ($message) use ($user, $fullname) {
            $message->to($user->email, $fullname)->subject('Chào mừng ' . $fullname . ' đến với SLG.vn');
        });
    }
    
    //send mail maketing
    public static function sendMailAuto($user)
    {                                                        
        $emails = "";
        for($i=0;$i<count($user['email']); $i++){
            $emails = $user['email'];           
        }
        
        
//        các tham số truyền vào nội dung khi mở mail
        Mail::send('emails.marketing', ['content' => $user['content'], 'subject' => $user['subject']], function ($message) use ($user, $emails) {
            //các địa chỉ + tiêu đề hiển thị trong hộp thư đến
            $message->bcc($emails)->subject($user['subject']);
        });           
        
        print_r('ok');
    }
        
}