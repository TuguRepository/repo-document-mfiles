<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'category',
        'version',
        'path',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Get download requests for this document
     */
    public function downloadRequests()
    {
        return $this->hasMany(DownloadRequest::class);
    }
}

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'type',
        'download_request_id',
        'notes'
    ];

    /**
     * Get the download request for this activity
     */
    public function downloadRequest()
    {
        return $this->belongsTo(DownloadRequest::class);
    }
}
