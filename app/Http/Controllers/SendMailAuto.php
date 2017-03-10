<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
//use Illuminate\Support\Facades\Redis;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\MailHelper;
use Redis;

class SendMailAuto extends Controller {          
    
    public function sendMail() {      
        //check key redis
                
        
        if(Redis::EXISTS('Queue_MarketingMail_listmail') && Redis::EXISTS('Queue_MarketingMail_content')){
            
            $address = json_decode(Redis::rpop('Queue_MarketingMail_listmail'));
            $content = json_decode(Redis::rpop('Queue_MarketingMail_content'));
            $emails = array();
            $fullnames = array();
           
            
            for($i=0;$i< count($address);$i++){
                $emails[$i] = $address[$i]->mail;
            }
//            foreach ($address as $values){
//                $emails = $values['mail'];
//                $fullnames = $values['fullname'];
//            }  
            //$emails = array();            
            
            $user = array(                
                'email' => $emails,
                'subject' => $content->subject,
                'content' => $content->content,                            
            );            
            //$emails = ['manhdoan1@mailnesia.com', 'manhdoan2@mailnesia.com','manhdoan3@mailnesia.com'];        
            MailHelper::sendMailAuto($user);
            return;
        }                        
             return "ko có mail";   
    }
       
        

}
