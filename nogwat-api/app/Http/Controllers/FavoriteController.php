<?php

namespace App\Http\Controllers;

use App\Models\Favorite;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
    * Marks Recipe as favorite
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {   
        try{
            Favorite::create([
                'recipe_id' => $request->recipeId,
                'user_id' => auth()->user()->id
            ]);

            $success = true;
            $message = 'succesfully added to favorites';
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
    * Deletes a recipe initially marked as favorite
    * 
    * @return \Illuminate\Http\Reponse
    */
    public function delete(Request $request)
    {
        try {
            Favorite::where('user_id',auth()->user()->id)
            ->where('recipe_id',$request->recipeId)
            ->delete();

            $success = true;
            $message = 'successfully removed from favorites';

        } catch (Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response($response, 200);
    }
}
