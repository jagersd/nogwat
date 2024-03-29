<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Store;
use App\Models\ActiveList;
use App\Models\UserGroup;
use App\Models\Group;
use App\Models\Measurement;
use App\Models\Recipe;

class ListController extends Controller
{
    /**
    * Addlistitem
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        if($this->isInGroup($request->user()->id, $request->listItems[0]['groupId']) == false){
            return response('You are not allowed to add items for this group', 401);
        }

        try {
            foreach($request->listItems as $listItem){
                ActiveList::create([
                    'group_id' => $listItem['groupId'],
                    'user_id_added' => $request->user()->id,
                    'store_id' => $listItem['storeId'] ?? null,
                    'item_name' => $listItem['itemName'],
                    'measurement_type_id' =>$listItem['measurementType'] ? Measurement::where('abbreviation',$listItem['measurementType'])->first('id')->id : null,
                    'measurement_amount' => $listItem['amount'] ?? null,
                    'date_added' => Carbon::now(),
                    'due_date' => isset($listItem['dueDate']) ? Carbon::parse($listItem['dueDate'])->format('y-m-d') : null,
                    'recipe_id' => $listItem['recipeId'] ?? null,
                ]);

                $success = true;
                $message = 'item successfully added';
            }

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

        if($request->amount != null && $request->measurementType == null){
            $request->measurementType = "st";
        }

        $listItem->update([
            'store_id' => $request->storeId,
            'item_name' => $request->itemName,
            'due_date' => $request->dueDate ? Carbon::parse($request->dueDate)->format('y-m-d') : null,
            'measurement_type_id' => $request->measurementType ? Measurement::where('abbreviation',$request->measurementType)->first('id')->id : null,
            'measurement_amount' => $request->amount,
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

        if(empty($groups)){
            return response('no-groups', 204);
        }

        foreach($groups as $group){
            $response[] = Group::where('id',$group)
            ->with('stores')
            ->with('activeLists')
            ->first();
        }

        return response($response, 200);
    }

    /**
    * Displays the users shopping Lists
    *
    * @return \Illuminate\Http\Response
    */
    public function starItems()
    {
        $response = UserGroup::where('user_id', auth()->user()->id)
        ->join('groups','user_groups.group_id','=','groups.id')
        ->get(['user_groups.group_id','groups.name']);

        foreach($response as $r){
            $r->stores = Store::where('group_id',$r->group_id)
            ->where('deleted',0)
            ->get(['id','name']);

            $r->items = DB::table('active_lists')
            ->select('item_name', DB::raw('count(*) as name_counter'))
            ->where('group_id',$r->group_id)
            ->where('user_id_added',auth()->user()->id)
            ->groupBy('active_lists.item_name','group_id')
            ->orderBy('name_counter', 'DESC')
            ->take(10)
            ->get();
        }

        return response($response, 200);
    }

    /**
    * Displays the user or group purchase history
    *
    * @return \Illuminate\Http\Response
    */
    public function getHistory(Request $request)
    {
        $groupId = $request->groupId ?: null;

        if($groupId !== null){
            if($this->isInGroup($request->user()->id, $groupId) == false){
                return response('You are not allowed to add items for this group', 401);
            }

            $response = ActiveList::where('group_id',$groupId)
            ->where('date_purchased','!=', null)
            ->where('date_purchased','>=',Carbon::now()->subMonths(6)->toDateTimeString())
            ->with('measurement')
            ->with('addedUser:id,name')
            ->with('purchasedUser:id,name')
            ->orderBy('date_purchased', 'desc')
            ->get();

            return response($response, 200);
        }
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

    /**
    * User records that the item is purchased
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function reversePurchase(Request $request)
    {
        $listItem = ActiveList::where('id',$request->listItemId)->first();

        $listItem->update([
            'date_purchased' => null,
            'user_id_purchased' => null
        ]);

        return response('record updated',200);
    }

    /**
    * Remove listItem resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $listItem = ActiveList::where('id',$id)->first();
        if($listItem->user_id_added == auth()->user()->id){
            $listItem->delete();
            return response('record deleted', 200);
        } else {
            return response('operation failed', 500);
        }
    }

    /**
    * Show recipe instructions for active listitem
    * @param  int  $groupId
    * @param  int  $recipeId
    * @return \Illuminate\Http\Response
    */
    public function showRecipe($groupId, $recipeId)
    {
        $baseItem = ActiveList::where('group_id',$groupId)->where('recipe_id',$recipeId)->orderBy('created_at', 'desc')->first();

        $recipe = Recipe::select('name', 'instructions')->where('id',$baseItem->recipe_id)->first();

        $activeItems = ActiveList::where('group_id',$groupId)
        ->where('recipe_id',$recipeId)
        ->where('created_at',$baseItem->created_at)
        ->get();

        $response = ['recipe'=>$recipe,'items'=>$activeItems];

        return response($response, 200);
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
