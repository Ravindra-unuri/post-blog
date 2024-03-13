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

    public function commentDetail($id)
    {
        $data = Comment::where('blogpost_id', $id)
            ->leftJoin('users as u', 'u.id', '=', 'comment.user_id')
            ->select(
                'u.first_name as Commented_By',
                'comment.comment as comment',
                'comment.created_at as Commented_at'
            )
            ->get();

        if ($data->isEmpty()) {
            return $this->sendNotFoundResponse(__('No comments found.'));
        } else {
            return $this->sendSuccessResponse(__('Success'), $data);
        }
    }

    public function deleteComment($id)
    {
        $userId = auth()->user()->id;

        $delete_data = Comment::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($delete_data) {
            $deleted = $delete_data->delete();

            if ($deleted) {
                return $this->sendSuccessResponse(__('Comment deleted Successfully'));
            } else {
                return $this->sendFailedResponse(__('Failed to delete comment'));
            }
        } else {
            return $this->sendNotFoundResponse(__('You don\'t have the right to delete comment'));
        }
    }

    public function likeDetail()
    {
        // $data=Like::all();
    }

}
