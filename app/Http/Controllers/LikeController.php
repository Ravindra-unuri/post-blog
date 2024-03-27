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
    public function doLike($blogpost_id)
    {
        $userId = auth()->user()->id;
        $check = Like::where('user_id', $userId)->where('blogpost_id', $blogpost_id)->first();
        if ($check) {
            return $this->sendConflictResponse(__('Already liked this post'));
        } else {
            $like = Like::create([
                'user_id' => $userId,
                'blogpost_id' => $blogpost_id
            ]);
            if ($like) {
                return $this->sendSuccessResponse(__('Success to like'));
            } else {
                return $this->sendNotFoundResponse(__('Unable to like'));
            }
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

    public function likeDetail(Like $like, $blogpostId)
    {
        try {
            $likes = $like->where('blogpost_id', $blogpostId)
                ->with('user:id,first_name')
                ->get(['user_id', 'blogpost_id', 'created_at']);

            if ($likes->isEmpty()) {
                return $this->sendNotFoundResponse(__('No likes found.'));
            }

            return $this->sendNotSuccessResponse(__('Success'), $likes);
        } catch (ModelNotFoundException $e) {
            return $this->sendNotFoundResponse(__('Blog post not found.'));
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse(__('Internal server error.'));
        }
    }

    // public function dislike($id)
    // {
    //     $userId = auth()->user()->id;

    //     $delete_data = Like::where('blogpost_id', $id)
    //         ->where('user_id', $userId)
    //         ->first();

    //     if ($delete_data) {
    //         $deleted = $delete_data->delete();

    //         if ($deleted) {
    //             return $this->sendSuccessResponse(__('Dislike Successfully'));
    //         } else {
    //             return $this->sendFailedResponse(__('Failed to dislike'));
    //         }
    //     } else {
    //         return $this->sendNotFoundResponse(__('Requested like not found for dislike'));
    //     }
    // }
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
                return ['message' => 'Dislike Successfully'];
            } else {
                DB::rollBack();
                return ['message' => 'Requested like not found for dislike'];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['message' => 'Failed to dislike'];
        }
    }
}
