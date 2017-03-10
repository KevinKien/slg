<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;

class EmailValidatorController extends Controller
{
    /**
     * Validate the given email.
     *
     * @param  string  $email
     * @return boolean
     * @author https://github.com/hbattat/verifyEmail
     */
    public function validateEmail($email)
    {
        $email_arr = explode("@", $email);
        $domain = array_slice($email_arr, -1);
        $domain = $domain[0];

        // Trim [ and ] from beginning and end of domain string, respectively
        $domain = ltrim($domain, "[");
        $domain = rtrim($domain, "]");

        if( "IPv6:" == substr($domain, 0, strlen("IPv6:")) ) {
            $domain = substr($domain, strlen("IPv6") + 1);
        }

        $mxhosts = array();
        if( filter_var($domain, FILTER_VALIDATE_IP) )
            $mx_ip = $domain;
        else
            getmxrr($domain, $mxhosts, $mxweight);

        if(!empty($mxhosts) )
            $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
        else {
            if( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
                $record_a = dns_get_record($domain, DNS_A);
            }
            elseif( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
                $record_a = dns_get_record($domain, DNS_AAAA);
            }

            if( !empty($record_a) )
                $mx_ip = $record_a[0]['ip'];
            else {
                return false;
            }
        }

        $connect = @fsockopen($mx_ip, 25);
        if($connect){
            if(preg_match("/^220/i", $out = fgets($connect, 1024))){
                fputs ($connect , "HELO $mx_ip\r\n");
                fgets ($connect, 1024);

                fputs ($connect , "MAIL FROM: <inbox@gmail.com>\r\n");
                $from = fgets ($connect, 1024);

                fputs ($connect , "RCPT TO: <$email>\r\n");
                $to = fgets ($connect, 1024);

                fputs ($connect , "QUIT");
                fclose($connect);

                if(!preg_match("/^250/i", $from) || !preg_match("/^250/i", $to)){
                    $result = false;
                }
                else{
                    $result = true;
                }
            }
        }
        else{
            $result = false;
        }

        return $result;
    }
}