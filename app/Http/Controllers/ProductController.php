<?php

namespace App\Http\Controllers;

use App\Helpers\HelpersController;
use App\Http\Requests\Product\ProductFilterRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller
{
    // Inyeccion de dependencia de la clase HelpersController, y el servicio de productos ProductService.
    public function __construct(
        protected HelpersController $helpersController,
        protected ProductService $productService
    ) {}


    /**
     * Lista de productos con filtros y paginación.
     *
     * @param ProductFilterRequest $request
     * 
     * @return JsonResponse
     * 
     */
    public function index(ProductFilterRequest $request): JsonResponse
    {
        $products = $this->productService->getProducts($request->validated(), $request->per_page ?? 20);
        return $this->helpersController->responseSuccessApi(['products' => $products]);
    }

    /**
     * Crea un nuevo producto.
     *
     * @param ProductRequest $request
     * 
     * @return JsonResponse
     * 
     */
    public function store(ProductRequest $request): JsonResponse
    {
        // Si no se creo el producto, se responde con un mensaje de error.
        if (!$product = $this->productService->createProduct($request->validated())) return $this->helpersController->responseFailApi('Error al crear el producto.');

        // Si se creo correctamente el producto se responde con un codigo de estatus 201, y el producto creado.
        return $this->helpersController->responseSuccessApi(['product' => $product->makeHidden(['deleted_at'])], 201);
    }

    /**
     * Muestra un producto específico.
     *
     * @param Product $product
     * 
     * @return JsonResponse
     * 
     */
    public function show(Product $product): JsonResponse
    {
        return $this->helpersController->responseSuccessApi(['product' => $product]);
    }

    /**
     * Actualiza un producto existente.
     *
     * @param ProductRequest $request
     * @param Product $product
     * 
     * @return JsonResponse
     * 
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        // Si no se actualizo correctamente el producto, se responde con un mensaje de error.
        if (!$this->productService->updateProduct($product, $request->validated())) return $this->helpersController->responseFailApi('Error al editar el producto');

        // Si se actualizo correctamente, se responde con una respueste de exito.
        return $this->helpersController->responseSuccessApi(['product' => $product->makeHidden(['deleted_at'])]);
    }

    /**
     * Elimina un producto.
     *
     * @param Product $product
     * 
     * @return JsonResponse
     * 
     */
    public function destroy(Product $product): JsonResponse
    {
        // Si no se elimino correctamente el producto, se responde con un mensaje de error.
        if (!$this->productService->deleteProduct($product)) return $this->helpersController->responseFailApi('Error al eliminar el producto');

        // Si se elimino correctamente, se responde con una respueste de exito.
        return $this->helpersController->responseSuccessApi(['msg' => 'Producto eliminado correctamente.']);
    }
}
