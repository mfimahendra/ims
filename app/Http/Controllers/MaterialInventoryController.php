<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\MaterialInventory;
use Illuminate\Http\Request;

class MaterialInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        return view('warehouse.index_warehouse', [
            'title' => 'Warehouse'
        ]);
    }


    public function fetchInventory()
    {
        try {
            $materialInventories = MaterialInventory::orderBy('material_description', 'asc')->get();
            // $materialInventories = MaterialInventory::select('material_code', 'material_description', DB::raw('SUM(quantity) as quantity'), DB::raw('MAX(price) as price'))
            //     ->orderBy('material_description', 'asc')
            //     ->groupBy('material_code', 'material_description')
            //     ->get();
            $response = [
                'status' => 200,
                'message' => 'Material Inventory fetched successfully',
                'data' => $materialInventories
            ];
            return response()->json($response, 200);

        } catch (\Throwable $th) {
            $response = [
                'status' => 500,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function updateInventory(Request $request)
    {
        try {
            $material_code = $request->get('material_code');
            $material_description = $request->get('material_description');
            $quantity = $request->get('quantity');
            $price = $request->get('price');

            $materialInventory = MaterialInventory::where('material_code', $material_code)
                ->update([
                    'material_description' => $material_description,
                    'quantity' => $quantity,
                    'price' => $price
                ]);

            $response = [
                'status' => 200,
                'message' => 'Material Inventory updated successfully',
                'data' => $materialInventory
            ];
            return response()->json($response, 200);
            
        } catch (\Throwable $th) {
            $response = [
                'status' => 500,
                'message' => 'Failed to update Material Inventory',
                'data' => []
            ];
            return response()->json($response, 500);            
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialInventory $materialInventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialInventory $materialInventory)
    {
        //
    }        

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialInventory $materialInventory)
    {
        //
    }
}
