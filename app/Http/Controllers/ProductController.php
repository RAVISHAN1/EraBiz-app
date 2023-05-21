<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // search by name
        if ($request->has('name')) {
            $name = $request->name;
            $query->where(function ($q) use ($name) {
                $q->where('name', 'LIKE', "%$name%");
            });
        }

        // filter by min price
        if ($request->has('min_price')) {
            $min_price = $request->min_price;
            $query->where(function ($q) use ($min_price) {
                $q->where('price', '>=', $min_price);
            });
        }

        // filter by max price
        if ($request->has('max_price')) {
            $max_price = $request->max_price;
            $query->where(function ($q) use ($max_price) {
                $q->where('price', '<=', $max_price);
            });
        }

        $products = $query->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name'          => 'required|string',
                'description'   => 'required|string',
                'price'         => 'required|numeric',
                'image_url'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // save image
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $url = $image->move(public_path('images'), $imageName);

                // get image url
                $data['image_url'] = $url;
            }

            $product = Product::create($data);

            return response()->json($product->refresh(), 201);
        } catch (\Throwable $th) {
            // Log the exception
            Log::error('An error occurred while storing a product | ', ['exception' => $th->getMessage()]);
            // Handle the exception
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['error' => 'Product Not Found!'], 404);
            }

            return $product;
        } catch (\Throwable $th) {
            // Log the exception
            Log::error('An error occurred while updating a product | ', ['exception' => $th->getMessage()]);
            // Handle the exception
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string',
                'description'   => 'required|string',
                'price'         => 'required|numeric',
                'image_url'     => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // update product
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->image_url = $request->image_url;
            $product->save();

            return response()->json($product->refresh(), 201);
        } catch (\Throwable $th) {
            // Log the exception
            Log::error('An error occurred while updating a product | ', ['exception' => $th->getMessage()]);
            // Handle the exception
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Product::findOrFail($id)->delete();
            return response()->json(['message' => 'Product deleted'], 200);
        } catch (\Throwable $th) {
            // Log the exception
            Log::error('An error occurred while deleting a product | ', ['exception' => $th->getMessage()]);
            // Handle the exception
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function uploadImage(Request $request, $id)
    {
        try {
            // Validate the uploaded file
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            // Upload the image
            if ($request->hasFile('image')) {

                $diskName = 'public';
                $disk = Storage::disk('public');
                $path = $request->file('image')->storePublicly('/images/products', $diskName);

                // Save the image path to the product
                $product->image_url = $disk->url($path);
                $product->save();

                return response()->json(['message' => 'Image uploaded successfully'], 200);
            }
        } catch (\Throwable $th) {
            // Log the exception
            Log::error('An error occurred while uploading a product image | ', ['exception' => $th->getMessage()]);
            // Handle the exception
            return response()->json(['message' => 'Image upload failed'], 400);
        }
    }
}
