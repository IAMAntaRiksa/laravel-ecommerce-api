<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->paginate(5);
        return new SliderResource(true, 'Data list Slider', $sliders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2000',
            'link' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload image slide
        $image = $request->file('image');
        $image->storeAs('/public/sliders/', $image->hashName());

        $slider = Slider::create([
            'image' => $image->hashName(),
            'link' => $request->link,
        ]);

        if ($slider) {
            return new SliderResource(true, 'Slider Berhasil Ditambahkan', $slider);
        } else {
            return new SliderResource(false, 'Slider Gagal Ditambahkann', $slider);
        }
    }

    public function destroy($id)
    {
        $slider = Slider::whereId($id)->first();
        if ($slider) {
            $slider->delete();
            Storage::disk('local')->delete('/public/sliders/' . basename($slider->image));
            return response()->json([
                'message' => 'Slider Berhasil Dihapus'
            ], 200);
        }
        return response()->json([
            'message' => 'Slider Gagal Dihapus'
        ], 422);
    }
}