<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\GroupInvite;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Mail\SendDevMessage;
use App\Mail\SendPasswordResetToken;
use Illuminate\Support\Facades\Mail;

class Auth extends Controller
{
    /**
    * Register
    */
    public function register(Request $request)
    {   
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);

        $existCheck = User::where('email',$request->email)->first();
        if($existCheck !== null){
            return response()->json('user already registered', 409);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $openInvites = GroupInvite::whereNotNull('unregistered_email')->get();

            foreach($openInvites as $openInvite){
                if(Crypt::decryptString($openInvite->unregistered_email) == $user->email){
                    GroupInvite::where('id',$openInvite->id)
                    ->update([
                        'invited_user_id' => User::where('email',$user->email)->first()->id
                    ]);
                }
            }

            $success = true;
            $message = 'User register successfully';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response,201);
    }


    /**
     * User API Login Controller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        foreach ($user->tokens as $token) {
            $token->delete();
        }

        $token = $user->createToken('nogwat-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response, 201);
    }

    /**
     * User API change passwordController
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'newPassword' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'These credentials do not match our records.'
            ], 404);
        } else {
            $user->password = Hash::make($request->newPassword);
            $user->save();

            return response([
                'message' => 'Password Changed'
            ], 200);
        }

        return response($response, 201);
    }

    /**
    * Displays user info and all related db items.
    *
    * @return \Illuminate\Http\Response
    */
    public function me()
    {
        //$user = $request->user();
        $user = User::where('id', auth()->user()->id)
        ->with('groups')
        ->with('recipes')
        ->first();

        $response = $user;

        return response($response, 200);
    }

    /**
    * Triggers an email for feedback
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * 
    */
    public function sendFeedback(Request $request){

        $userMail = $request->user()->email;
        $type = $request->type;
        $content = $request->content;

        Mail::to(env('FEEDBACK_MAIL'))->send(new SendDevMessage($userMail,$type,$content));

        $response = 'mail sent';

        return response($response,200);
    }

    /**
    * Generate and email password request token
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * 
    */
    public function generatePasswordResetToken(Request $request){

        $user = User::where('email',$request->email)->first();
        if($user == null){
            return response()->json('impossible request', 403);
        }

        $resetToken = mt_rand(10000,99999);
        PasswordReset::updateOrInsert(
            ['email'=>$request->email],
            [
                'token'=>Crypt::encrypt($resetToken),
                'created_at'=>\Carbon\Carbon::now()->addMinutes(10)->toDateTimeString()
            ]
        );

        Mail::to($user->email)->send(new SendPasswordResetToken($resetToken));

        $response = 'resetToken sent';

        return response($response,200);
    }

    /**
    * Allow user to submit new password based on token match
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * 
    */
    public function updatePasswordWithToken(Request $request){

        $request->validate([
            'email'=>'required|email',
            'newPassword' => 'required',
            'token' => 'required'
        ]);

        $resetAction = PasswordReset::where('email',$request->email)->first();

        if(Crypt::decrypt($resetAction->token) !== intval($request->token)){
            return response('tokens do not match',403);
        }

        if($resetAction->created_at < \Carbon\Carbon::now()->toDateTimeString()){
            return response('token is no longer valid', 403);
        }

        $user = User::where('email',$resetAction->email)->first();

        if($user !== null){
            $user->password = Hash::make($request->newPassword);
            $user->save();

            PasswordReset::where('email',$request->email)->delete();
    
            return response([
                'message' => 'Password Changed'
            ], 201);
        } else {
            return response([
                'message' => 'Something went wrong'
            ], 500);
        }

    }

}
