<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->when(
            Request()->q,
            function ($product) {
                $product->where('title', 'like', '%' . Request()->q . '%');
            }
        )->latest()->paginate(5);

        return new ProductResource(true, "List Data product", $products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2000',
                'title' => 'required|unique:products',
                'category_id' => 'required',
                'content' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'weight' => 'required',
                'discount' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload image
        $image = $request->file('image');
        $image->storeAs('/public/products/', $image->hashName());

        $product = Product::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'user_id' => auth()->guard('api_admin')->user()->id,
            'content' => $request->content,
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'discount' => $request->discount
        ]);

        if ($product) {
            return new ProductResource(true, 'Data product Berhasil Disimpan', $product);
        } else {
            return new ProductResource(false, 'Data product Gagal Disimpan', $product);
        }
    }

    public function show($id)
    {
        $product = Product::whereId($id)->first();
        if ($product) {
            return new ProductResource(true, 'Detail Data Product Ditemukan', $product);
        } else {
            return new ProductResource(false, 'Detail Data Product Tidak Ditemukan', $product);
        }
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|unique:products,title,' . $product->id,
                'category_id' => 'required',
                'content' => 'required',
                'price' => 'required',
                'weight' => 'required',
                'stock' => 'required',
                'discount' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image 
        if ($request->file('image')) {
            // delete image
            Storage::disk('local')->delete('/public/products/' . basename($product->image));
            // upload image baru
            $image = $request->file('image');
            $image->storeAs('/public/products/', $image->hashName());

            $product->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'user_id' => auth()->guard('api_admin')->user()->id,
                'content' => $request->title,
                'price' => $request->price,
                'weight' => $request->weight,
                'stock' => $request->stock,
                'discount' => $request->discount,
            ]);
        } else {
            $product->update([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'user_id' => auth()->guard('api_admin')->user()->id,
                'content' => $request->title,
                'price' => $request->price,
                'weight' => $request->weight,
                'stock' => $request->stock,
                'discount' => $request->discount,
            ]);
        }
        if ($product) {
            return new ProductResource(true, 'Data prodoct berhasil diupdate', $product);
        } else {
            return new ProductResource(false, 'Data prodoct gagal diupdate', $product);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        // $this->authorize('delete', $product);
        if ($product->delete()) {
            Storage::disk('local')->delete('/public/products/' . basename($product->image));
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Dihapus Data prodoct by id'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Dihapus Data prodoct by id'
            ]);
        }
    }
}