<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

use App\User, Illuminate\Support\Facades\Cache;
use App\Helpers\UserHelper;
use Illuminate\Support\Facades\Input;

//------------------Frontend-----------------------
Route::group(['domain'=>'sologame.slg.io'],function (){
    Route::group(['namespace'=>"Frontend"],function (){
        Route::get('/gift-code',['as'=>'get.gift.code','uses'=>'FrontendController@Home']);
        Route::get('/chi-tiet-event/{id}',['as'=>'event.detail','uses'=>'FrontendController@EventDetail']);
        Route::get('/loginFB/{id}',['as'=>'loginfb','uses'=>'FaceGiftController@LoginFacebook']);
        Route::get('/facebook/callback/{id}',['as'=>'call.back','uses'=>'FaceGiftController@FacebookCallback']);
        Route::post('/ring-gift-code',['as'=>'ring.gift.code','uses'=>'FrontendController@RingGiftCode']);
        Route::post('/change-server',['as'=>'change.server.detail','uses'=>'FrontendController@ChangeServerDetail']);
        Route::post('/share-event',['as'=>'share.event','uses'=>'FrontendController@ShareEvent']);
    });
});


//-------------------- ID -----------------------
Route::group(['domain' => 'id.slg.io'], function () {
    Route::group(['middleware' => 'auth'], function () {

        Route::get('/', function () {
            return redirect()->route('profile');
        });

        Route::controller('profile', 'Profile\ProfileController', [
            'get_index' => 'profile',
        ]);
    });

//---------------------

    Route::group(['namespace' => 'Auth'], function () {
        Route::controller('password', 'PasswordController');

        Route::group(['prefix' => 'auth'], function () {

            Route::get('login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);

            Route::post('login', 'AuthController@postLogin');

            Route::get('logout', ['as' => 'logout', function () {
                    $callback = Input::get('callback');
                    Auth::logout(); // log the user out of our application

                    if (!empty($callback)) {
                        return redirect($callback . '?logout=true'); // redirect the user to the login screen
                    }
                    return redirect()->route('login'); // redirect the user to the login screen
                }]);

            // Registration routes...
            Route::get('register', ['as' => 'register', 'uses' => 'AuthController@getRegister']);
            Route::post('register', 'AuthController@postRegister');

            //Social Login
            Route::get('login/{provider?}', [
                'uses' => 'AuthOpenIdController@getSocialAuth',
                'as' => 'auth.getSocialAuth'
            ]);

            Route::get('login/callback/{provider?}', [
                'uses' => 'AuthOpenIdController@getSocialAuthCallback',
                'as' => 'auth.getSocialAuthCallback'
            ]);
        });

        Route::group(['prefix' => 'oauth'], function () {
            Route::get('authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params'], function () {
            // display a form where the user can authorize the client to access it's data
            $authParams = Authorizer::getAuthCodeRequestParams();
            $formParams = array_except($authParams, 'client');
            $formParams['client_id'] = $authParams['client']->getId();

            // Check user id login
            if (Auth::check()) {
                $authParams['user_id'] = Auth::user()->id;

                $apps = Cache::get('active_app_list');
                if (isset($apps[$formParams['client_id']])) {
                    UserHelper::logUserGame($authParams['user_id'], $apps[$formParams['client_id']]);
                }
                
                $redirectUri = Authorizer::issueAuthCode('user', Auth::user()->id, $authParams);
                return redirect($redirectUri);
            }
            return View::make('oauth.authorization', ['params' => $formParams, 'client' => $authParams['client']]);
        }]);

            Route::post('authorize', ['as' => 'oauth.authorize.post', 'middleware' => ['check-authorization-params'],
                'uses' => 'OAuthController@postAuthorize'
            ]);

            Route::get('register', ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params'], function () {
            // display a form where the user can authorize the client to access it's data
            $authParams = Authorizer::getAuthCodeRequestParams();
            $formParams = array_except($authParams, 'client');
            $formParams['client_id'] = $authParams['client']->getId();

            return View::make('oauth.register', ['params' => $formParams, 'client' => $authParams['client']]);
        }]);

            Route::post('register', ['as' => 'oauth.register.post', 'middleware' => ['check-authorization-params'],
                'uses' => 'OAuthController@postRegister'
            ]);

            Route::post('access_token', ['as' => 'access_token', function () {
                    return Authorizer::issueAccessToken();
                }]);
        });
    });
});


