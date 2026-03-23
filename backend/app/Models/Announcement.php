<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

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
    ];

    protected $casts = [
        'attachments' => 'array',
        'published_at' => 'datetime',
        'is_urgent' => 'boolean'
    ];

    protected $dates = ['deleted_at'];

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
    public function getFeaturedImageUrlAttribute()
{
    if ($this->featured_image) {
        return asset('storage/' . $this->featured_image);
    }
    return null;
}
    protected $appends = ['featured_image_url'];
}