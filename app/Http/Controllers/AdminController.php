<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\User;
use PDF;


class AdminController extends Controller
{
    public $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function my_data($id){
        $users = User::find($id);
        
        return response()->json($users);
    }

    public function my_sponsor_nivel_1_cronjob($id){
        $user_1_level = User::select('id')
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();

        if($user_1_level!='[]'){
            return response()->json($user_1_level);
        }else{
            return 0;
        }
    }

    public function my_sponsor_nivel_2_cronjob($id){
        $user_2_level = User::select('id')
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();
        
        if($user_2_level!='[]'){
            return response()->json($user_2_level);
        }else{
            return 0;
        }
    }

    public function my_sponsor_nivel_1($id){
        $user_1_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();

        if($user_1_level!='[]'){
            return response()->json($user_1_level);
        }else{
            return 0;
        }
    }

    public function my_sponsor_nivel_2($id){
        $user_2_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();
        
        if($user_2_level!='[]'){
            return response()->json($user_2_level);
        }else{
            return 0;
        }
    }
    
    public function my_sponsor_nivel_3($id){
        $user_3_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();

        if($user_3_level!='[]'){
            return response()->json($user_3_level);
        }else{
            return 0;
        }        
    }

    public function my_sponsor_nivel_4($id){
        $user_4_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();

        if($user_4_level!='[]'){
            return response()->json($user_4_level);
        }else{
            return 0;
        }
    }

    public function my_sponsor_nivel_5($id){
        $user_5_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
            ->where ('id_sponsor', '=', $id)
            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
            ->orderBy('id')
            ->get();

        if($user_5_level!='[]'){
            return response()->json($user_5_level);
        }else{
            return 0;
        }
    }


    // For Dashboard - Admin    
    public function new_members_month($period){
        //$period = date("Y-m");

        $users_new = User::where([['status', '!=', 'D'],['status', '!=', 'P'],])
                ->where('affiliation_date', 'like', $period.'%')
                ->where('type_user', '=', 'S')
                ->count();

        return response()->json($users_new);
    }

    public function total_users(){

        $total_users = User::where([['status', '!=', 'D'],['status', '!=', 'P'],])
                ->where('type_user', '=', 'S')
                ->count();

        return response()->json($total_users);
    }

    public function pre_registers_users($period){
        //$period = date("Y-m");

        $pre_registers_users = User::where('status', '=', 'P')
                ->where('created_at', 'like', $period.'%')
                ->where('type_user', '=', 'S')
                ->count();

        return response()->json($pre_registers_users);
    }

    public function total_users_actives(){

        $total_users = User::where('status', '=', 'A')
                ->where('type_user', '=', 'S')
                ->count();

        return response()->json($total_users);
    }

    public function order_detail_print($id_order)
    {
        $orders_pending_detail = $this->orderService->get('/orders_pending_detail/'.$id_order);
        $orders_pending_detail_products = $this->orderService->get('/orders_pending_detail_products/'.$id_order);

        $data = array(
        'orders_pending_detail'                 => $orders_pending_detail,
        'orders_pending_detail_products'        => $orders_pending_detail_products
        );

        $pdf = PDF::loadView('PDF.orders_detail_pdf', $data);
        return $pdf->download('orders_detail_order' . $id_order. '.pdf');
    }

    


}
