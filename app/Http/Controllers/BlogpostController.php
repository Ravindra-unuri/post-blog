<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogpostControllerRequest;
use App\Models\Blogpost;
use App\Models\Follower;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BlogpostController extends Controller
{
    use ResponseTrait;
    public function storeBlogpost(BlogpostControllerRequest $request)
    {
        $data = Blogpost::create([
            'blogpost_name' => $request->input('blogpost_name'),
            'category_id' => $request->input('category_id'),
            'user_id' => auth()->user()->id,
            'blogpost_desc' => $request->input('blogpost_desc'),
            'upload_file' => $request->input('upload_file')
        ]);
        if ($data) {
            return $this->sendSuccessResponse(__('Blog post Uploaded Successfully'), $data);
        } else {
            return $this->sendSuccessResponse(__('Failed to upload blog post. Please try again later.'));
        }
    }

    public function showBlogpost()
    {
        $userId = auth()->user()->id;
        $data = Blogpost::leftjoin('followers as f', 'f.user_id', '=', 'blogpost.user_id')
        ->where('f.follower_id', $userId)
            ->with([
                'category' => function ($query) {
                    $query->select('id', 'category_name');
                },
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])
            ->withCount('like', 'comment')
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        if ($data) {
            return $this->sendSuccessResponse(__('Success'), $data);
        } else {
            return $this->sendNotfoundResponse(__('There is no blogpost'), $data);
        }
    }

    public function myBlogpost()
    {
        $userId = auth()->user()->id;

        $data = Blogpost::with([
            'category' => function ($query) {
                $query->select('id', 'category_name');
            },
            'user' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }
        ])
            ->withCount('like', 'comment')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($data->isEmpty()) {
            return $this->sendNotFoundResponse(__('There are no blog posts for this user.'));
        } else {
            return $this->sendSuccessResponse(__('Success'), $data);
        }
    }

    public function updateBlogpost(BlogpostControllerRequest $request, $id)
    {
        $blogpost = Blogpost::find($id);

        if (!$blogpost) {
            return $this->sendNotfoundResponse(__('Blog post not found.'));
        }

        $update = Blogpost::where('id', $id)->update([
            'blogpost_name' => $request->input('blogpost_name'),
            'category_id' => $request->input('category_id'),
            'user_id' => auth()->user()->id,
            'blogpost_desc' => $request->input('blogpost_desc'),
            'upload_file' => $request->input('upload_file')
        ]);

        if ($update) {
            return $this->sendSuccessResponse(__('Blog post updated successfully'));
        } else {
            return $this->sendFailedResponse(__('Failed to update blog post. Please try again later.'));
        }
    }

    public function deleteBlogpost($id)
    {
        $blogpost = Blogpost::find($id);

        if ($blogpost) {
            $deleted = $blogpost->delete();

            if ($deleted) {
                return $this->sendSuccessResponse(__('Blog post deleted successfully'));
            } else {
                return $this->sendFailedResponse(__('Failed to delete blog post.'));
            }
        } else {
            return $this->sendNotFoundResponse(__('The requested blog post is not found'));
        }
    }
}