//-------------------- PAY -----------------------
Route::group(['domain' => 'pay.io', 'middleware' => ['auth']], function () {
    Route::get('/', ['as' => 'topupcash', function () {
            return redirect()->route('topupcash.index');
        }]);

    Route::group(['prefix' => 'topcoin', 'namespace' => 'Payment'], function () {
        Route::get('/', ['as' => 'topcoin', 'uses' => 'TopCoinController@index']);

        Route::get('/{slug}', ['as' => 'topcoin.game', 'uses' => 'TopCoinController@game']);

        Route::post('transfer/{slug}', ['as' => 'topcoin.transfer', 'uses' => 'TopCoinController@transfer', 'middleware' => 'csrf']);
    });

    Route::controller('topupcash', 'Payment\TopupController', [
        'getIndex' => 'topupcash.index',
        'getTelco' => 'topupcash.telco',
        'postTelco' => 'topupcash.post.telco',
        'getBank' => 'topupcash.bank',
        'postBank' => 'topupcash.post.bank',
        'getSuccess' => 'topupcash.success',
        'getFail' => 'topupcash.fail',
        'getNganLuong' => 'topupcash.get.nl',
        'postNganLuong' => 'topupcash.post.nl',
        'getNganLuongCallback' => 'topupcash.get.nl.callback',
    ]);

    Route::group(['prefix' => 'transaction-history'], function () {
        Route::get('/', ['as' => 'transaction-history', function () {
                return redirect()->route('topup-history');
            }]);

        Route::get('topup', ['as' => 'topup-history', 'uses' => 'Util\LogController@getLogTopup']);
        Route::get('topcoin', ['as' => 'transfer-coin-history', 'uses' => 'Util\LogController@getLogTransferCoin']);
    });
});


Route::group(['domain' => 'pay.slg.io'], function () {
    Route::controller('mtopupcash', 'Payment\TopupMobileController');
    Route::controller('mbuyitem', 'Payment\BuyItemMobileController');
});

