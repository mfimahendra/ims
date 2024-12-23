<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\MaterialCategory;
use App\Models\MaterialInventory;
use App\Models\MaterialIncoming;
use App\Models\MaterialTransactionLogs;
use App\Models\OutgoingHistories;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Additional;
use App\Models\CodeGenerator;
use App\Models\OutgoingSlip;
use App\Models\FinancialAccounts;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function incomingIndex()
    {        
        $assets = FinancialAccounts::select('account_id')
        ->where('category', 'Aset')
        ->get();

        $source = FinancialAccounts::select('account_id')
        ->whereIn('category', ['Kas','Hutang', 'Hutang & Piutang'])
        ->get();

        return view('transaction.incoming',[            
            'assets' => $assets,
            'source' => $source
        ]);
    }

    public function outgoingIndex()
    {
        $assets = FinancialAccounts::select('account_id')
        ->where('category', 'Aset')
        ->get();

        $source = FinancialAccounts::select('account_id')
        ->whereIn('category', ['Kas','Hutang', 'Hutang & Piutang'])
        ->get();
                
        return view('transaction.outgoing',[
            'assets' => $assets,
            'source' => $source
        ]);
    }

    public function transactionLogsIndex()
    {
        $materials = MaterialInventory::select('material_code', 'material_description', DB::raw('SUM(quantity) as quantity'), DB::raw('MAX(price) as price'))
            ->orderBy('material_description', 'asc')
            ->groupBy('material_code', 'material_description')
            ->get();

        return view('transaction.logs',[
            'materials' => $materials
        ]);
    }

    public function fetchIncomingData()
    {
        try {
            $vendor = Vendor::orderBy('vendor_name', 'asc')->get();
            $materialInventories = MaterialInventory::select('material_code', 'material_description', DB::raw('SUM(quantity) as quantity'), DB::raw('MAX(price) as price'))
                ->orderBy('material_description', 'asc')
                ->groupBy('material_code', 'material_description')
                ->get();

            $materialCategories = MaterialCategory::orderBy('material_category', 'asc')->get();

            $response = [
                'status' => 200,
                'message' => 'Data fetched successfully',
                'inventories' => $materialInventories,
                'vendor' => $vendor,
                'categories' => $materialCategories
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

    public function fetchOutgoingData()
    {
        try {
            $materialInventories = MaterialInventory::select('material_code', 'material_description', 'material_category', DB::raw('SUM(quantity) as quantity'), DB::raw('MAX(price) as price'))
                ->orderBy('material_description', 'asc')
                ->groupBy('material_code', 'material_description', 'material_category')
                ->where('quantity', '>', 0)
                ->get();

            $vehicle = Vehicle::select('vehicle_code', 'vehicle_description','vehicle_id', 'drivers.driver_code', 'drivers.remark', 'drivers.driver_name')
                ->leftJoin('drivers', 'vehicles.driver_id', '=', 'drivers.driver_code')
                ->orderBy('vehicle_code', 'asc')
                ->get();

            $drivers = Driver::select('driver_code', 'driver_name','remark')
                ->orderBy('driver_name', 'asc')
                ->get();

            $additionals = Additional::select('additional_name')
            ->orderBy('additional_name', 'asc')
            ->get();

            $response = [
                'status' => 200,
                'message' => 'Data fetched successfully',
                'inventories' => $materialInventories,
                'vehicle' => $vehicle,
                'driver' => $drivers,
                'additional' => $additionals
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

    public function submitIncomingData(Request $request)
    {        
        DB::beginTransaction();
        try {
            $incoming_vendor = $request->get('vendor');
            $incoming_date = $request->get('date');
            $cart = $request->get('cart');
            $account_debit = $request->get('account_debit');
            $account_credit = $request->get('account_credit');

            $validator = \Validator::make($request->all(), [
                'vendor' => 'required',
                'date' => 'required',
                'cart' => 'required',
            ]);

            if($validator->fails()){
                $response = [
                    'status' => 500,
                    'message' => 'Data tidak lengkap',
                ];
                return response()->json($response, 500);
            }

                        
            $cart = json_decode($cart, true);

            $slip_code = CodeGenerator::where('remark', 'invoice')->first();            
            $slip_code = $slip_code->prefix . str_pad($slip_code->index, $slip_code->length, '0', STR_PAD_LEFT);            

            foreach ($cart as $key => $value) {
                $material_description = $value['product'];
                $material_category = $value['product_category'];
                $uom = $value['uom'];
                $quantity = $value['qty'];
                $price = $value['price'];
                $payment = $value['payment'];
                $in_vendor = $incoming_vendor;
                $date_in = strtotime($incoming_date);

                // material_incomings
                $materialIncoming = new MaterialIncoming();                

                $materialIncoming->slip_code = $slip_code;

                
                // material_code find from material_inventory
                $mt_code = MaterialInventory::where('material_description', $material_description)->first();
                if($mt_code){
                    $materialIncoming->material_code = $mt_code->material_code;
                    $material_code = $mt_code->material_code;
                } else {
                    $mt_code = CodeGenerator::where('remark', 'material')->first();
                    $material_code = $mt_code->prefix . str_pad($mt_code->index, $mt_code->length, '0', STR_PAD_LEFT);
                    $materialIncoming->material_code = $material_code;
                }

                $materialIncoming->material_description = $material_description;
                $materialIncoming->material_category = $material_category;

                // if material category not on material_categories table insert into it
                $check_material_category = MaterialCategory::where('material_category', $material_category)->first();
                if(!$check_material_category){
                    $materialCategory = new MaterialCategory();
                    $materialCategory->material_category = $material_category;
                    $materialCategory->created_at = date('Y-m-d H:i:s');
                    $materialCategory->updated_at = date('Y-m-d H:i:s');
                    $materialCategory->save();
                }

                $materialIncoming->uom = $uom;
                $materialIncoming->quantity = $quantity;
                $materialIncoming->price = $price;
                $materialIncoming->vendor = $in_vendor;
                $materialIncoming->payment = $payment;
                $materialIncoming->date_in = date('Y-m-d', $date_in);

                // account
                $materialIncoming->account_credit = $account_credit;
                $materialIncoming->account_debit = $account_debit;

                $materialIncoming->created_at = date('Y-m-d H:i:s');
                $materialIncoming->updated_at = date('Y-m-d H:i:s');
                $materialIncoming->save();

                // Inventory Master
                $inventory = MaterialInventory::where('material_description', $material_description)->first();
                if($inventory){                                    
                    // update price if different
                    // if($inventory->price != $price){
                    //     $inventory->price = $price;
                    // }                    
                    // New Item with new price
                    if($inventory->price != $price){
                        $inventory = new MaterialInventory();
                        $inventory->material_code = $material_code;
                        $inventory->material_description = $material_description;
                        $inventory->material_category = $material_category;
                        $inventory->uom = $uom;
                        $inventory->quantity = $quantity;
                        $inventory->price = $price;
                        $inventory->created_at = date('Y-m-d H:i:s');
                        $inventory->updated_at = date('Y-m-d H:i:s');
                        $inventory->save();                        
                    } else {
                        $inventory->quantity = $inventory->quantity + $quantity;
                        $inventory->material_category = $material_category;
                        $inventory->uom = $uom;
                        $inventory->updated_at = date('Y-m-d H:i:s');
                        $inventory->save();
                    }

                } else {
                    $inventory = new MaterialInventory();
                    $inventory->material_code = $material_code;
                    $inventory->material_description = $material_description;
                    $inventory->material_category = $material_category;
                    $inventory->quantity = $quantity;
                    $inventory->price = $price;
                    $inventory->created_at = date('Y-m-d H:i:s');
                    $inventory->updated_at = date('Y-m-d H:i:s');
                    $inventory->save();

                    // update code generator
                    $mt_code->index = $mt_code->index + 1;
                    $mt_code->save();
                }
                
                
                // make conditional if vendor new or existing
                $check_vendor = Vendor::where('vendor_name', $in_vendor)->first();
                if(!$check_vendor){
                    $vendor = new Vendor();

                    // vendor_code generator
                    $get_vendor_code = CodeGenerator::where('remark', 'vendor')->first();
                    $vendor_code = $get_vendor_code->prefix . str_pad($get_vendor_code->index, $get_vendor_code->length, '0', STR_PAD_LEFT);
                    $vendor->vendor_id = $vendor_code;
                    $vendor->vendor_name = $incoming_vendor;
                    $vendor->created_at = date('Y-m-d H:i:s');
                    $vendor->updated_at = date('Y-m-d H:i:s');
                    $vendor->save();

                    // update code generator
                    $get_vendor_code->index = $get_vendor_code->index + 1;
                    $get_vendor_code->save();
                }

                // insert to material_transaction_logs
                $materialTransactionLogs = new MaterialTransactionLogs();
                $materialTransactionLogs->slip = $slip_code;
                $materialTransactionLogs->material_code = $material_code;
                $materialTransactionLogs->material_description = $material_description;                
                $materialTransactionLogs->uom = $uom;
                $materialTransactionLogs->quantity = $quantity;
                $materialTransactionLogs->price = $price;
                $materialTransactionLogs->category = 'incoming';
                $materialTransactionLogs->created_at = date('Y-m-d H:i:s');
                $materialTransactionLogs->updated_at = date('Y-m-d H:i:s');
                $materialTransactionLogs->save();

            }

            // update code generator
            
            $slip_code = CodeGenerator::where('remark', 'invoice')->first();
            $slip_code->index = $slip_code->index + 1;
            $slip_code->save();

            DB::commit();

            $response = [
                'status' => 200,
                'message' => 'Data Berhasil Disimpan',
            ];

            return response()->json($response, 200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'status' => 500,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function submitOutgoingData(Request $request)
    {                                
        $cart = $request->get('cart');
        $additional_cart = $request->get('additional_cart');

        $category = $request->get('category');
        $vehicle = $request->get('vehicle');
        $driver = $request->get('driver');
        $account_id = $request->get('account');                

        $cart = json_decode($cart, true);
        $additional_cart = json_decode($additional_cart, true);
        
        // validator if category is empty, vehicle is empty, driver is empty, product is empty, qty is empty
        if(count($cart) < 1){
            $response = [
                'status' => 500,
                'message' => 'Data tidak boleh kosong',
            ];
            return response()->json($response, 500);
        }        
        
        $slip = $request->get('slip');
        DB::beginTransaction();    
        try {
            if($slip != null || $slip != ''){
                $find_slip = OutgoingSlip::where('outgoing_id', $slip)->first();
                $slip_code = $slip;

                // clear outgoing histories
                // $clearOutgoing = DB::table('outgoing_histories')->where('outgoing_id', $slip)->delete();
            } else {
                $slip_code = CodeGenerator::where('remark', 'outgoing')->first();                
                $slip_code = $slip_code->prefix . str_pad($slip_code->index, $slip_code->length, '0', STR_PAD_LEFT);                
            }


            foreach ($cart as $key => $value) {                
                $material_description = $value['product'];
                $quantity = $value['qty'];
                $type = $value['outgoing_type'];                

                // Insert to outgoing_histories
                // $outgoingHistories = new OutgoingHistories();

                // $outgoingHistories->outgoing_id = $slip_code;
                // $outgoingHistories->material_category = $type;
                // $outgoingHistories->type = 'Barang';
                // $outgoingHistories->description = $material_description;                            
                // $outgoingHistories->quantity = $quantity;

                // $outgoingHistories->created_by = Auth::user()->name;
                // $outgoingHistories->created_at = date('Y-m-d H:i:s');
                // $outgoingHistories->updated_at = date('Y-m-d H:i:s');
                // $outgoingHistories->save();

                // Insert to outgoing_histories
                $insertOutgoingHistories = DB::table('outgoing_histories')->updateOrInsert(
                    ['outgoing_id' => $slip_code, 'description' => $material_description],
                    [
                        'outgoing_id' => $slip_code,
                        'material_category' => $type,
                        'type' => 'Barang',
                        'description' => $material_description,
                        'quantity' => $quantity,
                        'created_by' => Auth::user()->name,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }

            foreach ($additional_cart as $key => $value) {                
                $additional_name = $value['additional'];
                $quantity = $value['qty'];
                $price = $value['price'];

                // Insert to outgoing_histories
                // $outgoingHistories = new OutgoingHistories();

                // $outgoingHistories->outgoing_id = $slip_code;                
                // $outgoingHistories->type = 'Tambahan';
                // $outgoingHistories->description = $additional_name;
                // $outgoingHistories->quantity = $quantity;
                // $outgoingHistories->price = $price;
                // $outgoingHistories->created_by = Auth::user()->name;
                // $outgoingHistories->created_at = date('Y-m-d H:i:s');
                // $outgoingHistories->updated_at = date('Y-m-d H:i:s');
                // $outgoingHistories->save();

                // Insert to outgoing_histories
                $insertOutgoingHistories = DB::table('outgoing_histories')->updateOrInsert(
                    ['outgoing_id' => $slip_code, 'description' => $additional_name],
                    [
                        'outgoing_id' => $slip_code,
                        'type' => 'Tambahan',
                        'description' => $additional_name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'created_by' => Auth::user()->name,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
                        
            DB::table('outgoing_slips')->updateOrInsert(
                ['outgoing_id' => $slip_code],
                [
                    'status' => 0,
                    'category' => $category,
                    'vehicle_id' => $vehicle,
                    'driver_id' => $driver,
                    'account_id' => $account_id,
                    'created_by' => Auth::user()->name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );


            // update code generator
            if(!$slip){
                $slip_code = DB::table('code_generators')->where('remark', 'outgoing')->first();
                DB::table('code_generators')->where('remark', 'outgoing')->update(['index' => $slip_code->index + 1]);

                $selip = $slip_code->prefix . str_pad($slip_code->index, $slip_code->length, '0', STR_PAD_LEFT);
            } else {
                $selip = $slip;
            }                    

            DB::commit();

            $response = [
                'status' => 200,
                'message' => 'Data Berhasil Disimpan dengan nomor slip ' . $selip,                
            ];

            return response()->json($response, 200);            
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'status' => 500,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);            
        }
    }

    public function commitOutgoingData(Request $request)
    {
        $slip = $request->get('slip');                        

        $cart = OutgoingHistories::where('outgoing_id', $slip)->where('type', 'Barang')->get();        

        if(count($cart) < 1){
            $response = [
                'status' => 500,
                'message' => 'Data tidak boleh kosong',
            ];
            return response()->json($response, 500);
        }
        
        try {            
            foreach ($cart as $key => $value) {
                $material_description = $value['description'];
                $quantity = $value['quantity'];                

                // Update from Master Material Inventory
                // find from material_inventory, select lowest price, then if quantity < 0, subtract from the highest price then delete the lowest price from inventory
                $inventory = MaterialInventory::where('material_description', $material_description)->orderBy('updated_at', 'asc')->first();

                if($inventory){
                    $current_qty = $inventory->quantity;
                    $old_qty = $inventory->quantity;
                    $old_price = $inventory->price;
                    $inventory->quantity = $inventory->quantity - $quantity;
                    $inventory->save();

                    // update price to Outgoing Histories model
                    DB::table('outgoing_histories')
                    ->where('outgoing_id', $slip)
                    ->where('description', $material_description)
                    ->update([
                        'price' => $inventory->price,
                        'updated_at' => date('Y-m-d H:i:s')
                        ]);

                } else {                    
                    $response = [
                        'status' => 500,
                        'message' => 'Material tidak ditemukan',
                    ];
                    return response()->json($response, 500);
                }

                // check if quantity < 0
                if($inventory->quantity < 0){
                    $current_qty = $inventory->quantity;                    

                    // softdelete the lowest price from inventory
                    $inventory->delete();

                    // inventory with highest price to auto subtract
                    $inventory = MaterialInventory::where('material_description', $material_description)->orderBy('updated_at', 'desc')->first();
                    $inventory->quantity = $inventory->quantity + $current_qty;
                    $inventory->save();

                    // update price to Outgoing Histories model as addition price
                    DB::table('outgoing_histories')
                    ->where('outgoing_id', $slip)
                    ->where('description', $material_description)
                    ->update([
                        'price' => $inventory->price,
                        'remark' => 'cost changes',
                        'updated_at' => date('Y-m-d H:i:s')
                        ]);
                }

                // Insert to material_transaction_logs            
                // count old_qty to define price for outgoing transaction logs
                if($current_qty < 0){
                    $materialTransactionLogs = new MaterialTransactionLogs();
                    $materialTransactionLogs->slip = $slip;
                    $materialTransactionLogs->material_code = $inventory->material_code;
                    $materialTransactionLogs->material_description = $material_description;
                    $materialTransactionLogs->quantity = $quantity + $current_qty;
                    $materialTransactionLogs->price = $old_price;
                    $materialTransactionLogs->category = 'outgoing';
                    $materialTransactionLogs->created_at = date('Y-m-d H:i:s');
                    $materialTransactionLogs->updated_at = date('Y-m-d H:i:s');
                    $materialTransactionLogs->save();                    
                }

                $materialTransactionLogs = new MaterialTransactionLogs();
                $materialTransactionLogs->slip = $slip;
                $materialTransactionLogs->material_code = $inventory->material_code;
                $materialTransactionLogs->material_description = $material_description;

                if($current_qty < 0){
                    $materialTransactionLogs->quantity = $quantity - $old_qty;
                } else {
                    $materialTransactionLogs->quantity = $quantity;
                }
                
                $materialTransactionLogs->price = $inventory->price;
                $materialTransactionLogs->category = 'outgoing';
                $materialTransactionLogs->created_at = date('Y-m-d H:i:s');
                $materialTransactionLogs->updated_at = date('Y-m-d H:i:s');
                $materialTransactionLogs->save();
            }
            
            // update outgoing_slips status to 1
            $outgoingSlip = OutgoingSlip::where('outgoing_id', $slip)->first();
            $outgoingSlip->status = 1;
            $outgoingSlip->updated_at = date('Y-m-d H:i:s');
            $outgoingSlip->save();



            $response = [
                'status' => 200,
                'message' => 'Data submitted successfully',
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

    public function deleteOutgoingData(Request $request)
    {
        try {
            $slip = $request->get('slip');
            $cart = OutgoingHistories::where('outgoing_id', $slip)->get();
            $slip = OutgoingSlip::where('outgoing_id', $slip)->first();

            foreach ($cart as $key => $value) {
                $material_description = $value['product'];
                $quantity = $value['qty'];
                $type = $value['outgoing_type'];

                // Update from Master Material Inventory                
                $inventory = MaterialInventory::where('material_description', $material_description)->orderBy('updated_at', 'asc')->first();
                if($inventory){
                    $inventory->quantity = $inventory->quantity + $quantity;
                    $inventory->save();
                } else {
                    $response = [
                        'status' => 500,
                        'message' => 'Material Inventory not found',
                    ];
                    return response()->json($response, 500);
                }

                // Insert to material_transaction_logs            
                // count old_qty to define price for outgoing transaction logs
                if($current_qty < 0){
                    $materialTransactionLogs = new MaterialTransactionLogs();
                    $materialTransactionLogs->material_code = $inventory->material_code;
                    $materialTransactionLogs->material_description = $material_description;
                    $materialTransactionLogs->quantity = $quantity + $current_qty;
                    $materialTransactionLogs->price = $old_price;
                    $materialTransactionLogs->category = 'incoming';
                    $materialTransactionLogs->created_at = date('Y-m-d H:i:s');
                    $materialTransactionLogs->updated_at = date('Y-m-d H:i:s');
                    $materialTransactionLogs->save();                    
                }

                $materialTransactionLogs = new MaterialTransactionLogs();
                $materialTransactionLogs->material_code = $inventory->material_code;
                $materialTransactionLogs->material_description = $material_description;

                if($current_qty < 0){
                    $materialTransactionLogs->quantity = $quantity - $old_qty;
                } else {
                    $materialTransactionLogs->quantity = $quantity;
                }
                
                $material = MaterialInventory::where('material_description', $material_description)->first();

                $materialTransactionLogs->price = $material->price;

                $materialTransactionLogs->category = 'incoming';
                $materialTransactionLogs->created_at = date('Y-m-d H:i:s');
                $materialTransactionLogs->updated_at = date('Y-m-d H:i:s');
                $materialTransactionLogs->save();
            }

            $slip->delete();

            $response = [
                'status' => 200,
                'message' => 'Data deleted successfully',
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

    public function fetchTransactionLogs(Request $request)
    {
        try {
            $category = $request->get('category');                    
            $material = $request->get('material');                        

            $date = $request->get('date');

            $transactionLogs = MaterialTransactionLogs::select('material_code', 'material_description', 'quantity', 'price', 'category', DB::raw('DATE_FORMAT(created_at, "%d-%M-%Y") as date'));

            if($category != 'all'){
                $transactionLogs = $transactionLogs->where('category', $category);
            }            
                        
            if(!empty($material)){                
                $material = explode(',', $material);
                // $transactionLogs = $transactionLogs->whereIn('material_code', $material);                                
                if(count($material) > 1){
                    $transactionLogs = $transactionLogs->whereIn('material_description', $material);
                } else {
                    $transactionLogs = $transactionLogs->where('material_description', $material[0]);
                }
            }
            
            if(!empty($date)){
                $date = explode(' - ', $date);
                $start_date = date('Y-m-d', strtotime($date[0]));
                $end_date = date('Y-m-d', strtotime($date[1]));

                $transactionLogs = $transactionLogs->whereBetween('created_at', [$start_date, $end_date]);
            }
                // $start_date = date('Y-m-01');
                // $end_date = date('Y-m-t');

                // $transactionLogs = $transactionLogs->whereBetween('created_at', [$start_date, $end_date]);
            // }

            $transactionLogs = $transactionLogs->orderBy('created_at', 'desc')->get();                            

            $response = [
                'status' => 200,
                'message' => 'Data fetched successfully',
                'logs' => $transactionLogs
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

    public function fetchUncompleted()
    {
        try {
            $outgoingSlips = OutgoingSlip::select('*', DB::raw('DATE_FORMAT(created_at, "%d-%M-%Y") as date'))
                ->where('status', 0)                                
                ->get();
            $outgoingHistories = OutgoingHistories::whereIn('outgoing_id', $outgoingSlips->pluck('outgoing_id'))->get();

            // foreach ($outgoingHistories as $outgoingHistory) {
            //     $outgoingHistory['history_id_with_date'] =date('Ymd', strtotime($outgoingHistory['created_at'])) . $outgoingHistory->outgoing_id; 
            // }
            foreach ($outgoingSlips as $outgoingSlip) {
                $outgoingSlip['history_id_with_date'] =date('Ymd', strtotime($outgoingSlip['created_at'])) . $outgoingSlip['outgoing_id'];
            }
                
            $slip = $outgoingSlips->pluck('history_id_with_date')->unique();

            // re array $slip
            $slip = $slip->values()->all();

            $response = [
                'status' => 200,
                'message' => 'Data fetched successfully',
                'slip' => $slip,
                'slipItems' => $outgoingSlips,
                'histories' => $outgoingHistories,
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
}
