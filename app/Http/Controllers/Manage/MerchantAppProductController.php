<?php
namespace App\Http\Controllers\Manage;
use Auth;
use App\Models\MerchantApp;
use App\Models\MerchantAppProduct;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use App\Http\Requests\CheckProductRequest;
use DB, Session, App\User, Kodeine\Acl\Models\Eloquent\Role;

//use App\User;
class MerchantAppProductController extends Controller
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
        return redirect('merchant_app_product/index');
    }
    public function getIndex()
    {
        $results_total=MerchantAppProduct::all();
        $results = MerchantAppProduct::list_product();
        return view('/product/list_product', compact('results','results_total'));
    }
    public function getAdd()
    {
       
        $marchent_app = MerchantApp::All();

        return view('/product/add_product', compact('marchent_app'));
    }
    public function postIndex(CheckProductRequest $request){
        $dulieu_tu_input = $request->all();
        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('product_name', 'product_price','amount_fpay','product_description');
        $merchant = new MerchantAppProduct;
        $merchant->merchant_app_id = $dulieu_tu_input["merchant_app_id"];
        $merchant->product_name=$dulieu_tu_input['product_name'];
        $merchant->product_price=$dulieu_tu_input['product_price'];
        $merchant->amount_fpay=$dulieu_tu_input['amount_fpay'];
        $merchant->product_description=$dulieu_tu_input['product_description'];
//Tiến hành lưu dữ liệu vào database
        $merchant->save();
        MerchantApp::setgamelist();
        Session::flash('flash_success', 'The product added successfully.');
        return redirect('merchant_app_product');
    }
    public function getEdit(){
        $id=$_GET['productid'];
        $result=MerchantAppProduct::list_product_id($id);
        foreach ($result as $value){}
        $merchant_app = DB::table('merchant_app')->where('id','!=',$value->id)->get();
        return view('/product/edit_product', compact('merchant_app','result'));
    }
    public function postEdit(CheckProductRequest $request){
        $productid=$_GET['productid'];
        $dulieu_tu_input = $request->all();
        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('product_name', 'product_price','amount_fpay','product_description');
        $merchant = new MerchantAppProduct;
         DB::table('merchant_app_product')->where('product_id', $productid)->update(
            ['merchant_app_id' => $dulieu_tu_input["merchant_app_id"],
            'product_name'=>$dulieu_tu_input['product_name'],
            'product_price'=>$dulieu_tu_input["product_price"],
            'amount_fpay'=>$dulieu_tu_input["amount_fpay"],
            'product_description'=>$dulieu_tu_input["product_description"],
            ]);
        Session::flash('flash_success', 'The product updated successfully.');
        return redirect('merchant_app_product');
    }

    public function getDelete(){
        $productid=$_GET['productid'];
        DB::table('merchant_app_product')->where('product_id', '=', $productid)->delete();
        Session::flash('flash_success', 'The product deleted successfully.');
        return redirect('merchant_app_product');
    }
}
?>