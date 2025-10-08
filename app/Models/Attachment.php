<?php
// app/Models/Attachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'grievance_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function grievance()
    {
        return $this->belongsTo(Grievance::class);
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            Storage::disk('local')->delete($attachment->file_path);
        });
    }
}