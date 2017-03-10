<?php
namespace App\Http\Controllers\Manage;
use Auth;
use Illuminate\Http\Request;
use App\Models\MerchantApp;
use App\Models\Oauth_client_endpoints;
use App\Http\Requests;
use App\Http\Requests\MerchantRequest;
use App\Http\Controllers\Controller;
use DB, Session, Cache;

class MerchantController extends Controller
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
        return redirect('merchant_app/index');
    }
    public function getIndex()
    {
        $results_total=MerchantApp::All(); 
        $results = DB::table('merchant_app')->paginate(10);
        return view('/merchant/list_merchant',compact('results','results_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getAdd()
    {
        $results=Oauth_client_endpoints::All();
        return view('/merchant/add_merchant',  compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postIndex(MerchantRequest $request)
    {
        $dulieu_tu_input = $request->all();
//        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('name','facebook_id','slug','url_news','url_homepage','thumb','slider','midder','content','logo',
            'profile', 'iframe_slider', 'thumb_url','slider_url','midder_url','content_url','logo_url','profile_url','description','user_num');

        $images = [
            'thumb' => $dulieu_tu_input['thumb'],
            'slider' => $dulieu_tu_input['slider'],
            'midder' => $dulieu_tu_input['midder'],
            'content' => $dulieu_tu_input['content'],
            'logo' => $dulieu_tu_input['logo'],
            'profile' => $dulieu_tu_input['profile'],
            'iframe_slider' => $dulieu_tu_input['iframe_slider'],
        ];

        $urls = [
            'thumb_url' => $dulieu_tu_input['thumb_url'],
            'slider_url' => $dulieu_tu_input['slider_url'],
            'midder_url' => $dulieu_tu_input['midder_url'],
            'content_url' => $dulieu_tu_input['content_url'],
            'logo_url' => $dulieu_tu_input['logo_url'],
            'profile_url' => $dulieu_tu_input['profile_url'],
        ];

        $merchant = new MerchantApp;
        $merchant->name = $dulieu_tu_input["name"];
        //$merchant->url_logo="array('logo'=>'".$dulieu_tu_input['logo']."','slider'=>'".$dulieu_tu_input['slider']."','midder'=>'".$dulieu_tu_input['midder']."','content'=>'".$dulieu_tu_input['content']."')";
        $merchant->clientid=$dulieu_tu_input['clientid'];
        $merchant->status=$dulieu_tu_input['status'];
        $merchant->facebook_id=$dulieu_tu_input['facebook_id'];
        $merchant->facebook_secret=$dulieu_tu_input['facebook_secret'];
        $merchant->app_description=$dulieu_tu_input['description'];
        $merchant->user_num=$dulieu_tu_input['user_num'];
        $merchant->slug=$dulieu_tu_input['slug'];
        $merchant->gametype=$dulieu_tu_input['gametype'];
        $merchant->images = json_encode($images);
        $merchant->is_new = $dulieu_tu_input["optionsRadiosnew"];
        $merchant->is_hot = $dulieu_tu_input["optionsRadioshot"];
        $merchant->imagemenu = $dulieu_tu_input["image_menu"];
        $merchant->level_priority = $dulieu_tu_input["order"];
        $merchant->url_news=$dulieu_tu_input['url_news'];
        $merchant->url_homepage=$dulieu_tu_input['url_homepage'];

        $topcoin = $request->has('topcoin') ? 1 : 0;

        $merchant->topcoin = $topcoin;
        $merchant->url = json_encode($urls);
//Tiến hành lưu dữ liệu vào database
        $merchant->save();
        MerchantApp::setgamelist();
        MerchantApp::setCache();

        Session::flash('flash_success', 'The game added successfully.');
        return redirect('merchant_app');
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
    public function getEdit()
    {
        $appid = $_GET['appid'];
        $result1 = DB::table('merchant_app')->where('id', '=', $appid)->
            get();
        foreach ($result1 as $value){}
        $result2=DB::table('oauth_client_endpoints')->where('client_id','!=',$value->clientid)->get();
        $result3=DB::table('oauth_client_endpoints')->where('client_id','=',$value->clientid)->get();
        return view('/merchant/edit_merchant', compact('result1','result2','result3'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postEdit(MerchantRequest $request)
    {
        $dulieu_tu_input = $request->all();
//        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('name','facebook_id','slug','url_news','url_homepage','thumb','slider','midder','content','logo',
            'profile', 'iframe_slider', 'thumb_url','slider_url','midder_url','content_url','logo_url','profile_url','description','user_num');
        $appid=$_GET['appid'];

        $images = [
            'thumb' => $dulieu_tu_input['thumb'],
            'slider' => $dulieu_tu_input['slider'],
            'midder' => $dulieu_tu_input['midder'],
            'content' => $dulieu_tu_input['content'],
            'logo' => $dulieu_tu_input['logo'],
            'profile' => $dulieu_tu_input['profile'],
            'iframe_slider' => $dulieu_tu_input['iframe_slider'],
        ];

        $urls = [
            'thumb_url' => $dulieu_tu_input['thumb_url'],
            'slider_url' => $dulieu_tu_input['slider_url'],
            'midder_url' => $dulieu_tu_input['midder_url'],
            'content_url' => $dulieu_tu_input['content_url'],
            'logo_url' => $dulieu_tu_input['logo_url'],
            'profile_url' => $dulieu_tu_input['profile_url'],
        ];

        $topcoin = $request->has('topcoin') ? 1 : 0;

        DB::table('merchant_app')->where('id', $appid)->update(
            ['name' => $dulieu_tu_input["name"],
                'clientid'=>$dulieu_tu_input['clientid'],
                'status'=>$dulieu_tu_input["status"],
                'facebook_id'=>$dulieu_tu_input["facebook_id"],
                'facebook_secret'=>$dulieu_tu_input["facebook_secret"],
                'app_description'=>$dulieu_tu_input['description'],
                'user_num'=>$dulieu_tu_input['user_num'],
                'slug'=>$dulieu_tu_input["slug"],
                'gametype'=>$dulieu_tu_input["gametype"],
                'images' => json_encode($images),
                'is_new' => $dulieu_tu_input["optionsRadiosnew"],
                'is_hot' => $dulieu_tu_input["optionsRadioshot"],
                'imagemenu' => $dulieu_tu_input["image_menu"],
                'level_priority' => $dulieu_tu_input["order"],
                'url_news'=>$dulieu_tu_input['url_news'],
                'url_homepage'=>$dulieu_tu_input['url_homepage'],
                'url' => json_encode($urls),
                'topcoin' => $topcoin,
            ]);

        MerchantApp::setgamelist();
        Session::flash('flash_success', 'The game updated successfully.');

        MerchantApp::setCache();

        return redirect('merchant_app');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    public function getDelete(){
        $appid=$_GET['appid'];
        MerchantApp::setgamelist();
        DB::table('merchant_app')->where('id', '=', $appid)->delete();
        Session::flash('flash_success', 'The game deleted successfully.');
        return redirect('merchant_app');
    }
}
