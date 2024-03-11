<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BlogpostCategoryController extends Controller
{
    use ResponseTrait;

    public function createCategory(Request $request)
    {
        // dd($request);
        $existingCategory = Category::where('category_name', $request->input('category_name'))->first();

        if ($existingCategory) {
            return $this->sendConflictResponse(__('Category already exists'), $existingCategory);
        }

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
        return $data;
    }

    public function showCategoryDetails($id)
    {
        $data = Category::where($id, 'id')->first();
        return $data;
    }

    public function updateCategory($id, $request)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'category_name' => $request->input('category_name'),
        ]);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
