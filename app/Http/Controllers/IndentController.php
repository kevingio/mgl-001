<?php

namespace App\Http\Controllers;

use App\Models\Indent;
use App\Models\IndentDetail;
use App\Models\Posting;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;

class IndentController extends Controller
{

    public function __construct(Posting $posting, Indent $indent, IndentDetail $indentDetail, Product $product)
    {
        $this->posting = $posting;
        $this->indent = $indent;
        $this->indentDetail = $indentDetail;
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postings = $this->posting->with(['details.product:id,name,qty', 'type', 'indent.details.product:id,name,qty'])
                                ->has('indent')
                                ->latest()
                                ->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Indent  $indent
     * @return \Illuminate\Http\Response
     */
    public function show(Indent $indent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Indent  $indent
     * @return \Illuminate\Http\Response
     */
    public function edit(Indent $indent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Indent  $indent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Indent $indent)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $indentItem = $this->indentDetail->find($data['id']);
            $product = $this->product->find($data['product_id']);
            $diff = $product->qty - $data['qty'];
            if(!empty($data['accepted_by'])) {
                $data['accepted_at'] = now();
                if(empty($indentItem->accepted_by)) {
                    if($product->qty >= $data['qty']) {
                        $product->decrement('qty', $data['qty']);
                    } else {
                        throw new \Exception;
                    }
                }
            } else {
                $data['accepted_at'] = null;
                if(!empty($indentItem->accepted_by)) {
                    $product->increment('qty', $data['qty']);
                }
            }
            $indentItem->update($data);

            DB::commit();
            return response()->json([
                'error' => false,
                'item' =>  [
                    'accepted_at' => $data['accepted_at'] ? date('l, jS F Y', strtotime($data['accepted_at'])) : null,
                    'accepted_by' => $data['accepted_by'],
                    'qty' => $data['qty']
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Indent  $indent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Indent $indent)
    {
        //
    }
}
