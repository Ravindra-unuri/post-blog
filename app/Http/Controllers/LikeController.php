<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ResponseTrait;
    public function doLike(Request $request)
    {
        $like = Like::create([
            'user_id' => $request->input('user_id'),
            'blogpost_id' => $request->input('blogpost_id')
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

    public function likeDetail()
    {
        // $data=Like::all();
    }

    public function deleteLike()
    {
        //
    }
}
