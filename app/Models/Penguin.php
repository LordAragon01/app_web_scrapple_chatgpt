<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penguin extends Model
{
    use HasFactory;

    protected $table = 'penguin_customer';

    protected $fillable = ['counter_number', 'ip'];

}
