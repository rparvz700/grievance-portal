<?php
// app/Models/Grievance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Grievance extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'description',
        'category_id',
        'status',
        'investigation_report',
        'admin_notes',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($grievance) {
            if (empty($grievance->reference_number)) {
                $grievance->reference_number = self::generateReferenceNumber();
            }
            if (empty($grievance->submitted_at)) {
                $grievance->submitted_at = now();
            }
        });

        static::updating(function ($grievance) {
            if ($grievance->isDirty('status')) {
                StatusHistory::create([
                    'grievance_id' => $grievance->id,
                    'old_status' => $grievance->getOriginal('status'),
                    'new_status' => $grievance->status,
                    'changed_by' => auth()->id(),
                ]);
            }
        });
    }

    public static function generateReferenceNumber()
    {
        do {
            $reference = 'GRV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-danger',
            'under_investigation' => 'bg-warning',
            'resolved' => 'bg-success',
            'closed' => 'bg-secondary',
            default => 'bg-light',
        };
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}