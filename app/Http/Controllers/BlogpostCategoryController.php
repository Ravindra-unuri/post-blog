<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BlogpostCategoryController extends Controller
{
    use ResponseTrait;

    public function createCategory(CategoryRequest $request)
    {
        $category = Category::create([
            'category_name' => $request->input('category_name'),
        ]);

        if ($category) {
            return $this->sendSuccessResponse(__('Category added successfully'), $category);
        } else {
            return $this->sendFailedResponse(__('Failed to create category'));
        }
    }

    public function viewCategories()
    {
        $data = Category::paginate(3);
        if ($data) {
            return $this->sendNotSuccessResponse(__('Success'), $data);
        } else {
            return $this->sendFailedResponse(__('Success'));
        }
    }

    public function showCategoryDetails($id)
    {
        $data = Category::where($id, 'id')->first();
        if ($data) {
            return $this->sendNotSuccessResponse(__('Success'), $data);
        } else {
            return $this->sendFailedResponse(__('Success'));
        }
    }

    public function updateCategory(CategoryRequest $request, Category $category)
    {
        if ($category) {
            $category->update([
                'category_name' => $request->input('category_name'),
            ]);

            return $this->sendSuccessResponse(__('Requested Category Updated Successfully'), $category);
        } else {
            return $this->sendNotFoundResponse(__('Requested Category Not Found'));
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            $category->delete();
            return $this->sendSuccessResponse(__('Requested Category Deleted Successfully'));
        } else {
            return $this->sendNotFoundResponse(__('Requested Category Not Found'));
        }
    }
}
