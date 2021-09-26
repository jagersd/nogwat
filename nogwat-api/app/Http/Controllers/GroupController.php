<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupConfig;
use App\Models\UserGroup;
use App\Models\User;

class GroupController extends Controller
{
    /**
     * Display the list of groups where the user belongs to
     *
     * @return \Illuminate\Http\Response
     */
    public function myGroups()
    {
        $response = auth()->user()->groups;

        foreach ($response as $r){
            $r->adminCheck = UserGroup::where('group_id',$r->id)
            ->where('user_id',auth()->user()->id)
            ->where('deleted',0)
            ->select('is_admin')
            ->first();
        }

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
        ->pluck('id')
        ->toArray();

        if(!in_array($id,$applicableGroups)){
            return response('unauthorized', 401);
        }

        $response = Group::where('id',$id)
        ->with('users')
        ->first();

        $response->adminCheck = UserGroup::where('user_id', auth()->user()->id)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
