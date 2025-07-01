<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormsTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'type_field_id',
        'field_name',
        'label',
        'size', // 0 - 12
        'required',
        'order',
        'visible',
        'editable',
        'filter_field',
        'modify_to',
        'info'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'table_id',
        'type_field_id'
    ];

    public function table() {
        return $this->belongsTo(Table::class);
    }
    public function type_field() {
        return $this->belongsTo(TypeField::class, 'type_field_id'); 
    }
}
