<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        return view('financial.index');
    }


    public function jurnalIndex()
    {
        return view('financial.transaction_journal');
    }


    // Tugas Ayang
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
}
