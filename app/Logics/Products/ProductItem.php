<?php

namespace App\Logics\Products;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Product\ProductRequest;

class ProductItem
{
    public static function create(ProductRequest $request)
    {
        return DB::transaction(function () use($request) {
            return Product::create($request->validated());
        });
    }

    public static function update(ProductRequest $request, Product $product)
    {
        return DB::transaction(function () use($request, $product) {
            return $product->update($request->validated());
        });
    }

    public static function delete(Product $product)
    {
        return $product->delete();
    }
}
