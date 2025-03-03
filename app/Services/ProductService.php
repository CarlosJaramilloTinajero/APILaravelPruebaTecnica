<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    // Inyeccion de dependencia del repositorio ProductRepository.
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * Retorna productos con filtros y paginacion.
     *
     * @param array $filters
     * @param int $perPage
     * 
     * @return LengthAwarePaginator
     * 
     */
    public function getProducts(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts($filters, $perPage);
    }

    /**
     * Crea un producto.
     *
     * @param array $data
     * 
     * @return Product
     * 
     */
    public function createProduct(array $data): Product
    {
        $product = $this->productRepository->createProduct($data);

        // Si se agregara una imagen en el request, la guardamos en el servidor
        if ($product && (isset($data['image']) && $data['image'])) {

            $imageData = $this->saveProductImage($data['image'], $data['name']);

            // Si se guardo correctamente la imagen, se crea el registro en la BD.
            if ($imageData) {
                $product->image()->create([
                    'name' => "Imagen para el producto {$product->name}.",
                    'path' => $imageData['path'],
                    'disk' => $imageData['disk']
                ]);
            } else {
                // Si ocurre algun error se genera un Log de error
                Log::error("Error al subir la imagen del producto con el ID {$product->id}");
            }
        }

        return $product;
    }

    /**
     * Retorna un producto por id.
     *
     * @param int $id
     * 
     * @return Product|null
     * 
     */
    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->getProductById($id);
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
        // Si se agregara una imagen en el request, la guardamos en el servidor y eliminamos la anterior
        if (isset($data['image']) && $data['image']) {

            // Si se elimino correctamente la imagen actual del producto
            if ($this->deleteProductImage($product)) {

                // Se guarda la nueva imagen
                $imageData = $this->saveProductImage($data['image'], $data['name']);

                // Si se guardo correctamente la imagen, se crea el registro en la BD.
                if ($imageData) {
                    $product->image()->create([
                        'name' => "Imagen para el producto {$product->name}.",
                        'path' => $imageData['path'],
                        'disk' => $imageData['disk']
                    ]);
                } else {
                    // Si ocurre algun error se genera un Log de error
                    Log::error("Error al subir la imagen del producto con el ID {$product->id}");
                }
            }
        }

        return $this->productRepository->updateProduct($product, $data);
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
        return $this->productRepository->deleteProduct($product);
    }

    /**
     * Guarda la imagen de un producto en el almacenamiento y retorna la ruta o un nulo.
     *
     * @param mixed $image
     * @param string $productName
     * 
     * @return string|null
     * 
     */
    private function saveProductImage($image, string $productName): ?array
    {
        try {
            // nombre de la imagen
            $fileName = Str::slug($productName) . '-' . Str::uuid() .  '.' . $image->getClientOriginalExtension();

            // Ruta de la imagen
            $path = 'images/products';

            // disco de almacenamiento
            $disk = 'public';

            // Se guarda la imagen con la clase Storage
            $imagePath = Storage::disk($disk)->putFileAs($path, $image,  $fileName);

            if (!$imagePath) return null;

            return [
                'path' => $imagePath,
                'disk' => $disk
            ];
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Funcion que elimina la imagen de un producto.
     *
     * @param Product $product
     * 
     * @return bool
     * 
     */
    private function deleteProductImage(Product $product): bool
    {
        try {
            $image = $product->image;

            // Si el producto no tiene imagen
            if (!$image) return true;

            // Si la imagen existe en el servidor
            if (Storage::disk($image->disk)->exists($image->path)) {
                // Si no se elimino la imagen del servidor
                if (!Storage::disk($image->disk)->delete($image->path)) return false;
            }

            // Se elimina el registro de la imagen en la BD
            return $image->delete();
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
