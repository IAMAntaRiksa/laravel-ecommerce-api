<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::when(Request()->q, function ($categories) {
            $categories->where('name', 'like', '%' . Request()->q . '%');
        })->latest()->paginate(5);

        return new CategoryResource(true, 'Data list categories', $categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2000',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload image
        $image = $request->file('image');
        $image->storeAs('/public/categories', $image->hashName());

        $category = Category::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-')
        ]);

        if ($category) {
            return new CategoryResource(true, 'Data Category Berhasil Disimpan', $category);
        } else {
            return new CategoryResource(false, 'Data Category Gagal Tersimpan', $category);
        }
    }
    public function show($id)
    {
        $category = Category::whereId($id)->first();
        if ($category) {
            return new CategoryResource(true, 'Detail Data Category!', $category);
        }
        return new CategoryResource(false, 'Detail Data Category Tidak DItemukan!', null);
    }
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $category->id,
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check image 
        if ($request->file('image')) {
            // delete image lama
            Storage::disk('local')->delete('/public/categories/' . basename($category->image));

            // upload image
            $image = $request->file('image');
            $image->storeAs('/public/categories', $image->hashName());

            // upload dengan image baru
            $category->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);
        }
        // upload without image
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-')
        ]);

        if ($category) {
            return new CategoryResource(true, 'Data Category Berhasil Diupdate', $category);
        } else {
            return new CategoryResource(false, 'Data Category Gagal Diupdate', $category);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category->delete()) {
            Storage::disk('local')->delete('/public/categories/' . basename($category->image));
            return response()->json([
                'success' => true,
                'message' => 'Berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Gagal dihapus'
            ]);
        }
    }
}