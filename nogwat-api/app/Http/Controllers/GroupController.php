<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupConfig;
use App\Models\UserGroup;
use App\Models\User;
use App\Models\GroupInvite;
use Illuminate\Support\Facades\Crypt;

class GroupController extends Controller
{
    /**
     * Display the list of groups where the user belongs to
     *
     * @return \Illuminate\Http\Response
     */
    public function myGroups()
    {
        $response['groups'] = auth()->user()->groups;

        foreach ($response['groups'] as $r){
            $r->adminCheck = UserGroup::where('group_id',$r->id)
            ->where('user_id',auth()->user()->id)
            ->select('is_admin')
            ->first();
        }

        $response['invites'] = GroupInvite::where('invited_user_id', auth()->user()->id)
        ->where('status','pending')
        ->with('group')
        ->with('invitor')
        ->get();

        return response($response, 200);
    }

    /**
     * Get all info about specific group
     *
     * @return \Illuminate\Http\Response
     */
    public function groupDetails($id)
    {
        $applicableGroups = UserGroup::where('user_id', auth()->user()->id)
        ->pluck('group_id')
        ->toArray();

        if(!in_array($id,$applicableGroups)){
            return response('unauthorized', 401);
        }

        $response = Group::where('id',$id)
        ->with('stores')
        ->with('users')
        ->with('openInvitesRegistered')
        ->with('openInvitesUnregistered', function ($query) {
            $query->select('unregistered_email','group_id');
        })
        ->first();

        
        if(!empty($response->openInvitesUnregistered)){
            foreach($response->openInvitesUnregistered as $inviteArray){
                $inviteArray->unregistered_email = Crypt::decryptString($inviteArray->unregistered_email);
            }
        }
        
        $response->adminCheck = UserGroup::where('user_id', auth()->user()->id)
        ->where('group_id', $id)
        ->select('is_admin')
        ->first();

        return response($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $group = new Group();
            $group->name = $request->name;
            $group->admin_instructions = $request->admin_instructions;
            $group->save();

            $userGroup = new UserGroup();
            $userGroup->group_id = $group->id;
            $userGroup->user_id = $request->user()->id;
            $userGroup->is_admin = 1;
            $userGroup->save();

            $groupConfig = new GroupConfig();
            $groupConfig->group_id= $group->id;
            $groupConfig->save();

            $success = true;
            $message = 'Group created';
        }   catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }

    /**
    * Display the config settings for a specific group
    *
    * @return \Illuminate\Http\Response
    */
    public function getGroupConfig($id)
    {   
        $applicableGroups = UserGroup::where('user_id', auth()->user()->id)
        ->pluck('id')
        ->toArray();

        if(!in_array($id,$applicableGroups)){
            return response('unauthorized', 401);
        }

        $group = Group::where('id',$id)->first();
        $response = $group->groupConfig;

        return response($response, 200);
    } 


    /**
     * Edit config settings for a specific group
     *
     * @param  int  $request->configId is the config id
     * @return \Illuminate\Http\Response
     */
    public function updateGroupConfig(Request $request)
    {
        try{
            GroupConfig::where('id',$request->configId)
            ->update([
                'use_store' => $request->useStore,
                'use_store_type' => $request->useStoreType,
                'use_planning' => $request->usePlanning
            ]);

            $success = true;
            $message = 'Config updated';

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }

    /**
     * Allows the user to leave a specific group
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function leaveGroup(Request $request)
    {
        $userGroupCombo = UserGroup::where('user_id',auth()->user()->id)
        ->where('group_id',$request->groupId)
        ->first();

        if($userGroupCombo->is_admin ==1){
            return response('you are the group admin',406);
        } else {
            try{
                $userGroupCombo->delete();

                $success = true;
                $message = 'group left';
            } catch (\Illuminate\Database\QueryException $ex){
                $success = false;
                $message = $ex->getMessage();
            }
            
            $response = [
                'succes' => $success,
                'message' => $message,
            ];
            
            return response()->json($response);
        }
    }

    /**
     * Allows group admins to disband the complete group
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disband(Request $request)
    {
        $userGroupCombo = UserGroup::where('user_id',auth()->user()->id)
        ->where('group_id',$request->groupId)
        ->first();

        if($userGroupCombo->is_admin ==1){
            try{
                Group::where('id',$userGroupCombo->group_id)
                ->update([
                    'deleted'=>1
                ]);
                UserGroup::where('group_id',$userGroupCombo->group_id)
                ->delete();

                $success= true;
                $message = 'group removed';
            } catch (\Illuminate\Database\QueryException $ex){
                $success = false;
                $message = $ex->getMessage();
            }

            $response = [
                'succes' => $success,
                'message' => $message
            ];

            return response()->json($response);
        } else {
            return response('action not allowd', 406);
        }
    }
}
