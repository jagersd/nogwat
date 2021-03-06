<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [Auth::class, 'login']);
Route::post('/register',[Auth::class,'register']);
Route::post('/forgotpassword',[Auth::class,'generatePasswordResetToken']);
Route::post('/submitresettoken',[Auth::class,'updatePasswordWithToken']);

Route::middleware('auth:sanctum')->group( function() {
    //global routes
    Route::get('/me',[Auth::class, 'me']);
    Route::get('/dashboard/{locale}', [DashboardController::class,'index']);
    Route::post('/changepassword', [Auth::class,'changePassword']);
    Route::post('/sendfeedback',[Auth::class,'sendFeedback']);

    //invite routes
    Route::post('/inviteuser',[InviteController::class, 'create']);
    Route::get('/myinvites',[InviteController::class, 'myIndex']);
    Route::post('/acceptinvite',[InviteController::class,'acceptInvite']);
    Route::post('/rejectinvite',[InviteController::class,'rejectInvite']);

    //group routes
    Route::post('/creategroup', [GroupController::class, 'create']);
    Route::get('/mygroups', [GroupController::class, 'myGroups']);
    Route::get('/groupdetails/{id}',[GroupController::class, 'groupDetails']);
    Route::get('/getgroupconfig/{id}',[GroupController::class,'getGroupConfig']);
    Route::put('updategroupconfig',[GroupController::class,'updateGroupConfig']);
    Route::post('/leavegroup',[GroupController::class,'leaveGroup']);
    Route::post('/disbandgroup',[GroupController::class,'disband']);
    Route::post('/changegroupname',[GroupController::class,'updateGroupName']);
    Route::post('/kickfromgroup',[GroupController::class,'kickFromGroup']);
    Route::post('/promoteuser',[GroupController::class,'promoteToAdmin']);

    //recipe routes
    Route::post('/createrecipe',[RecipeController::class, 'create']);
    Route::post('/amendrecipe',[RecipeController::class, 'update']);
    Route::post('/deleterecipe',[RecipeController::class, 'delete']);
    Route::get('/myrecipes',[RecipeController::class,'myIndex']);
    Route::get('/searchrecipes',[RecipeController::class,'searchIndex']);
    Route::get('/myfavorites',[RecipeController::class,'myFavorites']);

    //favo routes
    Route::post('/addfavorite',[FavoriteController::class,'create']);
    Route::post('/removefavorite',[FavoriteController::class,'delete']);

    //list routes
    Route::post('/additem',[ListController::class,'create']);
    Route::put('/updateitem',[ListController::class,'update']);
    Route::put('/purchaseditem',[ListController::class,'purchased']);
    Route::put('/reversepurchase',[ListController::class,'reversePurchase']);
    Route::get('/mylist',[ListController::class,'myList']);
    Route::get('/gethistory',[Listcontroller::class,'getHistory']);
    Route::delete('/removelistitem',[Listcontroller::class,'destroy']);
    Route::get('/addedRecipeDetails/{groupId}/{recipeId}',[Listcontroller::class,'showRecipe']);

    //star items
    Route::get('/staritems',[Listcontroller::class,'starItems']);

    //store routes
    Route::post('addstore',[StoreController::class,'create']);
    Route::post('adjuststorename',[StoreController::class,'update']);
    Route::put('deletestore',[StoreController::class,'delete']);
});
