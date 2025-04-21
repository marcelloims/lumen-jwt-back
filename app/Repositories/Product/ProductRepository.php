<?php

namespace App\Repositories\Product;

use App\Models\Photo;
use App\Repositories\BaseRepositories;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends BaseRepositories
{
    protected $baseService;

    public function __construct(BaseService $_baseService,)
    {
        $this->baseService = $_baseService;
    }

    public function getData($id)
    {
        if ($id) {
            return DB::table('products')->join('photos', 'products.id', "=", "photos.product_id")
                ->select("products.*", "image1", "image2", "image3")
                ->where('products.id', $id)->first();
        } else {
            return DB::table('products')->select("id", "pcode", "name")->get();
        }
    }

    public function fetch($request)
    {
        $query = DB::table('products')->select('id', $request->columns[0], $request->columns[1], $request->columns[2]);

        if ($request->search) {
            $query->where('pcode', 'like', '%' . $request->search . '%')
                ->orWhere("name", 'like', '%' . $request->search . '%')
                ->orWhere("status", 'like', '%' . $request->search . '%');
        }

        if ($request->sorting) {
            $query->orderBy(strtolower($request->sorting[1]), $request->sorting[0]);
        }

        return $query->paginate($request->perPage);
    }

    public function save($validator, $request)
    {
        $data = array_merge(
            $validator->validated(),
            [
                "brand_code"    => $request->brand_code,
                "hight_cm"      => $request->hight_cm,
                "width_cm"      => $request->width_cm,
                "long_cm"       => $request->long_cm,
                "tax"           => $request->tax,
            ],
            $this->baseService->auditableInsert($request->userEmail)
        );

        $image1 = $request->image1 == "undefined" ? null : $request->image1->getClientOriginalName();
        $image2 = $request->image2 == "undefined" ? null : $request->image2->getClientOriginalName();
        $image3 = $request->image3 == "undefined" ? null : $request->image3->getClientOriginalName();

        $request->image1 == "undefined" ? null : Storage::disk('product')->put($image1, file_get_contents($request->image1));
        $request->image2 == "undefined" ? null : Storage::disk('product')->put($image2, file_get_contents($request->image2));
        $request->image3 == "undefined" ? null : Storage::disk('product')->put($image3, file_get_contents($request->image3));

        $productId = DB::table('products')->insertGetId($data);

        $photo = [
            'product_id'    => $productId,
            'image1'        => $image1,
            'image2'        => $image2,
            'image3'        => $image3
        ];

        BaseRepositories::store('photos', $photo);

        return true;
    }

    public function updated($validator, $request)
    {
        $imageProduct = DB::where('product_id', $request->id)->first();

        $valImage1 = $imageProduct->image1;
        $valImage2 = $imageProduct->image2;
        $valImage3 = $imageProduct->image3;

        if ($request->image1 == 'null' && empty($request->file('image1'))) {
            $valImage1 = $imageProduct->image1;
        }

        if ($request->image1 == 'null') {
            $valImage1 = $imageProduct->image1;
        }

        if (!empty($request->file('image1')) && empty($imageProduct->image1)) {
            Storage::disk('product')->put($request->image1->getClientOriginalName(), file_get_contents($request->image1));
            $valImage1 = $request->image1->getClientOriginalName();
        }

        if (!empty($request->file('image1')) && $request->image1->getClientOriginalName() != $imageProduct->image1) {
            if ($imageProduct->image1) {
                Storage::delete($imageProduct->image1);
            }
            Storage::disk('product')->put($request->image1->getClientOriginalName(), file_get_contents($request->image1));

            $valImage1 = $request->image1->getClientOriginalName();
        }

        if (!empty($request->file('image1')) && $request->image1->getClientOriginalName() == $imageProduct->image1) {

            $valImage1 = $imageProduct->image1;
        }

        // ================================================================================================================

        if ($request->image2 == 'null' && empty($request->file('image2'))) {
            $valImage2 = $imageProduct->image2;
        }

        if ($request->image2 == 'null') {
            $valImage2 = $imageProduct->image2;
        }

        if (!empty($request->file('image2')) && empty($imageProduct->image2)) {
            Storage::disk('product')->put($request->image2->getClientOriginalName(), file_get_contents($request->image2));
            $valImage2 = $request->image2->getClientOriginalName();
        }

        if (!empty($request->file('image2')) && $request->image2->getClientOriginalName() != $imageProduct->image2) {
            if ($imageProduct->image2) {
                Storage::delete($imageProduct->image2);
            }
            Storage::disk('product')->put($request->image2->getClientOriginalName(), file_get_contents($request->image2));

            $valImage2 = $request->image2->getClientOriginalName();
        }

        if (!empty($request->file('image2')) && $request->image2->getClientOriginalName() == $imageProduct->image2) {

            $valImage2 = $imageProduct->image2;
        }

        // ================================================================================================================

        if ($request->image3 == 'null' && empty($request->file('image3'))) {
            $valImage3 = $imageProduct->image3;
        }

        if ($request->image3 == 'null') {
            $valImage3 = $imageProduct->image3;
        }

        if (!empty($request->file('image3')) && empty($imageProduct->image3)) {
            Storage::disk('product')->put($request->image3->getClientOriginalName(), file_get_contents($request->image3));
            $valImage3 = $request->image3->getClientOriginalName();
        }

        if (!empty($request->file('image3')) && $request->image3->getClientOriginalName() != $imageProduct->image3) {
            if ($imageProduct->image3) {
                Storage::delete($imageProduct->image3);
            }
            Storage::disk('product')->put($request->image3->getClientOriginalName(), file_get_contents($request->image3));

            $valImage3 = $request->image3->getClientOriginalName();
        }

        if (!empty($request->file('image3')) && $request->image3->getClientOriginalName() == $imageProduct->image3) {

            $valImage3 = $imageProduct->image3;
        }


        $photos = [
            "image1" => $valImage1,
            "image2" => $valImage2,
            "image3" => $valImage3,
        ];

        DB::where('product_id', $request->id)->update($photos);

        $data = array_merge($validator->validated(), [
            "description"   => $request->description == 'null' ? null : $request->description,
            "brand_code"    => $request->brand_code == 'null' ? null : $request->brand_code,
            "hight_cm"      => $request->hight_cm == 'null' ? null : $request->hight_cm,
            "width_cm"      => $request->width_cm == 'null' ? null :  $request->width_cm,
            "long_cm"       => $request->long_cm == 'null' ? null : $request->long_cm,
            "tax"           => $request->tax == 'null' ? null : $request->tax,
        ], $this->baseService->auditableUpdate($request->userEmail));
        return BaseRepositories::update('products', $data, $request->id);
    }

    public function destroyed($id)
    {
        DB::where('product_id', $id)->delete();
        return BaseRepositories::destroy('products', $id);
    }

    public function getImage($id)
    {
        // $imageProduct = DB::where('product_id', $id)->first();

        // $image1Url = asset('product/' . $imageProduct->image1);
        // $image2Url = asset('product/' . $imageProduct->image2);
        // $image3Url = asset('product/' . $imageProduct->image3);

        // $imageUrl = [
        //     "image1" => $image1Url,
        //     "image2" => $image2Url,
        //     "image3" => $image3Url,
        // ];

        // return $imageUrl;
    }
}
