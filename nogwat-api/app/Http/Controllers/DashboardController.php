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
    public function index($locale)
    {
        $lastUserDashboard = UserStat::where('user_id',auth()->user()->id)
        ->first()->last_dashboard;

        $maxDashId = Dashboard::max('id') ?? 0;

        if($lastUserDashboard == $maxDashId || $maxDashId == 0){
            return response('No new dashboard messages available', 204);
        } else {
            $messages = Dashboard::where('id','>',$lastUserDashboard)
            ->where('locale',$locale)
            ->limit(3)
            ->get();

            UserStat::where('user_id', auth()->user()->id)
            ->update(['last_dashboard'=>$maxDashId]);

            return response($messages, 200);
        }
    }
}
