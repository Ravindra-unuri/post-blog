<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    use ResponseTrait;
    public function follow($userId)
    {
        $user = auth()->user();
        if ($userId == $user->id) {
            return $this->sendConflictResponse(__('You cannot follow yourself'));
        } else {
            if (!$user->following()->where('user_id', $userId)->exists()) {
                $user->following()->attach($userId);
                return $this->sendSuccessResponse(__('Successfully followed user'));
            }

            return $this->sendConflictResponse(__('You have already followed this user'));
        }
    }

    public function unfollow($userId)
    {
        $user = auth()->user();

        if ($user->following()->where('user_id', $userId)->exists()) {
            $user->following()->detach($userId);

            return $this->sendSuccessResponse(__('Successfully unfollowed user'));
        }

        return $this->sendFailedResponse(__('User is not currently being followed'));
    }
}
