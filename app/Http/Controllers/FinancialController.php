<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{

    // Give only auth::role('owner can access this controller')
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('financial.index');
    }


    public function jurnalIndex()
    {
        $accounts = DB::table('financial_accounts')->get();

        return view('financial.transaction_journal',[
            'accounts' => $accounts
        ]);
    }
    
    public function fetchJournalData(Request $request)
    {
        try {
            $daterange = $request->get('date');
            // "11/23/2024 - 11/23/2024

            $datefrom = explode(' - ', $daterange)[0];
            $dateto = explode(' - ', $daterange)[1];

            $datefrom_formatted = date('Y-m-d', strtotime($datefrom));
            $dateto_formatted = date('Y-m-d', strtotime($dateto));

            $account_debit = $request->get('account_debit');
            $account_credit = $request->get('account_credit');            

            $transactions = DB::table('financial_transactions');
            
            if($account_debit != null){
                $transactions->where('debit', $account_debit);
            }
            if($account_credit != null){
                $transactions->where('credit', $account_credit);
            }

            $transactions->whereBetween('date', [$datefrom_formatted, $dateto_formatted]);
            $transactions = $transactions->get();

            $response = array(
                'status' => 'success',
                'data' => $transactions
            );

            return response()->json($response);
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'error',
                'message' => 'Failed to fetch journal data'
            );

            return response()->json($response);
        }
    }

    public function accountIndex()
    {
        $financial_accounts = DB::table('financial_accounts')->get();            

        return view('financial.financial_account',[
            'financial_accounts' => $financial_accounts
        ]);
    }    
    
    
    public function createNewTransaction(Request $request)
    {
        try {
            $date = $request->get('date');
            $description = $request->get('description');
            $remark = $request->get('remark');

            // account
            $debit = $request->get('debit');
            $credit = $request->get('credit');

            // amount
            $amount = $request->get('amount');

            // function new transaction on financial_transaction

            // update account balance here on financial_account 
            // debit + amount
            // credit - amount


        } catch (\Throwable $th) {
            
        }
    }

    public function fetchAccountData()
    {
        $financial_accounts = DB::table('financial_accounts')->get();            

        $response = array(
            'status' => 'success',
            'data' => $financial_accounts
        );

        return response()->json($response);
    }

    public function updateAccount(Request $request)
    {        
        try {

            $account_id = $request->get('account_id');
            $sn = $request->get('sn');
            $pos = $request->get('pos');
            $category = $request->get('category');
            $saldo = $request->get('saldo');

            $update = DB::table('financial_accounts')
            ->where('account_id', $account_id)
            ->update([
                'sn' => $sn,
                'pos' => $pos,
                'category' => $category,
                'saldo' => $saldo
            ]);

            $response = array(
                'status' => 'success',
                'message' => 'Account updated'
            );

            return response()->json($response);            
            
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'error',
                'message' => 'Failed to update account'
            );

            return response()->json($response);
            
        }
    }
}
