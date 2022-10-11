<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        //
        $products = ProductResource::collection($this->productRepo->getAll());
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Get products success');
        $this->setData($products);
        return $this->respond();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $category = $this->productRepo->getCategoryAll();
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Get categories success');
        $this->setData($category);
        return $this->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // var_dump($request->all());
        // die();
        DB::beginTransaction();
        try {
            $category_id = $this->productRepo->checkIdCategory($request->category);
            $product = $this->productRepo->create([
                'product_name' => $request->product_name,
                'product_price' => $request->product_price,
                'category_id' => $category_id,
                'product_rating' => 5
            ]);
            $this->productRepo->createProductSize($product->id, $request->sizes);
            $this->productRepo->createImage($product->id, $request->images);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
            $this->setStatus('failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
            // response()->json('error', 500);
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Create product success');
        return $this->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = ProductResource::collection($this->productRepo->getAll());
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Get product success');
        $this->setData($product);
        return $this->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
        DB::beginTransaction();
        try {
            $product = $this->productRepo->update($id, $data);
            $product_image = $this->productRepo->updateImage($request->product_image_id, $request->product_image_link);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json('error dada', 500);
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Update product success');
        $this->setData($product);
        return $this->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $arrId = $request->product_ids;
            $this->productRepo->deleteProduct($arrId);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Delete product success');
        return $this->respond();
    }
}
