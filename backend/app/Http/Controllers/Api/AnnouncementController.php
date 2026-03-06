<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        $query = Announcement::with('creator');

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by urgent
        if ($request->has('urgent') && $request->urgent !== 'all') {
            $query->where('is_urgent', $request->urgent === 'urgent');
        }

        // Sort by latest first
        $query->latest();

        $announcements = $query->paginate($request->get('per_page', 10));

        // Get simple stats
        $stats = [
            'total' => Announcement::count(),
            'published' => Announcement::where('status', 'published')->count(),
            'draft' => Announcement::where('status', 'draft')->count(),
            'archived' => Announcement::where('status', 'archived')->count(),
            'urgent' => Announcement::where('is_urgent', true)->count(),
            'total_views' => Announcement::sum('views')
        ];

        return response()->json([
            'success' => true,
            'data' => $announcements,
            'stats' => $stats
        ]);
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:event,advisory,emergency,meeting,program',
            'status' => 'required|in:published,draft,archived',
            'is_urgent' => 'boolean',
            'featured_image' => 'nullable|image|max:2048|mimes:jpeg,jpg,png',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'status' => $request->status,
            'is_urgent' => $request->boolean('is_urgent'),
            'created_by' => auth()->id(),
            'published_at' => $request->status === 'published' ? now() : null
        ];

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('announcements', 'public');
            $data['featured_image'] = $path;
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
            $data['attachments'] = $attachments;
        }

        $announcement = Announcement::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully',
            'data' => $announcement->load('creator')
        ], 201);
    }

    /**
     * Display the specified announcement
     */
    public function show($id)
    {
        $announcement = Announcement::with('creator')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category' => 'sometimes|in:event,advisory,emergency,meeting,program',
            'status' => 'sometimes|in:published,draft,archived',
            'is_urgent' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['title', 'content', 'category', 'status', 'is_urgent']);

        // Update published_at if status changed to published
        if ($request->has('status') && 
            $request->status === 'published' && 
            $announcement->status !== 'published') {
            $data['published_at'] = now();
        }

        // If status is not published, remove published_at
        if ($request->has('status') && $request->status !== 'published') {
            $data['published_at'] = null;
        }

        $announcement->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully',
            'data' => $announcement->fresh('creator')
        ]);
    }

    /**
     * Remove the specified announcement
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Delete featured image
        if ($announcement->featured_image) {
            Storage::disk('public')->delete($announcement->featured_image);
        }

        // Delete attachments
        if ($announcement->attachments) {
            foreach ($announcement->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully'
        ]);
    }

    /**
     * Toggle urgent status
     */
    public function toggleUrgent($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_urgent' => !$announcement->is_urgent]);

        return response()->json([
            'success' => true,
            'message' => $announcement->is_urgent ? 'Marked as urgent' : 'Removed urgent status',
            'data' => $announcement
        ]);
    }

    /**
     * Bulk delete announcements
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $announcements = Announcement::whereIn('id', $request->ids)->get();
        
        foreach ($announcements as $announcement) {
            // Delete files
            if ($announcement->featured_image) {
                Storage::disk('public')->delete($announcement->featured_image);
            }
            if ($announcement->attachments) {
                foreach ($announcement->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
            $announcement->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' announcements deleted successfully'
        ]);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id',
            'status' => 'required|in:published,draft,archived'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = Announcement::whereIn('id', $request->ids)
            ->update([
                'status' => $request->status,
                'published_at' => $request->status === 'published' ? now() : null
            ]);

        return response()->json([
            'success' => true,
            'message' => $updated . ' announcements updated successfully'
        ]);
    }

    /**
     * Duplicate announcement
     */
    public function duplicate($id)
    {
        $announcement = Announcement::findOrFail($id);

        $newAnnouncement = $announcement->replicate();
        $newAnnouncement->title = $announcement->title . ' (Copy)';
        $newAnnouncement->status = 'draft';
        $newAnnouncement->published_at = null;
        $newAnnouncement->views = 0;
        $newAnnouncement->created_by = auth()->id();
        $newAnnouncement->save();

        return response()->json([
            'success' => true,
            'message' => 'Announcement duplicated successfully',
            'data' => $newAnnouncement
        ]);
    }
}