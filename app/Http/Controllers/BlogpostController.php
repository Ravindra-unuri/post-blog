<?php

namespace App\Http\Controllers;

use App\Models\Blogpost;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BlogpostController extends Controller
{
    use ResponseTrait;
    public function storeBlogpost(Request $request)
    {
        $data = Blogpost::create([
            'blogpost_name' => $request->input('blogpost_name'),
            'category_id' => $request->input('category_id'),
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
        $data = Blogpost::all();
        if ($data) {
            return $this->sendSuccessResponse(__('Success'), $data);
        } else {
            return $this->sendNotfoundResponse(__('There is no blogpost'), $data);
        }
    }

    public function myBlogpost()
    {
        // $data=Blogpost::
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
