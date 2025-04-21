<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $_productService)
    {
        $this->productService = $_productService;
    }

    public function getAll()
    {
        $response   = $this->productService->getAll();

        return response()->json($response, $response['code']);
    }

    public function fetch(Request $request)
    {
        $response   = $this->productService->fetch($request);

        return response()->json($response, $response['code']);
    }

    public function store(Request $request)
    {
        $response   = $this->productService->save($request);

        return response()->json($response, $response['code']);
    }

    public function edit($id)
    {
        $response   = $this->productService->getData($id);

        return response()->json($response, $response['code']);
    }

    public function update(Request $request)
    {
        $response   = $this->productService->update($request);

        return response()->json($response, $response['code']);
    }

    public function destroy($id)
    {
        $response   = $this->productService->destory($id);

        return response()->json($response, $response['code']);
    }

    public function getImage($id)
    {
        $response   = $this->productService->getImage($id);

        return response()->json($response, $response['code']);
    }
}
