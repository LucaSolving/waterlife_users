<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OrderService;
use App\Services\CommissionService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $orderService, $commissionService;

    public function __construct(OrderService $orderService, CommissionService $commissionService)
    {
        $this->orderService = $orderService;
        $this->commissionService = $commissionService;
    }

    public function show(){
        $partner_id = auth()->user()->id;
        $user_level = auth()->user()->level;

        //For Tracks 1 y 2
        $affiliation_date = auth()->user()->affiliation_date;
        $affiliation_date2 = strtotime(auth()->user()->affiliation_date);
        
        $data1['month_1'] = date('Y-m', strtotime($affiliation_date));
        $data2['month_2'] = date('Y-m', strtotime('+1 month', $affiliation_date2));
        $data3['month_3'] = date('Y-m', strtotime('+2 month', $affiliation_date2));
        $data4['month_4'] = date('Y-m', strtotime('+3 month', $affiliation_date2));
        
        $data2['month_2'] = date('Y-m-d', strtotime('+1 month', $affiliation_date2));

        $months_1 = $this->commissionService->post('/commissions_users_track_1mes/'.$partner_id,$data1);
        $months_2 = $this->commissionService->post('/commissions_users_track_2mes/'.$partner_id,$data2);
        $months_3 = $this->commissionService->post('/commissions_users_track_3mes/'.$partner_id,$data3);
        $months_4 = $this->commissionService->post('/commissions_users_track_4mes/'.$partner_id,$data4);
        
        //return $data2['month_2'];
        $date_now_affiliation = date('Y-m-d', strtotime($affiliation_date));
        $date_last_1er_month = date("Y-m-t", strtotime($date_now_affiliation));
        $date_last_2do_month = date("Y-m-t", strtotime($data2['month_2']));
        $date_last_3ro_month = date("Y-m-t", strtotime($data3['month_3']));
        $date_now = date("Y-m-d");

        if(strtotime($date_now) > strtotime($date_last_1er_month)){
            $month_1_limit = 1;
        }else{
            $month_1_limit = 0;
        }

        if(strtotime($date_now) > strtotime($date_last_2do_month)){
            $month_2_limit = 1;
        }else{
            $month_2_limit = 0;
        }

        if(strtotime($date_now) > strtotime($date_last_3ro_month)){
            $month_3_limit = 1;
        }else{
            $month_3_limit = 0;
        }
        
        if( ($months_1['amp']==0) && ($month_1_limit==1) ){
        //if($months_1['amp']==0){
            $track_full = 'inactive';
            $track_1 = '';
            $track_2 = '';
        }else{
            if($month_1_limit==1){
                if($months_1['amp']>=300){
                    $track_1 = 'active';
                    $track_2 = 'inactive';               
                    $track_full = '';
                }else{
                    $track_1 = 'inactive';
                    $track_2 = 'active';                
                    $track_full = '';
                }
            }else{
                $track_1 = 'active';
                $track_2 = 'inactive';               
                $track_full = '';
            }
            
    
            if($track_1=='active'){
                if(($months_2['amp']<600) && ($month_2_limit==1)){
                    $track_full = 'inactive';
                }
            }else{
                //if($months_2['amp']<200){
                if(($months_2['amp']<300) && ($month_2_limit==1)){
                    $track_full = 'inactive';
                }
                if(($months_3['amp']<600) && ($month_3_limit==1)){
                    $track_full = 'inactive';
                }
            }            
        }
        //return $track_full;
        //return $months_1;
        
        //End - For Tracks 1 y 2

        //return $months_4;
        
        $commission_data = $this->commissionService->get('/commissions_users/'.$partner_id);
        
        if($commission_data!=0){
            foreach ($commission_data as $item) {
                $commission['amp'] = $item["amp"];
                if($item["personal_discount"]==0){
                    $commission['personal_discount'] = $item["personal_discount_history"];
                }else{
                    $commission['personal_discount'] = $item["personal_discount"];
                }
            }        
        }else{
            $commission['amp'] = 0;
            $commission['personal_discount'] = 0;
        }

        //1er Semestre
        $commission_1S = $this->commissionService->get('/commissions_users_1S/'.$partner_id);

        if($commission_1S==''){
            $commission_1S = 0;       
        }

        //2do Semestre
        $commission_2S = $this->commissionService->get('/commissions_users_2S/'.$partner_id);

        if($commission_2S==''){
            $commission_1S = 0;
        }


        //Nro Consultores directos
        $user_1_level = User::where ('id_sponsor', '=', $partner_id)
                            //->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                            ->where('status', '=', 'A')
                            ->orderBy('id')
                            ->get();

        //return $user_1_level;

        //Count
        $count_level1 = 0;
        foreach ($user_1_level as $it1) {
            $count_level1++;
        }

        $user_1_level = User::select('id')
                            ->where ('id_sponsor', '=', $partner_id)
                            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                            ->orderBy('id')
                            ->get();

        
        
        if($user_1_level!='[]'){

            foreach ($user_1_level as $item) {
                $id_1_level = $item["id"];
                $users_1_level_ids[] = $item["id"];

                $user_2_level = User::select('id')
                                        ->where ('id_sponsor', '=', $id_1_level)
                                        ->where([['status', '!=', 'D'],['status', '!=', 'P'],])->get();
                
                
                if($user_2_level!='[]'){                
                    foreach ($user_2_level as $it2) {
                        $users_2_level_ids[] = $it2["id"];
                    }
                }else{                
                    $users_2_level_ids[] = '[]';
                }
            }
            
            if($users_2_level_ids!='[]'){
                
                
                $users_1_level_ids['users_1_level_ids'] = implode(",",$users_1_level_ids);
            
                $simulation_commissions = $this->commissionService->post('/simulation_commissions',$users_1_level_ids);
                
                $simulation_commissions_1_1S = $this->commissionService->post('/simulation_commissions_1_1S',$users_1_level_ids);
                $simulation_commissions_1_2S = $this->commissionService->post('/simulation_commissions_1_2S',$users_1_level_ids);


                
                $users_2_level_ids['users_2_level_ids'] = implode(",",$users_2_level_ids);

                $simulation_commissions_2 = $this->commissionService->post('/simulation_commissions_2',$users_2_level_ids);
                $simulation_commissions_2_1S = $this->commissionService->post('/simulation_commissions_2_1S',$users_2_level_ids);
                $simulation_commissions_2_2S = $this->commissionService->post('/simulation_commissions_2_2S',$users_2_level_ids);

                $group_volume = $commission['amp']+($simulation_commissions + $simulation_commissions_2);
                $group_volume_1S = $simulation_commissions_1_1S + $simulation_commissions_2_1S;                
                $group_volume_2S = $simulation_commissions_1_2S + $simulation_commissions_2_2S;
            }else{
                $group_volume = 0;
                $group_volume_1S = 0;
                $group_volume_2S = 0;
            }

        }else{

            $group_volume = $commission['amp'];
            $group_volume_1S = 0;
            $group_volume_2S = 0;

        }
        

        return view('home.dashboard', compact('user_level','commission','commission_1S','commission_2S','count_level1','group_volume','group_volume_1S','group_volume_2S','months_1','months_2','months_3','months_4','track_1','track_2','track_full'));
        
    }

}
