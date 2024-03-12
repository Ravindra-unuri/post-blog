<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ResponseTrait;
    public function doLike($blogpost_id)
    {
        $userId = auth()->user()->id;
        $like = Like::create([
            'user_id' => $userId,
            'blogpost_id' => $blogpost_id
        ]);
        if ($like) {
            return $this->sendSuccessResponse(__('Success to like'));
        } else {
            return $this->sendNotfoundResponse(__('Unable to like'));
        }
    }

    public function allLike()
    {
        $data = Like::all();

        if ($data->isEmpty()) {
            return $this->sendNotFoundResponse(__('No likes found.'));
        } else {
            return $this->sendSuccessResponse(__('Success'), $data);
        }
    }

    public function likeDetail($id)
    {
        $data = Like::where('blogpost_id', $id)
            ->leftJoin('users as u', 'u.id', '=', 'like.user_id')
            ->select(
                'u.first_name as Liked_By',
                'like.created_at as Liked_at'
            )
            ->get();

        if ($data->isEmpty()) {
            return $this->sendNotFoundResponse(__('No likes found.'));
        } else {
            return $this->sendSuccessResponse(__('Success'), $data);
        }
    }


    public function dislike($blogpost_id)
    {
        $userId = auth()->user()->id;
        Like::where('blogpost_id', $blogpost_id && 'user_id', $userId)->delete();
    }
}
