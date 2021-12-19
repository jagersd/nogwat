<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\UserGroup;

class StoreController extends Controller
{
    /**
    * Create a store
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        if ($this->isGroupAdmin($request->user()->id, $request->groupId) == false){
            return 'you are not the group Admin';
        }

        try{
            $store = Store::create([
                'name' => $request->name,
                'group_id' => $request->groupId,
                'description' => $request->description,
            ]);

            $success = true;
            $message = 'Store created';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response($response, 201);
    }

    /**
    * Update store details
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {   
        if ($this->isGroupAdmin($request->user()->id, $request->groupId) == false){
            return 'you are not the group Admin';
        }

        try{
            $store = Store::where('id', $request->storeId)
            ->where('group_id',$request->groupId)
            ->first();

            $store->name = !empty($request->name) ? $request->name : $store->name;
            $store->description = !empty($request->description) ? $request->description : $store->description;
            $store->save();

            $success = true;
            $message = 'Store updated';

        } catch (\Illuminate\Database\QueryException $ex){
            $success = false;
            $message = 'Store update failed';
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response($response, 200);
    }

    /**
    * Delete store
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function delete(Request $request)
    {
        if ($this->isGroupAdmin($request->user()->id, $request->groupId) == false){
            return 'you are not the group Admin';
        }

        try{
            Store::where('id', $request->storeId)
            ->update([
                'deleted' => 1,
            ]);

            $success = true;
            $message = 'Store deleted';

        } catch(\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = 'Store update failed';
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response($response, 200);
    }


    /**
    * Checks whether the user is group admin
    */
    private function isGroupAdmin($user, $group)
    {
        $adminCheck = UserGroup::where('user_id',$user)
        ->where('group_id',$group)
        ->first('is_admin')->is_admin;

        return ($adminCheck == 1) ? true : false;
    }

}
