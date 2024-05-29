<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';


    public function vendorList()
    {
        return Vendor::orderBy('vendor_name', 'asc')->get();
    }
}
