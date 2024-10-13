<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module',
        'description',
        'icon',
        'name',
        'order',
        'status',
        'permission_id'
    ];

    protected $hidden = [];

    protected function casts(): array
    {
        return [
            // example
            // 'email_verified_at' => 'datetime',
        ];
    }
}
