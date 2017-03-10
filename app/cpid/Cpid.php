<?php
namespace app\cpid;
use Illuminate\Database\Eloquent\Model;
class Cpid extends Model{
    public function ds(){
        $cpid = DB::table('users')->get();
        foreach ($users as $user)
{
var_dump($user->name);
}
    }
    
}

?>