//-------------------- API -----------------------
Route::group(['domain' => 'api.io', 'prefix' => 'apiv1'], function () {
    Route::group(['middleware' => 'oauth'], function () {
        Route::get('user', function () {
            return User::all();
        });

        Route::get('me', ['as' => 'api_get_user', function () {
                $resourceOwnerType = Authorizer::getResourceOwnerType();
                $resourceOwnerId = Authorizer::getResourceOwnerId();

                if ($resourceOwnerType === 'user') {
                    $user = Auth::loginUsingId($resourceOwnerId);
                    return $user;
                }
                return 'errors';
            }]);
    });

// Route::get('test1',['as'=>'test','uses'=>''])
    Route::group(['namespace' => 'Api'], function () {

        Route::group(['middleware' => 'log-request'], function () {
            Route::any('user/google/login', 'AuthenticationController@google');
            Route::any('user/facebook/login', ['as' => 'user-login-facebook', 'uses' => 'AuthenticationController@facebook']);
            Route::any('user/device/login', ['as' => 'login-device', 'uses' => 'AuthenticationController@device']);
            Route::any('store-device-token', ['as' => 'store-device-token', 'uses' => 'AuthenticationController@storeDeviceToken']);
            Route::any('save_device_token', 'AuthenticationController@storeDeviceToken');

            Route::any('user/register', ['as' => 'user-register', 'uses' => 'UserAPIControlller@register']);
            Route::any('user/login', ['as' => 'user-login', 'uses' => 'UserAPIControlller@login']);

            Route::any('user/get-user-info', ['as' => 'getuserinfo', 'uses' => 'UserAPIControlller@getUserinf']);
            Route::any('user/change-pass', ['as' => 'changepass', 'uses' => 'UserAPIControlller@Changepass']);
            Route::any('user/change-user-info', ['as' => 'chanuserinfo', 'uses' => 'UserAPIControlller@Changeinfo']);
            Route::controller('appleservice', 'AppleServiceController');
        });

        Route::get('giftcode', 'Api\ApiGiftcodeController@giftcodes');
        Route::get('getlistgame', 'ShowListController@getListGame');
        Route::get('getloguser', 'ShowListController@getLogUser');

        Route::get('apidauupdate', 'DauupdateController@index');
        Route::get('apiniuupdate', 'NiuupdateController@index');
        Route::get('rediskeyupdate', 'AllkeybackupController@index');
        Route::controller('news', 'NewsController');
        Route::controller('transaction-log', 'TransactionLogController');

        Route::get('check-approval', 'CheckApprovalController@check_approval');
        Route::get('homepage', 'CheckApprovalController@homepage');
        Route::get('gameconfig', 'CheckApprovalController@homepage');
    });
    
    Route::get('list_api_product', 'ApiProduct\ApiProductController@index');
    Route::get('list_api_product_apple', 'ApiProduct\ApiProductAppleController@index');
    Route::get('get_all_log_device', 'ApiDevice\ApiDeviceController@index');
    Route::get('get_all_log_device_by_user', 'ApiDevice\ApiDeviceByUserController@index');

    Route::get('log-revenue', 'RevenueController@store');
    Route::get('log-atm', 'RevenueController@getCheckAtmTrans');
    Route::get('log-revenueapi', 'RevenueController@getLogrevenuecpi');
    Route::get('log-server-game', 'Manage\ServerController@store1');
    Route::get('log-game', 'Manage\ServerController@storegame1');
    Route::get('server-list', 'Manage\ServerController@serverlist');
    Route::get('server-user', 'Manage\ServerController@serveruser');
    Route::get('giftcode', 'Manage\ApiGiftcodeController@giftcodes');
    Route::get('userlike', 'Manage\ApiUserController@likepage');
    
    Route::any('oauth/validate-access-token', ['as' => 'validate-access-token', 'uses' => 'Auth\OAuthController@validateAccessToken']);

    //Route::controller('appleservice', 'Api\AppleServiceController');

    Route::controller('appleservice1', 'Api\AppleService1Controller');
});

