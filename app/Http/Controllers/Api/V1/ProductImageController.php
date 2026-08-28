<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProductImageRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(UploadProductImageRequest $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', Response::HTTP_NOT_FOUND);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $path = $request->file('image')->store('products', 'public');
        $product->update(['image' => $path]);

        return ApiResponse::success(new ProductResource($product), 'Product image uploaded successfully');
    }
}
