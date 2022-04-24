<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Logics\Products\ProductItem;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\Product\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::get();

        return $this->jsonResponse(HTTP_SUCCESS, 'All Products Retrieved',new ProductCollection($products));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Product\ProductRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ProductRequest $request): JsonResponse
    {
        $created_product = ProductItem::create($request);

        return $this->jsonResponse(HTTP_CREATED, 'Product Created Successfully', new ProductResource($created_product));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->jsonResponse(HTTP_SUCCESS, 'Product Fetched Successfully', new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\ProductRequest $request
     * @param  \App\Models\Product  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        ProductItem::update($request, $product);

        return $this->jsonResponse(HTTP_ACCEPTED, "Product Updated Successfully");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        ProductItem::delete($product);

        return $this->jsonResponse(HTTP_SUCCESS, "Product Deleted Successfully");
    }
}
