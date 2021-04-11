<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Validator;
use App\Models\Bill;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listDoc = Document::orderBy('created_at', 'DESC')->get();
        $data = [];
        foreach ($listDoc as $doc) {
            $author = Document::find($doc->id)->getAuthor->first();
            $language = Document::find($doc->id)->getLanguage->first();
            $publisher = Document::find($doc->id)->getPublisher->first();
            $field = Document::find($doc->id)->getField->first();
            $list = new \stdClass();
            $list->id = $doc->id;
            $list->author = $author;
            $list->language = $language;
            $list->publisher = $publisher;
            $list->field = $field;
            // $list->code = $doc->code;
            $list->name = $doc->name;
            $list->publishing_year = $doc->publishing_year;
            $list->price = $doc->price;
            $list->page_number = $doc->page_number;
            $list->category = $doc->category;
            $list->amount = $doc->amount;
            //get so luong muon nhung chua tra
            $borrowBook = Bill::where("document_id", $doc->id)->where('status', 'not')->select("amount")->get();
            $borrowed = 0;
            foreach ($borrowBook as $item) {
                $borrowed += $item->amount;
            }
            $stillBook = floatval($doc->amount) - $borrowed;
            $list->still_amount = $stillBook;
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
            // 'code' => 'required|string|unique:documents',
            'name' => 'required|string',
            'author_id' => 'required',
            'publisher_id' => 'required',
            'language_id' => 'required',
            'field_id' => 'required',
            'publishing_year' => 'required|date',
            'price' => 'required|numeric',
            'page_number' => 'required|integer',
            'category' => 'required|string',
            'amount' => 'required|integer',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        Document::create($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        return $document;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $validate = Validator::make($request->all(), [
            // 'code' => 'required|unique|string:documents',
            'name' => 'required|string',
            'author_id' => 'required',
            'publisher_id' => 'required',
            'language_id' => 'required',
            'field_id' => 'required',
            'publishing_year' => 'required|date',
            'price' => 'required|numeric',
            'page_number' => 'required|integer',
            'category' => 'required|string',
            'amount' => 'required|integer',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $document->update($request->all());
        return response()->json(["status" => true, "data" => $request->all()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return response()->json(["status" => true], 200);
    }
}
