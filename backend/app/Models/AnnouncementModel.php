<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'status',
        'is_urgent',
        'created_by',
        'published_at',
        'featured_image',
        'attachments',
        'views'
    ];

    protected $casts = [
        'attachments' => 'array',
        'published_at' => 'datetime',
        'is_urgent' => 'boolean'
    ];

    // Category constants
    const CATEGORIES = [
        'event' => 'Events & Activities',
        'advisory' => 'Public Advisories',
        'emergency' => 'Emergency Alerts',
        'meeting' => 'Community Meetings',
        'program' => 'Government Programs'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor for excerpt
    public function getExcerptAttribute()
    {
        return substr(strip_tags($this->content), 0, 100) . '...';
    }
}