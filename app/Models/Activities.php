// App/Models/Activity.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'download_request_id',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloadRequest()
    {
        return $this->belongsTo(DownloadRequest::class);
    }
}
