<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Mail\NewCommentNotification;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    use ResponseTrait;

    public function doComment(Request $request, $bl_id)
    {
        $userId = auth()->user()->id;
        $comment = Comment::create([
            'user_id' => $userId,
            'blogpost_id' => $bl_id,
            'comment' => $request->input('comment')
        ]);

        if ($comment) {
            $commenterName = $comment->user->name;
            $commentDetail = $comment->comment;

            Mail::to($comment->blogpost->user->email)->send(new NewCommentNotification($commenterName, $commentDetail));

            return response()->json(['message' => 'Comment posted successfully']);
        } else {
            return response()->json(['message' => 'Failed to post comment'], 500);
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
}
