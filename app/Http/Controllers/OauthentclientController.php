<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Oauth_client_endpoints;
use App\Models\Oauth_clients;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class OauthentclientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $list_total=Oauth_clients::All();
        $list=DB::table('oauth_clients')->paginate(10);
        return view('list_oauth_clients', compact('list','list_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add()
    {
        //$oauth_client = Oauth_clients::All();
        //$data['oauth_client'] = $oauth_client;
        return view('add_oauth_clients');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $original_string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $merchant = new Oauth_clients;
        //Lấy thông tin từ các input đưa vào
        //trong model Merchant_app_cp
        $merchant->id = rand(1000000000,9999999999);
        $merchant->name = $dulieu_tu_input["game"];
        $merchant->secret=get_random_string($original_string, 20);
        $merchant->created_at = date('Y-m-d H:i:s',time());
        //Tiến hành lưu dữ liệu vào database
        $merchant->save();
        echo "Thêm thành công!";
        return redirect('oauth_clients');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
        $id = $_GET['id'];
        $results= DB::table('oauth_clients') 
                ->where('id', '=', $id)->get();
        return view('edit_oauth_clients', compact('results'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $id = $_GET['id'];
        $original_string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $dulieu_tu_input = $request->all();
        $merchant = new Oauth_clients;
        DB::table('oauth_clients')->where('id', $id)->update(['secret' => get_random_string($original_string, 20),
           'name' => $dulieu_tu_input["game"], 'updated_at' => date('Y-m-d H:i:s',time())]);

        return redirect('oauth_clients');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete()
    {
        $client_id = $_GET['id'];
        DB::table('oauth_clients')->where('id', '=', $client_id)->delete();
        return redirect('oauth_clients');
    }
    
}
 function get_random_string($valid_chars, $length)
    {
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
    }
