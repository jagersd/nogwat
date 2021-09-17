<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ActiveList;
use App\Models\UserGroup;
use App\Models\Measurement;

class ListController extends Controller
{
    /**
    * Addlistitem
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {   
        if($this->isInGroup($request->user()->id, $request->groupId) == false){
            return response('You are not allowed to add items for this group', 401);
        }

        try {
            $list = ActiveList::create([
                'group_id' => $request->groupId,
                'user_id_added' => $request->user()->id,
                'store_id' => $request->storeId,
                'item_name' => $request->itemName,
                'measurement_type_id' => Measurement::where('abbreviation',$request->measurementType)->first()->id,
                'measurement_amount' => $request->amount,
                'date_added' => Carbon::now(),
                'due_date' => $request->dueDate,
            ]);

            $success = true;
            $message = 'item successfully added';

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response($response,201);
    }

    /**
    * Allows the user to update a list item
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {
        $listItem = ActiveList::where('id',$request->listItemId)->first();
        
        $listItem->update([
            'store_id' => $request->storeId,
            'item_name' => $request->itemName,
            'due_date' => $request->dueDate,
        ]);

        return response('record updated',200);
    }

    /**
    * Displays the users shopping Lists
    *
    * @return \Illuminate\Http\Response
    */
    public function myList()
    {   
        $groups = UserGroup::where('user_id', auth()->user()->id)
        ->get('group_id')
        ->toArray();
        
        $response = ActiveList::whereIn('group_id',$groups)->get();
        
        return response($response, 200);
    }


    /**
    * User records that the item is purchased
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function purchased(Request $request)
    {
        $listItem = ActiveList::where('id',$request->listItemId)->first();
        
        $listItem->update([
            'date_purchased' => Carbon::now(),
            'user_id_purchased' => $request->user()->id
        ]);

        return response('record updated',200);
    }


    /*
    *
    * Check whether the user is allowd to alter the list for the group
    *
    */
    private function isInGroup($user, $group)
    {
        $checker = UserGroup::where('user_id',$user)
        ->where('group_id',$group)
        ->first();

        return ($checker) ? true : false;
    }

}
