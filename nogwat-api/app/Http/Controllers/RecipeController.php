<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Measurement;
use App\Models\Favorite;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchIndex(Request $request)
    {
        $mealTypes = $request->mealTypes ?: null;
        $searchString = $request->searchString ?: null;

        $response = Recipe::with('recipeItems')
        ->with('user:id,name')
        ->where('private',false)
        ->where('deleted',0)
        ->withCount('favorited')
        ->when($mealTypes !== null, function($query) use ($mealTypes){
            $query->whereIn('meal_type',explode(",",$mealTypes));
        })
        ->when($searchString !== null , function($query) use ($searchString){
            $query->where('name','like', '%' . $searchString . '%')
            ->where('description','like', '%'. $searchString . '%');
        })
        ->orderBy('id','DESC')
        ->limit(20)
        ->get();

        $favoCheck = Favorite::where('user_id', auth()->user()->id)
        ->pluck('recipe_id')->toArray();

        foreach($response as $r){
            if (in_array($r->id, $favoCheck)){
                $r->favorited = "true";
            } else {
                $r->favorited = "false";
            }

            unset($r->user->id);
        }

        return response($response, 200);
    }

    /**
     * Create a recipe
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $recipe = Recipe::create([
                'name' => $request->name,
                'description' => $request->description,
                'meal_type' => $request->mealType,
                'user_id_created' => Auth::id(),
                'instructions' => $request->instructions,
                'person_amount' => $request->personAmount,
                'updatecounter' => 0,
                'lang' => $request->lang,
                'private' => $request->private,
            ]);

            foreach ($request->ingredients as $recipeItem){

                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'item_name' => $recipeItem['name'],
                    'measurement_type_id' => $this->getMeasurementTypeId($recipeItem['amountType']),
                    'measurement_amount' => $recipeItem['amount']
                ]);
            }

            $success = true;
            $message = 'Recipe created';

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
     * Update a recipe
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $recipeId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $recipe = Recipe::where('id',$request->recipeId)->first();

        if($recipe->user_id_created != auth()->user()->id){
            return response('only the original creater is allowed to update the recipe');
        }

        try {
            $recipe->update([
                'name' => $request->name,
                'description' => $request->description,
                'meal_type' => $request->mealType,
                'instructions' => $request->instructions,
                'person_amount' => $request->personAmount,
                'updatecounter' => $recipe->updatecounter + 1
            ]);

            RecipeItem::where('recipe_id', $recipe->id)->delete();

            foreach ($request->ingredients as $recipeItem){

                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'item_name' => $recipeItem['name'],
                    'measurement_type_id' => $this->getMeasurementTypeId($recipeItem['amountType']),
                    'measurement_amount' => $recipeItem['amount']
                ]);
            }

            $success = true;
            $message = 'Recipe updated';

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

    /*
    *
    * Get the measurement type id based on the abbreviated version
    *
    */
    private function getMeasurementTypeId($abbreviation):int
    {
        return Measurement::where('abbreviation', $abbreviation)->first('id')->id;
    }

    /**
     * Delete a recipe
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $recipeId
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $recipe = Recipe::where('id',$request->recipeId)->first();

        if($recipe->user_id_created != $request->user()->id){
            return response('only the original creater is allowed to update the recipe', 401);
        }else{
            $recipe->deleted = 1;
            $recipe->save();
            return response('recipe deleted', 200);
        }
    }

    /**
     * Display the Users recipes
     *
     * @return \Illuminate\Http\Response
     */
    public function myIndex()
    {
        $response = Recipe::where('user_id_created', auth()->user()->id)
        ->with('recipeItems')
        ->with('user:id,name')
        ->where('deleted',0)
        ->get();

        foreach($response as $r){
            $favoCheck = Favorite::where('recipe_id', $r->id)
            ->where('user_id', auth()->user()->id)
            ->first();

            $r->favorited = ($favoCheck !==null) ? "true" : "false";
        }

        return response($response, 200);
    }

     /**
     * Display the Users favorited recipes
     *
     * @return \Illuminate\Http\Response
     */
    public function myFavorites()
    {
        $userFavos = Favorite::where('user_id', auth()->user()->id)
        ->pluck('recipe_id')->toArray();

        $response = Recipe::whereIn('id', $userFavos)
        ->with('recipeItems')
        ->with('user:id,name')
        ->where('deleted', 0)
        ->get();

        foreach($response as $r){
            $r->favorited = "true";
        }

        return response($response, 200);
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
