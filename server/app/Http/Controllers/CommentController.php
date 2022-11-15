<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'comment' => 'required',
            'rating' => 'required|numeric',
        ]);
        if (!$validated) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        DB::beginTransaction();
        try {
            Comment::create([
                'product_id' => $request->product_id,
                'customer_id' => $request->customer_id,
                'comment' => $request->comment,
                'comment_rating' => $request->rating,
            ]);
            $comment = Comment::where('product_id', '=', $request->product_id)->orderBy('id', 'DESC')->get();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('Failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
        }
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('Success');
        $this->setMessage('Comment success');
        $this->setData(CommentResource::collection($comment));
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
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('Success');
        $this->setMessage('Comment success');
        $this->setData(CommentResource::collection(Comment::where('product_id', '=', $id)->orderBy('id', 'DESC')->get()));
        return $this->respond();
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
