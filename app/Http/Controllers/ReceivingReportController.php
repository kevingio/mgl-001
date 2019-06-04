<?php

namespace App\Http\Controllers;

use App\Models\ReceivingReport;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;

class ReceivingReportController extends Controller
{

    public function __construct(ReceivingReport $receivingReport, Product $product)
    {
        $this->receivingReport = $receivingReport;
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receivingReports = $this->receivingReport->with('details.product')->latest()->get();
        return response()->json([
            'error' => false,
            'items' => $receivingReports
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
            $receivingReport = $this->receivingReport->create($data);
            foreach ($data['details'] as $product) {
                $receivingReport->details()->create($product);
                $this->product->find($product['product_id'])->increment('qty', $product['qty']);
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'item' => $receivingReport
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
     * @param  \App\Models\ReceivingReport  $receivingReport
     * @return \Illuminate\Http\Response
     */
    public function show(ReceivingReport $receivingReport)
    {
        return response()->json([
            'error' => false,
            'item' => $receivingReport->with('details')->get()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceivingReport  $receivingReport
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceivingReport $receivingReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceivingReport  $receivingReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReceivingReport $receivingReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceivingReport  $receivingReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReceivingReport $receivingReport)
    {
        DB::beginTransaction();
        try {
            foreach ($receivingReport->details as $product) {
                $this->product->find($product->product_id)->decrement('qty', $product->qty);
            }
            $receivingReport->details()->delete();
            $receivingReport->delete();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'item has been deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
