<?php
namespace App\Http\Controllers\Manage;
use Auth;
use App\Models\MerchantApp;
use App\Models\MerchantAppProductApple;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use App\Http\Requests\CheckProductAppleRequest;
use DB, Session, App\User, Kodeine\Acl\Models\Eloquent\Role;

//use App\User;
class MerchantAppProductAppleController extends Controller
{
     public function __construct(Request $request)
    {
        $this->beforeFilter(function () use ($request) {
            $callback = $request->url();
            if (!Auth::check()) {
                return redirect('http://id.slg.vn/auth/login?callback=' . $callback);
            }
        });
    }

    public function missingMethod($parameters = array())
    {

//        return view('errors.404');
        return redirect('merchant_app_product_apple/index');
    }
    public function getIndex()
    {
        $results_total=MerchantAppProductApple::all();
        $results = MerchantAppProductApple::list_product_apple();
        return view('/productapple/list_product_apple', compact('results','results_total'));
    }
    public function getAdd()
    {
       
        $marchent_app = MerchantApp::All();

        return view('/productapple/add_product_apple', compact('marchent_app'));
    }
    public function postIndex(CheckProductAppleRequest $request){
        $dulieu_tu_input = $request->all();
        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('product_id', 'title','amount','money_in_game','description');
        $merchant = new MerchantAppProductApple;
        $merchant->product_id=$dulieu_tu_input1["product_id"];
        $merchant->merchant_app_id = $dulieu_tu_input["merchant_app_id"];
        $merchant->title=$dulieu_tu_input1['title'];
        $merchant->amount=$dulieu_tu_input1['amount'];
        $merchant->money_in_game=$dulieu_tu_input1['money_in_game'];
        $merchant->description=$dulieu_tu_input1['description'];
//Tiến hành lưu dữ liệu vào database
        $merchant->save();
        Session::flash('flash_success', 'The product added successfully.');
        return redirect('merchant_app_product_apple');
    }
    public function getEdit(){
        $id=$_GET['id'];
        $result=MerchantAppProductApple::list_product_apple_id($id);
        foreach ($result as $value){}
        $merchant_app = DB::table('merchant_app')->where('id','!=',$value->id)->get();
        return view('/productapple/edit_product_apple', compact('merchant_app','result'));
    }
    public function postEdit(CheckProductAppleRequest $request){
        $id=$_GET['id'];
        $dulieu_tu_input = $request->all();
        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('product_id', 'title','amount','money_in_game','description');
        $merchant = new MerchantAppProductApple;
         DB::table('merchant_app_produc_apple')->where('product_apple_id', $id)->update(
            ['product_id' => $dulieu_tu_input1["product_id"],
            'merchant_app_id'=>$dulieu_tu_input['merchant_app_id'],
            'title'=>$dulieu_tu_input1["title"],
            'amount'=>$dulieu_tu_input1["amount"],
            'money_in_game'=>$dulieu_tu_input1["money_in_game"],    
            'description'=>$dulieu_tu_input1["description"],
            ]);
        Session::flash('flash_success', 'The product updated successfully.');
        return redirect('merchant_app_product_apple');
    }

    public function getDelete(){
        $id=$_GET['id'];
        DB::table('merchant_app_produc_apple')->where('product_apple_id', '=', $id)->delete();
        Session::flash('flash_success', 'The product deleted successfully.');
        return redirect('merchant_app_product_apple');
    }
}
?>