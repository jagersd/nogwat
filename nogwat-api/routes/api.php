<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [Auth::class, 'login']);
Route::post('/register',[Auth::class,'register']);


Route::middleware('auth:sanctum')->group( function() {
    //global routes
    Route::get('/me',[Auth::class, 'me']);

    //invite routes
    Route::post('/inviteuser',[InviteController::class, 'create']);
    Route::get('/myinvites',[InviteController::class, 'myIndex']);
    Route::post('/acceptinvite',[InviteController::class,'acceptInvite']);
    Route::post('/rejectinvite',[InviteController::class,'rejectInvite']);

    //group routes
    Route::post('/creategroup', [GroupController::class, 'create']);
    Route::get('/mygroups', [GroupController::class, 'myGroups']);
    Route::get('/getgroupconfig/{id}',[GroupController::class,'getGroupConfig']);
    Route::put('updategroupconfig',[GroupController::class,'updateGroupConfig']);

    //recipe routes
    Route::post('/createrecipe',[RecipeController::class, 'create']);
    Route::put('/updaterecipe',[RecipeController::class, 'update']);
    Route::post('/deleterecipe',[RecipeController::class, 'delete']);
    Route::get('/myrecipes',[RecipeController::class,'myIndex']);

    //list routes
    Route::post('/additem',[ListController::class,'create']);
    Route::put('/updateitem',[ListController::class,'update']);
    Route::put('/purchaseditem',[ListController::class,'purchased']);
    Route::get('/mylist',[ListController::class,'myList']);

    //store routes
    Route::post('addstore',[StoreController::class,'create']);
    Route::put('updatestore',[StoreController::class,'update']);
    Route::put('deletestore',[StoreController::class,'delete']);
});