//-------------------- TOOLS -----------------------
Route::group(['domain' => 'tools.slg.io', 'middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect()->route('revenue.index');
    });

    Route::group([
        'middleware' => 'acl',
        'is' => 'administrator|deploy'], function () {

        Route::match(['get', 'post'], 'notification', [
            'as' => 'notification',
            'uses' => 'NotificationController@index'
        ]);

        Route::get('push-news', [
            'as' => 'push-news',
            'uses' => 'Api\NewsController@push'
        ]);

        Route::match(['get', 'post'], 'revenue-topup', [
            'as' => 'revenue-topup',
            'uses' => 'RevenueController@revenueTopup'
        ]);

        Route::controller('marketingmail', 'MarketingMailController', [
            'getIndex' => 'marketingmail.index',
            'postInsert' => 'marketingmail.insert'
        ]);

        Route::controller('menu', 'MenuController', [
            'getIndex' => 'menu.index',

        ]);
    });

    Route::group([
        'middleware' => 'acl',
        'is' => 'administrator|partner|cp|deploy'], function () {

        Route::post('daulog', 'DaulogController@store');
        Route::get('daulog', 'DaulogController@index');
        Route::controllers(['logniu' => 'Log\NiulogController']);

        Route::get('revenue', ['as' => 'revenue.index', 'uses' => 'RevenueController@index']);
    });

    Route::group([
        'middleware' => 'acl',
        'is' => 'administrator'], function () {

        Route::get('laravel-log', ['as' => 'laravel-log', 'uses' => 'Util\LogController@laravel']);

        Route::controller('users', 'UserController', [
            'getEdit' => 'user.edit',
            'getSearch' => 'user.search',
            'postUpdate' => 'user.update',
            'getFix' => 'user.fix.get',
            'postFix' => 'user.fix.post',
        ]);
        Route::controller('accounttest', 'AccounttestController', [
            'getIndex' => 'accounttest.index',
            'postInsert' => 'accounttest.insert',
            'getDelete' => 'accounttest.delete',
            'getSearch' => 'accounttest.search',
        ]);

        Route::controller('compensations', 'CompensationController', [
            'getEdit' => 'compensation.edit',
            'getSearch' => 'compensation.search',
            'postUpdate' => 'compensation.update',
            'getEditCoin' => 'compensation.editCoin'
        ]);
        
        Route::controller('blocked-payment', 'BlocklistController', [
            'getEdit' => 'blocked-payment.edit',
            'getSearch' => 'blocked-payment.search',
            'postUpdate' => 'blocked-payment.update',
            'getAdd' => 'blocked-payment.getAdd',
            'postAdd' => 'blocked-payment.postAdd',
            'postDelete'  => 'blocked-payment.postDelete'
        ]);
        
        Route::controller('insertlog', 'InsertLogController', [
            'getEdit' => 'insertlog.edit',
            'getSearch' => 'insertlog.search',            
        ]);
        
        Route::controller('merge-acc-face', 'MergeAccFaceController', [
            'getSearch' => 'merge-acc-face.search',
            'postUpdate' => 'merge-acc-face.update',
            'getLoghistory' => 'merge-acc-face.loghistory',
            'getBack' => 'merge-acc-face.backmerge'
        ]);
        Route::controller('settings', 'SettingsController', [
            'getIndex' => 'settings.index',
            'postUpdate' => 'settings.update',
            'postAjaxGenerateMasterPassword' => 'settings.generate-master-password'
        ]);

        Route::group(['namespace' => 'Payment'], function () {
            Route::controller('card-test', 'CardTestController', [
                'getIndex' => 'card-test.index',
                'postCreate' => 'card-test.create',
            ]);

            Route::match(['get', 'post'], 'scratch-card/transaction', [
                'as' => 'scratch-card-transaction',
                'uses' => 'ScratchCardController@checkTransaction'
            ]);
        });

        Route::group(['namespace' => 'Manage'], function () {
            Route::controller('partner', 'PartnerController', [
                'getIndex' => 'partner.index',
                'getSearch' => 'partner.search',
            ]);
            Route::post('partner1', 'PartnerController@store');
            Route::controller('cpid', 'CpidController', [
                'getIndex' => 'cpid.index',
                'getSearch' => 'cpid.search',
            ]);
            Route::post('cpid1', 'CpidController@store');
            Route::controllers(['merchant_app' => 'MerchantController']);
            Route::controllers(['oauth_client_endpoints' => 'OauthentController']);
            Route::controller('server', 'ServerController', [
                'getIndex' => 'server.index',
                'getSearch' => 'server.search',
            ]);
            Route::post('server1', 'ServerController@store');
            Route::controller('wheel', 'WheelitemController', [
                'getIndex' => 'wheel.index',
            ]);
            Route::post('wheel3', 'WheelitemController@store');
            Route::controllers(['merchant_app_product' => 'MerchantAppProductController']);
            Route::controllers(['merchant_app_product_apple' => 'MerchantAppProductAppleController']);
            Route::get('gift-code-list',['as'=>'list.event','uses'=>'GiftController@getEvent']);
            Route::get('add-event',['as'=>'add.Gift','uses'=>'GiftController@addEvent']);
            Route::post('ajax-add-game',['as'=>'ajax_gift_game','uses'=>'GiftController@AjaxGame']);
            Route::post('post-event',['as'=>'post_event','uses'=>'GiftController@PostEvent']);
            Route::post('del_list_event',['as'=>'del_list','uses'=>'GiftController@DeleteEvent']);
        });

        Route::get('request-log/{page?}', ['as' => 'request-log', 'uses' => 'Util\LogController@getRequestLog'])->where('page', '[0-9]+');
        Route::get('clear-request-log', ['as' => 'clear-request-log', 'uses' => 'Util\LogController@clearRequestLog']);
        Route::get('clear-redis-session', ['as' => 'clear-redis-session', 'uses' => 'SettingsController@clearRedisSession']);


        Route::group(['prefix' => 'transfer-coin-log', 'namespace' => 'Util'], function () {
            Route::get('/', ['as' => 'transfer-coin-log', 'uses' => 'LogController@getIndexLogTransferCoin']);
            Route::get('search', ['as' => 'transfer-coin-log-search', 'uses' => 'LogController@getAllLogTransferCoin']);
        });

        Route::get('logGD/{page?}', ['as' => 'log-GD', 'uses' => 'Util\LogController@index']);
        Route::post('logGD', 'Util\LogController@searchLogGD');       
        
        Route::get('sendMailAuto', 'SendMailAuto@sendMail');

        Route::get('add-coin-log/{page?}', ['as' => 'log-add-coin', 'uses' => 'Log\LogAddCoinController@index']);
        Route::post('add-coin-log', 'Log\LogAddCoinController@searchLogAddCoins');

        Route::group(['namespace' => 'LogGd'], function () {
            Route::get('log_coin/{page?}', ['as' => 'log-coin', 'uses' => 'LogCoinUserController@index']);
            Route::post('log_coin', 'LogCoinUserController@store');

            Route::get('log_transfer_coin/{page?}', ['as' => 'log_transfer_user', 'uses' => 'LogTransferCoinUserController@index']);
            Route::post('log_transfer_coin', 'LogTransferCoinUserController@store');

            Route::get('log_buy_item/{page?}', ['as' => 'log-buy-item', 'uses' => 'LogTransferZingSohaController@index']);
            Route::post('log_buy_item', 'LogTransferZingSohaController@store');
            Route::post('log_buy_item_game', 'LogTransferZingSohaController@store1');

            Route::get('log_detail_transfer/{page?}', ['as' => 'log-detail-transfer', 'uses' => 'LogDetailTransferController@index']);
            Route::post('log_detail_transfer', 'LogDetailTransferController@store');
            Route::post('log_detail_transfer_partner', 'LogDetailTransferController@store1');

            Route::get('log_zing/{page?}', ['as' => 'log-zing', 'uses' => 'LogZingController@index']);
            Route::post('log_zing', 'LogZingController@store');

            Route::get('log_soha/{page?}', ['as' => 'log-soha', 'uses' => 'LogSohaController@index']);
            Route::post('log_soha', 'LogSohaController@store');

            Route::get('log_mwork/{page?}', ['as' => 'log-mwork', 'uses' => 'LogMworkController@index']);
            Route::post('log_mwork', 'LogMworkController@store');

            Route::get('log_facebook/{page?}', ['as' => 'log-facebook', 'uses' => 'LogFacebookController@index']);
            Route::post('log_facebook', 'LogFacebookController@store');

            Route::get('log_fpay/{page?}', ['as' => 'log-fpay', 'uses' => 'LogFpayController@index']);
            Route::post('log_fpay', 'LogFpayController@store');

            Route::get('log_fpay1/{page?}', ['as' => 'log-fpay1', 'uses' => 'LogFpay1Controller@index']);
            Route::post('log_fpay1', 'LogFpay1Controller@store');

            Route::get('log_garena/{page?}', ['as' => 'log-garena', 'uses' => 'LogGarenaController@index']);
            Route::post('log_garena', 'LogGarenaController@store');
        });
    });
});


