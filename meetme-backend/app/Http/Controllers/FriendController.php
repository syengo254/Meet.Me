<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendSuggestionResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function suggest(int $id){
        /* 
        we check city and suggest people in city
        if no city, we suggest random users
        *
        also check if user is already a friend or has sent a freind request
        */

        $alreadies = [$id];
        $profileColl = UserProfile::select('user_id','friend_requests', 'friends')->where('user_id', '!=', $id)->get();

        foreach($profileColl as $profile){
            if(count($profile->friends["users"]) > 0){
                foreach($profile->friends["users"] as $user){
                    if($user['id'] == $id){
                        array_push($alreadies, $profile->user_id);
                        break;
                    }
                }
            }
    
            if(count($profile->friend_requests["users"]) > 0){
                foreach($profile->friend_requests["users"] as $user){
                    if($user['id'] == $id){
                        array_push($alreadies, $profile->user_id);
                        break;
                    }
                }
            }
        }

        return [
            "success" => 1, 
            "suggestions" => FriendSuggestionResource::collection(
                User::whereNotIn('id', $alreadies)->with('user_profile:id,user_id,avatar')->get()
            )
        ];
    }

    public function addRequest(User $user){
        $requester = User::with('user_profile')->where('id', Auth::user()->id)->first();
        $user_profile = UserProfile::find($user->id);

        $current_requests = $user_profile->friend_requests;
        array_unshift($current_requests["users"], $requester);
        $user_profile->friend_requests = $current_requests;

        return ["success" => $user_profile->save()];
    }
}
