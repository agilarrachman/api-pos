<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetProductsRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetProductsRequest $request)
    {
        $products = Product::with('category')
            ->search($request->search)
            ->when($request->product_category_id, function ($query, $categoryId) {
                $query->where('product_category_id', $categoryId);
            })
            ->latest()
            ->paginate($request->limit ?? 10);

        return ApiResponse::success(new PaginatedResource($products, ProductResource::class), 'Products list');
    }

    public function options(GetProductsRequest $request)
    {
        $products = Product::select('id', 'name')
            ->search($request->search)
            ->orderBy('name')
            ->get();

        return ApiResponse::success(ProductResource::collection($products), 'Products list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return ApiResponse::success(new ProductResource($product->load('category')), 'Product created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(new ProductResource($product), 'Product details');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', Response::HTTP_NOT_FOUND);
        }

        $product->update($request->validated());
        return ApiResponse::success(new ProductResource($product->load('category')), 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', Response::HTTP_NOT_FOUND);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return ApiResponse::success(null, 'Product deleted successfully');
    }
}
