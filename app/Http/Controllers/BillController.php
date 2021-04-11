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
        $listBill = Bill::orderBy('created_at', 'DESC')->get();
        $data = [];
        foreach ($listBill as $bill) {
            $reader = Bill::find($bill->id)->getReader->first();
            $document = Bill::find($bill->id)->getDocument->first();
            $list = new \stdClass();
            $list->id = $bill->id;
            $list->reader = $reader;
            $list->document = $document;
            $list->lender = $bill->lender;
            $list->amount = $bill->amount;
            $list->borrow_time = $bill->borrow_time;
            $list->return_time = $bill->return_time;
            $list->status = $bill->status;
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
            'student_id' => 'required',
            'document_id' => 'required',
            'lender' => 'required|string',
            'borrow_time' => 'required|date',
            'return_time' => 'required|date',
            'status' => 'required|in:ok,not'
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
            'student_id' => 'required',
            'document_id' => 'required',
            'lender' => 'required|string',
            'borrow_time' => 'required|date',
            'return_time' => 'required|date',
            'status' => 'required|in:ok,not'
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $bill->update($request->all());
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
        $bill->delete();
        return response()->json(["status" => true], 200);
    }
}
