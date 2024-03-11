<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ResponseTrait;
    public function doComment(Request $request)
    {
        $like = Comment::create([
            'user_id' => $request->input('user_id'),
            'blogpost_id' => $request->input('blogpost_id'),
            'comment' => $request->input('comment')
        ]);
        if ($like) {
            return $this->sendSuccessResponse(__('Success to do comment'));
        } else {
            return $this->sendNotfoundResponse(__('Unable to do comment'));
        }
    }

    public function allComment()
    {
        $data = Comment::all();

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
