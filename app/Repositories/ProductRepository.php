<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ICrudRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * Obtiene una lista paginada de productos con filtros.
     *
     * @param array $filters
     * @param int $perPage
     * 
     * @return LengthAwarePaginator
     * 
     */
    public function getAllProducts(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Product::query();

        // Filtro por nombre
        $query->when(
            $filters['name'] ?? null,
            fn(Builder $query, $name) =>
            $query->where('name', 'like', "%{$name}%")
        );

        // Filtro por rango de precios
        $query->when(
            $filters['min_price'] ?? null,
            fn(Builder $query, $min) =>
            $query->where('price', '>=', $min)
        );
        $query->when(
            $filters['max_price'] ?? null,
            fn(Builder $query, $max) =>
            $query->where('price', '<=', $max)
        );

        // Filtro por stock
        if (isset($filters['in_stock']) && ($filters['in_stock'] || $filters['in_stock'] === "0")) {
            $query->where('stock', $filters['in_stock'] ? '>' : '<=', 0);
        }

        return $query->paginate($perPage);
    }

    /**
     * Guardar un nuevo producto.
     *
     * @param array $data
     * 
     * @return Product
     * 
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Obtiene un producto por id.
     *
     * @param int $id
     * 
     * @return Product|null
     * 
     */
    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Actualiza un producto.
     *
     * @param Product $product
     * @param array $data
     * 
     * @return bool
     * 
     */
    public function updateProduct(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * Elimina un producto.
     *
     * @param Product $product
     * 
     * @return bool
     * 
     */
    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }
}
