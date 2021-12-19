<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\GroupInvite;
use App\Models\User;
use App\Mail\SendInvite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class InviteController extends Controller
{   
    /**
    * Creates a group invite
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {   
        if ($this->isGroupAdmin($request->user()->id, $request->groupId) == false){
            return 'you are not the group Admin';
        }

        if(User::where('email',$request->invitee)->exists()){
            $inviteeId = User::where('email',$request->invitee)->first('id')->id;

            $inviteeGroups = UserGroup::where('user_id',$inviteeId)
            ->select('group_id')
            ->get()
            ->toArray();

            if(in_array($request->group_id,$inviteeGroups)){
                return 'The invitee is already in the group';
            }

            try{
                $invite = GroupInvite::updateOrCreate(
                    ['invited_user_id'=>$inviteeId, 'group_id'=>$request->groupId],
                    [
                    'invited_user_id' => $inviteeId,
                    'invitor_user_id' => $request->user()->id,
                    'group_id'=>$request->groupId,
                    'status'=>'pending']
                );

                Mail::to($request->invitee)->send(new SendInvite($request->user()));
                
                $success = true;
                $message = 'Invite Sent';
    
    
            } catch (\Illuminate\Database\QueryException $ex) {
                $success = false;
                $message = $ex->getMessage();
            }
        } else {
            try{
                $invite = GroupInvite::create([
                    'unregistered_email' => Crypt::encryptString($request->invitee),
                    'invitor_user_id' => $request->user()->id,
                    'group_id'=>$request->groupId,
                ]);

                Mail::to($request->invitee)->send(new SendInvite($request->user()));
    
                $success = true;
                $message = 'Invite Sent';
    
    
            } catch (\Illuminate\Database\QueryException $ex) {
                $success = false;
                $message = $ex->getMessage();
            }
        }
        



        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response($response, 201);
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


    /**
    * Get request which generates an overview of invites for logged in user
    */
    public function myIndex()
    {
        $response = GroupInvite::where('invited_user_id', auth()->user()->id)
        ->select('id','group_id', 'status','created_at','updated_at')
        ->get();

        return response($response, 200);
    }

    /**
    * Allows the user to Accept an invite
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function acceptInvite(Request $request)
    {   
        $invitation = GroupInvite::where('id', $request->inviteId)->first();
        $invitation->status = 'accepted';
        $invitation->save();

        UserGroup::create([
            'user_id' => $request->user()->id,
            'group_id' => $invitation->group_id
        ]);

        $this->removeDuplicates($invitation);

        return response('success', 200);
    }

    

    /**
    * Allows the user to Reject an invite
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function rejectInvite(Request $request)
    {   
        $invitation = GroupInvite::where('id', $request->inviteId)->first();
        $invitation->status = 'rejected';
        $invitation->save();

        $this->removeDuplicates($invitation);

        return response('success', 200);
    }

    private function removeDuplicates($invitation)
    {
        $dupInvites = GroupInvite::where('id','!=',$invitation->id)
        ->where('invited_user_id',$invitation->invited_user_id)
        ->where('group_id',$invitation->group_id)
        ->where('status','pending')
        ->get();

        if($dupInvites != null){
            foreach($dupInvites as $dupInvite){
                $dupInvite->delete();
            }
        }
    }

    /**
    * Allows admin to retract an open invite
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

}
