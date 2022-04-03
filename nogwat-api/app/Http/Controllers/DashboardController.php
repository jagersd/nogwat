<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStat;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    /**
    * Checks whether the user should get a Dashboard message presented and if that is the case, the contents are returned.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $lastUserDashboard = UserStat::where('user_id',auth()->user()->id)
        ->first()->last_dashboard;

        $maxDashId = Dashboard::max('id');

        if($lastUserDashboard == 0 || $lastUserDashboard == $maxDashId){
            return response('No new dashboard messages available', 200);
        } else {
            $messages = Dashboard::where('id','>',$lastUserDashboard)
            ->limit(3)
            ->get();

            UserStat::where('user_id', auth()->user()->id)
            ->update(['last_dashboard'=>$maxDashId]);

            return response($messages, 200);
        }
    }
}
