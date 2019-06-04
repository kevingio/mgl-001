<?php

namespace App\Http\Controllers;

use App\Models\Posting;
use App\Models\Indent;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;

class PostingController extends Controller
{

    public function __construct(Posting $posting, Indent $indent, Product $product)
    {
        $this->posting = $posting;
        $this->indent = $indent;
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postings = $this->posting->with(['details.product:id,name,qty', 'type'])->latest()->get();
        return response()->json([
            'error' => false,
            'items' => $postings
        ], 200);
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
        $data = $request->all();
        DB::beginTransaction();
        try {
            $posting = $this->posting->create($data);
            foreach ($data['details'] as $product) {
                $posting->details()->create($product);
                $stock = $this->product->find($product['product_id']);
                $diff = $stock->qty - $product['qty'];
                if($diff >= 0) {
                    $stock->decrement('qty', $product['qty']);
                } else {
                    $stock->decrement('qty', $stock->qty);

                    // create indent request
                    $indent = $this->indent->firstOrCreate([ 'posting_id' => $posting->id ]);
                    $indent->details()->create([
                        'product_id' => $product['product_id'],
                        'qty' => abs($diff)
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'item' => $posting
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function show(Posting $posting)
    {
        return response()->json([
            'error' => false,
            'item' => $posting->with(['details.product', 'type'])->get()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function edit(Posting $posting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posting $posting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posting $posting)
    {
        //
    }
}
