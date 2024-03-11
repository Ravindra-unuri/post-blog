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
        //
    }

    // public function showDetailedBlogpost()
    // {

    // }

    public function updateBlogpost()
    {
        //
    }

    public function deleteBlogpost()
    {
        //
    }
}
