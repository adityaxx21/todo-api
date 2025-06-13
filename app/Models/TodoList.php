<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'assignee',
        'due_date',
        'status',
        'priority',
        'time_tracked',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status' => 'string',
        'priority' => 'string',
        'time_tracked' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($todo) {
            if (empty($todo->status)) {
                $todo->status = 'pending';
            }
        });
    }
}
