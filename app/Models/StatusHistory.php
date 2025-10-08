<?php
// app/Models/StatusHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'grievance_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    public function grievance()
    {
        return $this->belongsTo(Grievance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}