<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function create(array $params): int {
        return Product::create($params)->id;
    }

    public function update(int $id, array $params): bool {
        return Product::find($id)?->update($params);
    }

    public function delete(int $id): bool {
        return Product::find($id)?->update(['active' => 0]);
    }

    public function read(int $id): ?Product {
        return Product::find($id);
    }
}