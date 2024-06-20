<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'table_id', 'name', 'datatype_id', 'long',
        'created_by', 'updated_by'
    ];
}