//-------------------- APPS -----------------------
Route::group(['domain' => 'app.slg.io', 'namespace' => 'App'], function () {
    Route::any('onepiece/fbonepieceonline', 'OnePieceController@fbonepieceonline');
    Route::any('onepiece/fbonepieceonline2', 'OnePieceController@fbonepieceonline');
    Route::get('onepiece/FBcallback', 'OnePieceController@getFBcallback');
    Route::get('onepiece/login_slg', 'OnePieceController@login_slg');
//    
    Route::controllers(['onepiece' => 'OnePieceController']);
    
    
    Route::any('tamquoctruyenky/fbtamquoctruyenky', 'TamQuocTruyenKyController@fbtamquoctruyenky');
    Route::get('tamquoctruyenky/FBcallback', 'TamQuocTruyenKyController@getFBcallback');
    Route::get('tamquoctruyenky/login_slg', 'TamQuocTruyenKyController@login_slg');    
    Route::controllers(['tamquoctruyenky' => 'TamQuocTruyenKyController']);
    
    Route::controller('onepiece-zing', 'OnePieceZingController', [
        'getIndex' => 'onepiece-zing.index',
        'getPayment' => 'onepiece-zing.payment',
        'getPaymentCallback' => 'onepiece-zing.payment-callback',
        'getTop' => 'onepiece-zing.top',
        'getAuth' => 'onepiece-zing.auth',
        'getPlay' => 'onepiece-zing.play',
        'getLogout' => 'onepiece-zing.logout',
        'postBilling' => 'onepiece-zing.billing',
    ]);
//
    Route::any('thienlong/fbthienlongslg', 'ThienlongController@fbthienlongslg');
    Route::get('thienlong/FBcallback', 'ThienlongController@getFBcallback');
    Route::get('thienlong/login_slg', 'ThienlongController@login_slg');
//
    Route::controllers(['thienlong' => 'ThienlongController']);        
    
    Route::any('fish/FBfishslg', 'FishController@FbFish_slg');
    Route::get('fish/FBcallback', 'FishController@getFBcallback');
    Route::get('fish/login_slg', 'FishController@login_slg');
    
    Route::controllers(['fish' => 'FishController']);

    Route::any('manga/fbmangaheros', 'MangaController@fbmangaheros');
    Route::get('manga/FBcallback', 'MangaController@getFBcallback');
    Route::get('manga/login_slg', 'MangaController@login_slg');
    Route::controllers(['manga' => 'MangaController']);
//
//
    Route::any('linhvuong/fblinhvuong', 'LinhvuongController@fblinhvuong');
    Route::get('linhvuong/FBcallback', 'LinhvuongController@getFBcallback');
    Route::get('linhvuong/login_slg', 'LinhvuongController@login_slg');
    Route::get('linhvuong/sohapayment', 'LinhvuongController@getPayment');
    Route::get('linhvuong/sohanewscontent', 'LinhvuongController@getNewscontent');    
    Route::get('linhvuong/sohasv', 'LinhvuongController@getSlg');
    Route::any('linhvuong/sohapaycallback', 'LinhvuongController@getSohapaycallback');
    Route::any('linhvuong/soharedirectsohaform', 'LinhvuongController@getRedirectsohaform');
    Route::controllers(['linhvuong' => 'LinhvuongController']);
//    
//    
    
//
//
    Route::any('wheeloffortune/fbwheeloffortune', 'WheeloffortuneController@fbwheeloffortuneslg');
    Route::get('wheeloffortune/FBcallback', 'WheeloffortuneController@getFBcallback');
    Route::get('wheeloffortune/login_slg', 'WheeloffortuneController@login_slg');
    Route::post('wheel1', 'WheeloffortuneController@store');
    Route::post('wheel2', 'WheeloffortuneController@store1');
    Route::post('wheel3', 'WheeloffortuneController@store2');

    Route::get('games', 'AppController@getAdGameList');
});

Route::group(array('domain' => 'task.slg.vn'), function() {
    Route::get('/', function() {
        dd('test');
    });
    Route::controller('revenue', 'Task\UpdaterevenueController');
});


//
////-------------------- TASK -----------------------
//Route::group(['domain' => 'task.slg.vn', 'namespace' => 'Task'], function () {
//    Route::controllers(['revenue' => 'TaskRevenueController']);
//});
//-------------------- ERRORS -----------------------
Route::get('500', function () {
    abort(500);
});

Route::get('404', function () {
    abort(404);
});

//-------------------- Thien Long API -----------------------
Route::group(['domain' => 'api.slg.vn', 'prefix' => 'tl/v1', 'middleware' => 'log-request'], function () {
    Route::group(['namespace' => 'Api\ThienLong\V1'], function () {
        Route::controller('user', 'UserController');
        Route::controller('device', 'DeviceController');
    });
});

Route::group(['domain' => 'fpay.vn'], function () {
    Route::get('/', function () {
        return 'ok';
    });
});
