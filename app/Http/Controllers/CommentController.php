<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Comment::orderBy('created_at', 'DESC')->get();
        $listCmt = Comment::orderBy('created_at', 'DESC')->get();
        return $this->getList($listCmt);
    }

    public function getList($listCmt)
    {
        $data = [];
        foreach ($listCmt as $item) {
            $cus = Comment::find($item->id)->getCustomer->first();
            $pro = Comment::find($item->id)->getProduct->first();
            $list = new \stdClass();
            $list->id = $item->id;
            $list->customer = $cus;
            $list->product = $pro;
            $list->content = $item->content;
            array_push($data, $list);
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'content' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        Comment::create($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $Comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $Comment)
    {
        return $Comment;
    }
    public function getComment(Request $request)
    {
        $list = Comment::where("product_id", $request->product_id)->orderBy('created_at', 'DESC')->get();
        return $this->getList($list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $Comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $Comment)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'content' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $Comment->update($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $Comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $Comment)
    {
        $Comment->delete();
        return response()->json(["status" => true], 200);
    }
}
