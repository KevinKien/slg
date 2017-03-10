<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oauth_clients extends Model
{
     protected $table = 'oauth_clients';
    // public function list_id($id) {
      //   $result= DB::table('oauth_clients')
         //       ->select('oauth_clients.*') 
         //       ->where('id', '!=', $id);
       // return $result;
   // }
     public $timestamps = false;
}
