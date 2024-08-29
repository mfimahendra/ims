<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingSlip extends Model
{
    use HasFactory;

    protected $table = 'outgoing_slips';    

    protected $protected = ['id', 'created_at', 'updated_at'];
}
