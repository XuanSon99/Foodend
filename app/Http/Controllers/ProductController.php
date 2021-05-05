<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proList = Product::orderBy('created_at', 'DESC')->get();
        $data = [];
        foreach ($proList as $pro) {
            $cate = Product::find($pro->id)->getCate()->first();
            $list = new \stdClass();
            $list->id = $pro->id;
            $list->category = $cate;
            $list->price = $pro->price;
            $list->name = $pro->name;
            $list->time = $pro->time;
            $list->image = $pro->image;
            $list->cate_id = $pro->cate_id;
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
    public function search(Request $request)
    {
        $query = $request->get('query');
        $list = Product::where('name', 'like', "%{$query}%")
            ->orWhere('price', 'like', "%{$query}%")
            ->orWhere('time', 'like', "%{$query}%")
            ->get();
        return response()->json(["status" => true, "data" =>  $list], 200);
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cate_id' => 'required',
            'price' => 'required|integer',
            'time' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $image = $request->file('image')->store('public/images');
        $cate = new Product([
            'name' => $request->name,
            'image' => str_replace("public", "storage", $image),
            'cate_id' => $request->cate_id,
            'price' => $request->price,
            'time' => $request->time,
        ]);
        $cate->save();
        return response()->json(["status" => true, "data" =>  $cate], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $Product)
    {
        return $Product;
        // return Product::find($Product->Product_id)->getProduct->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $Product)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cate_id' => 'required',
            'price' => 'required|integer',
            'time' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $image = $request->file('image')->store('public/images');
        $Product['image'] = str_replace("public", "storage", $image);
        $Product->update($request->except('image'));
        return response()->json(["status" => true, "data" => $request->all()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $Product)
    {
        $Product->delete();
        return response()->json(["status" => true], 200);
    }
}
