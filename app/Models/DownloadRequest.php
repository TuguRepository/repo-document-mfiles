<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'document_id',
        'document_title',
        'document_number',
        'document_version',
        'reason',
        'other_reason',
        'notes',
        'status',
        'reference_number',
        'approval_note',
        'rejection_reason',
        'rejection_note',
        'approver_id',
        'download_token',
        'download_count',
        'expires_at',
        'ip_address',
        'user_agent',
        'usage_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_download_at' => 'datetime',
    ];

    /**
     * Get the user who made this download request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document being requested.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the admin user who approved or rejected this request.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Get the alternative document if one was suggested.
     */
    public function alternativeDocument()
    {
        return $this->belongsTo(Document::class, 'alternative_document_id');
    }

    /**
     * Scope a query to only include pending requests.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved requests.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected requests.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include active approved requests.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Check if this request is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this request is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if this request is rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if this request is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at !== null && $this->expires_at < now();
    }

    /**
     * Check if this request is still valid for downloading.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->isApproved() && !$this->isExpired();
    }
}
