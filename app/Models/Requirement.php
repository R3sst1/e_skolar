<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'file_path',
        'file_type',
        'status',
        'remarks'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-slate-100'
        };
    }

    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-text',
            'jpg', 'jpeg', 'png' => 'image',
            default => 'file'
        };
    }

    public function getFileSize()
    {
        if ($this->file_path && \Storage::disk('public')->exists($this->file_path)) {
            $bytes = \Storage::disk('public')->size($this->file_path);
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                return $bytes . ' bytes';
            } elseif ($bytes == 1) {
                return '1 byte';
            } else {
                return '0 bytes';
            }
        }
        return 'N/A';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
