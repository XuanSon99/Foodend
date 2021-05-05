<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Validator;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listBill = Bill::orderBy('created_at', 'DESC')->groupBy("bill_id")->select("bill_id")->get();
        $data = [];
        foreach ($listBill as $b) {
            $bills = Bill::where("bill_id", $b->bill_id)->get();
            $list = new \stdClass();
            $list->products = $this->getList($bills);
            $list->bill_id = $b->bill_id;
            array_push($data, $list);
        }
        return $data;
    }

    public function getList($listBill)
    {
        $data = [];
        foreach ($listBill as $bill) {
            $cus = Bill::find($bill->id)->getCustomer->first();
            $pro = Bill::find($bill->id)->getProduct->first();
            $list = new \stdClass();
            $list->id = $bill->id;
            $list->customer = $cus;
            $list->product = $pro;
            $list->address = $bill->address;
            $list->price = $bill->price;
            $list->quantity = $bill->quantity;
            $list->status = $bill->status;
            $list->time = $bill->created_at;
            $list->bill_id = $bill->bill_id;
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
            'address' => 'required|string',
            'price' => 'required',
            'quantity' => 'required',
            'status' => 'required|in:delivered,wating,cancel,remove'
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        Bill::create($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        return $bill;
    }

    public function getBill(Request $request)
    {
        $listBill = Bill::where("user_id", $request->user_id)->orderBy('created_at', 'DESC')->groupBy("bill_id")->select("bill_id")->get();
        $data = [];
        foreach ($listBill as $b) {
            $bills = Bill::where("user_id", $request->user_id)->where("bill_id", $b->bill_id)->get();
            $list = new \stdClass();
            $list->products = $this->getList($bills);
            $list->bill_id = $b->bill_id;
            array_push($data, $list);
        }
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        $validate = Validator::make($request->all(), [
            'status' => 'required|in:yes,no'
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $bills = Bill::where("bill_id", $bill->bill_id)->get();
        foreach ($bills as $b) {
            $b->update($request->all());
        }
        return response()->json(["status" => true, "data" => $request->all()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        $bills = Bill::where("bill_id", $bill->bill_id)->get();
        foreach ($bills as $b) {
            $b->delete();
        }
        return response()->json(["status" => true], 200);
    }
}
