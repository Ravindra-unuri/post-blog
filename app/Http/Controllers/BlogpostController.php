<?php

namespace App\Http\Controllers;

use App\Models\Blogpost;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogpostController extends Controller
{
    use ResponseTrait;
    public function storeBlogpost(Request $request)
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
        $data = Blogpost::leftJoin('like as l', 'l.blogpost_id', '=', 'blogpost.id')
            ->leftJoin('comment as c', 'c.blogpost_id', '=', 'blogpost.id')
            ->leftJoin('category as ct', 'ct.id', '=', 'blogpost.category_id')
            ->leftJoin('users as u', 'u.id', '=', 'blogpost.user_id')
            ->select(
                'blogpost.id as Blogpost_Id',
                'blogpost.blogpost_name as Blogpost_Name',
                'ct.category_name as Category_Name',
                'u.first_name as User_Name',
                DB::raw('COUNT(DISTINCT l.id) as Likes'),
                DB::raw('COUNT(DISTINCT c.id) as Comments')
            )
            ->groupBy('blogpost.id', 'blogpost.blogpost_name', 'ct.category_name', 'u.first_name')
            ->get();

        if ($data) {
            return $this->sendSuccessResponse(__('Success'), $data);
        } else {
            return $this->sendNotfoundResponse(__('There is no blogpost'), $data);
        }
    }

    public function myBlogpost()
    {
        $userId = auth()->user()->id;
        $data = Blogpost::leftJoin('like as l', 'l.blogpost_id', '=', 'blogpost.id')
            ->leftJoin('comment as c', 'c.blogpost_id', '=', 'blogpost.id')
            ->leftJoin('category as ct', 'ct.id', '=', 'blogpost.category_id')
            ->leftJoin('users as u', 'u.id', '=', 'blogpost.user_id')
            ->select(
                'blogpost.id as Blogpost_Id',
                'blogpost.blogpost_name as Blogpost_Name',
                'ct.category_name as Category_Name',
                'u.first_name as User_Name',
                'blogpost.user_id', // Include user_id in select
                DB::raw('COUNT(DISTINCT l.id) as Likes'),
                DB::raw('COUNT(DISTINCT c.id) as Comments')
            )
            ->where('blogpost.user_id', $userId) // Filter by user_id
            ->groupBy('blogpost.id', 'blogpost.blogpost_name', 'ct.category_name', 'u.first_name', 'blogpost.user_id')
            ->get();
        if ($data->isEmpty()) {
            return $this->sendNotFoundResponse(__('There are no blog posts for this user.'));
        } else {
            return $this->sendSuccessResponse(__('Success'), $data);
        }
    }

    // public function showDetailedBlogpost()
    // {

    // }

    public function updateBlogpost(Request $request, $id)
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

    public function deleteBlogpost()
    {
        //
    }
}
