<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $_productRepository)
    {
        $this->productRepository = $_productRepository;
    }

    public function getAll()
    {
        $response   = $this->productRepository->getData($id = null);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_OK,
                "status"    => "success",
                "response"  => $response
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => false,
                "process"   => "fetch"
            ];
        }
    }

    public function fetch($request)
    {
        $response   = $this->productRepository->fetch($request);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_OK,
                "status"    => "success",
                "response"  => $response
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => false,
                "process"   => "fetch"
            ];
        }
    }

    public function save($request)
    {
        $validator  = Validator::make($request->all(), [
            'category_id'   => 'required|max:255',
            'type_id'       => 'required|max:255',
            'pcode'         => 'required|max:255',
            'name'          => 'required|max:255',
            'unit'          => 'required|max:255',
            'status'        => 'required|max:255',
        ], [
            'category_id.required' => 'The category field is required',
            'type_id.required' => 'The type field is required',
        ]);

        if ($validator->fails()) {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => $validator->errors(),
                "process"   => "validation"
            ];
        }

        $response = $this->productRepository->save($validator, $request);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_CREATED,
                "status"    => "success",
                "message"   => "Data has been created"
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => $validator->errors(),
                "process"   => "insert"
            ];
        }
    }

    public function getData($id)
    {
        $response   = $this->productRepository->getData($id);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_OK,
                "status"    => "success",
                "response"  => $response
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => false,
                "process"   => "getData"
            ];
        }
    }

    public function update($request)
    {
        $validator  = Validator::make($request->all(), [
            'category_id'   => 'required|max:255',
            'type_id'       => 'required|max:255',
            'pcode'         => 'required|max:255',
            'name'          => 'required|max:255',
            'unit'          => 'required|max:255',
            'status'        => 'required|max:255',
        ], [
            'category_id.required' => 'The category field is required',
            'type_id.required' => 'The type field is required',
        ]);

        if ($validator->fails()) {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => $validator->errors(),
                "process"   => "validation"
            ];
        }

        $response = $this->productRepository->updated($validator, $request);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_CREATED,
                "status"    => "success",
                "message"   => "Data has been updated"
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => $validator->errors(),
                "process"   => "update"
            ];
        }
    }

    public function destory($id)
    {
        $response = $this->productRepository->destroyed($id);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_OK,
                "status"    => "success",
                "message"   => "Your file has been deleted"
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "process"   => "delete"
            ];
        }
    }

    public function getImage($id)
    {
        $response = $this->productRepository->getImage($id);

        if ($response == true) {
            return [
                "code"      => Response::HTTP_OK,
                "status"    => "success",
                "response"  => $response
            ];
        } else {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => false,
                "process"   => "getImage"
            ];
        }
    }
}
