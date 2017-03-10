<?php
namespace App\Http\Controllers\Manage;
use Auth;
use App\Models\Wheel_item;
use App\Models\Gift_code;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use App\Http\Requests\CheckWheelRequest;
use DB, Session,Response, App\User, Kodeine\Acl\Models\Eloquent\Role;

//use App\User;
class WheelitemController extends Controller
{
    public function getIndex()
    {
        $wheel = DB::table('wheel_item')
            ->paginate(10);

        return view('/wheel/list_item_wheel', compact('wheel'));
    }

    public function getAdd()
    {
        return view('/wheel/add_item_wheel');
    }

    public function postIndex(CheckWheelRequest $request)
    {
        $request->flashOnly('eventname', 'imageitem','turndial','rate1','item1','quantity1','rate2','item2','quantity2'
            ,'rate3','item3','quantity3','rate4','item4','quantity4','rate5','item5','quantity5','rate6','item6','quantity6'
            ,'rate7','item7','quantity7','rate8','item8','quantity8');
        $dulieu_tu_input = $request->all();
        $event = $dulieu_tu_input['eventname'];
        $number = $dulieu_tu_input['itemnumber'];
        $image = $dulieu_tu_input['imageitem'];
        $is_use = $dulieu_tu_input["optionsRadios"];
        $dial = $dulieu_tu_input["turndial"];
        $item = array();
        $item_quantity = array();
        if($number == 8){
        $item = [
            $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['rate1'],
            $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['rate2'],
            $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['rate3'],
            $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['rate4'],
            $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['rate5'],
            $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['rate6'],
            $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['rate7'],
            $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['rate8']
        ];
            $item_quantity = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['quantity1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['quantity2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['quantity3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['quantity4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['quantity5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['quantity6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['quantity7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['quantity8']
            ];
        }elseif($number == 9){
            $this->validate($request, [
                'quantity9' => 'required|numeric|min:1',
                'rate9' => 'required|numeric|min:0',
                'item9' => 'required'
            ], [
                'quantity9.required' => 'Bạn cần nhập số lượng của item 9',
                'quantity9.numeric' => 'Số lượnng phải là số',
                'quantity9.min' => 'Số lượng không được nhỏ hơn 1',
                'rate9.required' => 'Bạn cần nhập tỷ lệ quay của item 9',
                'rate9.numeric' => 'Tỷ lệ quay phải là số',
                'rate9.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
                'item9.required' => 'Bạn cần nhập item 9'
            ]);
            $item = [ $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['rate1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['rate2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['rate3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['rate4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['rate5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['rate6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['rate7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['rate8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['rate9']];
            $item_quantity = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['quantity1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['quantity2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['quantity3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['quantity4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['quantity5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['quantity6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['quantity7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['quantity8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['quantity9']
            ];

        }else{
            $this->validate($request, [
                'quantity10' => 'required|numeric|min:1',
                'rate10' => 'required|numeric|min:0',
                'item10' => 'required'
            ], [
                'quantity10.required' => 'Bạn cần nhập số lượng của item 10',
                'quantity10.numeric' => 'Số lượnng phải là số',
                'quantity10.min' => 'Số lượng không được nhỏ hơn 1',
                'rate10.required' => 'Bạn cần nhập tỷ lệ quay của item 10',
                'rate10.numeric' => 'Tỷ lệ quay phải là số',
                'rate10.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
                'item10.required' => 'Bạn cần nhập item 10'
            ]);

            $item = [$dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['rate1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['rate2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['rate3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['rate4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['rate5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['rate6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['rate7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['rate8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['rate9'],
                $dulieu_tu_input['item10'].'_10' =>$dulieu_tu_input['rate10']];
            $item_quantity = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['quantity1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['quantity2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['quantity3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['quantity4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['quantity5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['quantity6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['quantity7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['quantity8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['quantity9'],
                $dulieu_tu_input['item10'].'_10' =>$dulieu_tu_input['quantity10']
            ];
        }
        if($is_use==1){
            Wheel_item::updateWheelitemuse();
        }
        Wheel_item::insertWheelitem($event,json_encode($item),$number,$image,$is_use,$dial,json_encode($item_quantity));
        Session::flash('flash_success', 'The sever added successfully.');
        return redirect('/wheel');
    }



    public function getEdit()
    {
        $id = $_GET['id'];
        $results = DB::table('wheel_item')
            ->where('id', $id)
            ->get();
        $list_item = (array)json_decode($results[0]->item);
        $list_quantity = (array)json_decode($results[0]->item_quantity);
        return view('/wheel/edit_item_wheel', compact('results','list_item','list_quantity'));
    }

    public function getAddgift(){
        $id = $_GET['id'];
        $results = DB::table('wheel_item')
            ->where('id', $id)
            ->get();
        $list_item = (array)json_decode($results[0]->item);
        $list_quantity = (array)json_decode($results[0]->item_quantity);
        return view('/wheel/add_code_item', compact('list_item','list_quantity'));
    }
    public function postAddgiftcode(Request $request){
        $id = $_GET['id'];
        $dulieu_tu_input = $request->all();
        $results = DB::table('wheel_item')
            ->where('id', $id)
            ->get();
        $list_item = (array)json_decode($results[0]->item);
        $list_quantity = (array)json_decode($results[0]->item_quantity);
        $i1 = 0;
        foreach($list_item as $key => $values) {
            $i1++;
            $giftcode = explode("\n", $dulieu_tu_input['giftcode' . $i1]);
            $quantity = intval($dulieu_tu_input['quantity'.$i1]);;
            $nameitem = explode("_", $key);

            if(count($giftcode)-1 > $quantity){
                Session::flash('flash_error', 'Giftcode number exceeds the number of the item'.$nameitem[0].' turns spinning ');
                return view('/wheel/add_code_item', compact('list_item','list_quantity'));
            }
        }
//
            $i = 0;
        $time = time();
        foreach($list_item as $key => $values){
            $i++;
            $giftcode = explode("\n", $dulieu_tu_input['giftcode'.$i]);
            $nameitem = explode("_", $key);
            foreach($giftcode as $row){
                if($row != null){
                $giftitem= Gift_code::firstOrNew(['giftcode' => $row, 'item' => $nameitem[0] , 'created_at' => date('Y-m-d H:i:s',$time),
                    'id_wheel' => $id,'is_use'=> 0]);
                $giftitem->save();}
            }
        }
        Session::flash('flash_success', 'The wheel add gift item successfully.');
        return redirect('/wheel');
    }
    public function postEdit(CheckWheelRequest $request)
    {
        $id = $_GET['id'];
        $request->flashOnly('eventname', 'imageitem','turndial','rate1','item1','quantity1','rate2','item2','quantity2'
            ,'rate3','item3','quantity3','rate4','item4','quantity4','rate5','item5','quantity5','rate6','item6','quantity6'
            ,'rate7','item7','quantity7','rate8','item8','quantity8');
        $dulieu_tu_input = $request->all();
        $number = $dulieu_tu_input['itemnumber'];
        $is_use = $dulieu_tu_input["optionsRadios"];
        $item = array();
        $item_quantity = array();
        if($number == 8){
            $item = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['rate1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['rate2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['rate3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['rate4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['rate5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['rate6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['rate7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['rate8']
            ];
            $item_quantity = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['quantity1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['quantity2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['quantity3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['quantity4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['quantity5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['quantity6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['quantity7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['quantity8']
            ];}elseif($number == 9){
            $this->validate($request, [
                'quantity9' => 'required|numeric|min:1',
                'rate9' => 'required|numeric|min:0',
                'item9' => 'required'
            ], [
                'quantity9.required' => 'Bạn cần nhập số lượng của item 9',
                'quantity9.numeric' => 'Số lượnng phải là số',
                'quantity9.min' => 'Số lượng không được nhỏ hơn 1',
                'rate9.required' => 'Bạn cần nhập tỷ lệ quay của item 9',
                'rate9.numeric' => 'Tỷ lệ quay phải là số',
                'rate9.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
                'item9.required' => 'Bạn cần nhập item 9'
            ]);
            $item = [ $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['rate1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['rate2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['rate3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['rate4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['rate5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['rate6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['rate7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['rate8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['rate9']];
            $item_quantity = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['quantity1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['quantity2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['quantity3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['quantity4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['quantity5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['quantity6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['quantity7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['quantity8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['quantity9']
            ];
        }else{
            $this->validate($request, [
                'quantity10' => 'required|numeric|min:1',
                'rate10' => 'required|numeric|min:0',
                'item10' => 'required'
            ], [
                'quantity10.required' => 'Bạn cần nhập số lượng của item 10',
                'quantity10.numeric' => 'Số lượnng phải là số',
                'quantity10.min' => 'Số lượng không được nhỏ hơn 1',
                'rate10.required' => 'Bạn cần nhập tỷ lệ quay của item 10',
                'rate10.numeric' => 'Tỷ lệ quay phải là số',
                'rate10.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
                'item10.required' => 'Bạn cần nhập item 10'
            ]);
            $item = [$dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['rate1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['rate2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['rate3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['rate4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['rate5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['rate6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['rate7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['rate8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['rate9'],
                $dulieu_tu_input['item10'].'_10' =>$dulieu_tu_input['rate10']];
            $item_quantity = [
                $dulieu_tu_input['item1'].'_1' =>$dulieu_tu_input['quantity1'],
                $dulieu_tu_input['item2'].'_2' =>$dulieu_tu_input['quantity2'],
                $dulieu_tu_input['item3'].'_3' =>$dulieu_tu_input['quantity3'],
                $dulieu_tu_input['item4'].'_4' =>$dulieu_tu_input['quantity4'],
                $dulieu_tu_input['item5'].'_5' =>$dulieu_tu_input['quantity5'],
                $dulieu_tu_input['item6'].'_6' =>$dulieu_tu_input['quantity6'],
                $dulieu_tu_input['item7'].'_7' =>$dulieu_tu_input['quantity7'],
                $dulieu_tu_input['item8'].'_8' =>$dulieu_tu_input['quantity8'],
                $dulieu_tu_input['item9'].'_9' =>$dulieu_tu_input['quantity9'],
                $dulieu_tu_input['item10'].'_10' =>$dulieu_tu_input['quantity10']
            ];
        }
        if($is_use==1){
            DB::table('wheel_item')->where('id','!=',$id)->update(
                ['is_use' => 0]);
        }
        DB::table('wheel_item')->where('id', $id)->update(
                ['event' => $dulieu_tu_input["eventname"],
                    'image_item' => $dulieu_tu_input["imageitem"],
                    'item_number' => $dulieu_tu_input["itemnumber"],
                    'is_use' => $is_use,
                    'turn_dial' => $dulieu_tu_input["turndial"],
                    'item_quantity' => json_encode($item_quantity),
                    'item' => json_encode($item)
                   ]);
            Session::flash('flash_success', 'The wheel updated successfully.');
            return redirect('/wheel');

    }

    public function store(){
        $data_ids = $_REQUEST['data_ids'];
        $data_id_array = explode(",", $data_ids);
        if(!empty($data_id_array)) {
            foreach($data_id_array as $id) {
                DB::table('wheel_item')->where('id', '=', $id)->delete();
            }
        }
    }

}
?>