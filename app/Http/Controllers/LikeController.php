<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ResponseTrait;

    public function doLike($blogpostId)
    {
        $userId = auth()->user()->id;

        try {
            DB::beginTransaction();

            $existingLike = Like::where('user_id', $userId)
                ->where('blogpost_id', $blogpostId)
                ->exists();

            if ($existingLike) {
                DB::rollBack();
                return $this->sendConflictResponse(__('Already liked this post.'));
            }

            Like::create([
                'user_id' => $userId,
                'blogpost_id' => $blogpostId
            ]);

            DB::commit();
            return $this->sendSuccessResponse(__('Success to like.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendFailedResponse(__('Unable to like.'));
        }
    }

    public function allLike()
    {
        try {
            $likes = Like::all();

            if ($likes->isEmpty()) {
                return $this->sendNotFoundResponse(__('No likes found.'));
            } else {
                return $this->sendSuccessResponse(__('Success'), $likes);
            }
        } catch (\Exception $e) {
            return $this->sendFailedResponse(__('Failed to fetch likes.'));
        }
    }

    public function likeDetail(Like $like, $blogpostId)
    {
        try {
            $likes = $like->where('blogpost_id', $blogpostId)
                ->with('user:id,first_name')
                ->get(['user_id', 'blogpost_id', 'created_at']);

            if ($likes->isEmpty()) {
                return $this->sendNotFoundResponse(__('No likes found.'));
            }

            return $this->sendSuccessResponse(__('Success'), $likes);
        } catch (ModelNotFoundException $e) {
            return $this->sendNotFoundResponse(__('Blog post not found.'));
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse(__('Internal server error.'));
        }
    }

    public function dislike($id)
    {
        $userId = auth()->user()->id;

        try {
            DB::beginTransaction();

            $deleteCount = Like::where('blogpost_id', $id)
                ->where('user_id', $userId)
                ->delete();

            if ($deleteCount > 0) {
                DB::commit();
                return $this->sendNotSuccessResponse(__('Dislike Successfully'));
            } else {
                DB::rollBack();
                return $this->sendNotFoundResponse(__('Requested like not found for dislike.'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendFailedResponse(__('Failed to dislike.'));
        }
    }
}
