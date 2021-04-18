<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Rating::orderBy('created_at', 'DESC')->get();
        $listCmt = Rating::orderBy('created_at', 'DESC')->get();
        return $this->getList($listCmt);
    }

    public function getList($listCmt)
    {
        $data = [];
        foreach ($listCmt as $item) {
            $cus = Rating::find($item->id)->getCustomer->first();
            $pro = Rating::find($item->id)->getProduct->first();
            $list = new \stdClass();
            $list->id = $item->id;
            $list->customer = $cus;
            $list->product = $pro;
            $list->start = $item->start;
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
            'start' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        Rating::create($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rating  $Rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $Rating)
    {
        return $Rating;
    }
    public function getRating(Request $request)
    {
        $list = Rating::where("product_id", $request->product_id)->orderBy('created_at', 'DESC')->get();
        return $this->getList($list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rating  $Rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $Rating)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'start' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $Rating->update($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rating  $Rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $Rating)
    {
        $Rating->delete();
        return response()->json(["status" => true], 200);
    }
}
