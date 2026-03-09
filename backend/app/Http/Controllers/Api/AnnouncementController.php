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
     * Display a listing of announcements (excluding trashed)
     */
    public function index(Request $request)
    {
        return response()
            ->json($this->getAnnouncementData($request))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function getAnnouncementData(Request $request)
    {
        // Only get non-trashed announcements
        $query = Announcement::with('creator')->whereNull('deleted_at');

        // Search
        if ($request->has('search') && !empty($request->search)) {
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

        // Get stats including trashed count (views removed)
        $stats = [
            'total' => Announcement::whereNull('deleted_at')->count(),
            'published' => Announcement::whereNull('deleted_at')->where('status', 'published')->count(),
            'draft' => Announcement::whereNull('deleted_at')->where('status', 'draft')->count(),
            'archived' => Announcement::whereNull('deleted_at')->where('status', 'archived')->count(),
            'urgent' => Announcement::whereNull('deleted_at')->where('is_urgent', true)->count(),
            'trashed' => Announcement::onlyTrashed()->count()
        ];

        return [
            'success' => true,
            'data' => $announcements,
            'stats' => $stats
        ];
    }

    /**
     * Get trashed announcements
     */
    public function trashed(Request $request)
    {
        $query = Announcement::onlyTrashed()->with('creator');

        // Search in trashed
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category in trashed
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $announcements = $query->latest('deleted_at')->paginate($request->get('per_page', 10));

        // Get stats including trashed count (views removed)
        $stats = [
            'total' => Announcement::whereNull('deleted_at')->count(),
            'published' => Announcement::whereNull('deleted_at')->where('status', 'published')->count(),
            'draft' => Announcement::whereNull('deleted_at')->where('status', 'draft')->count(),
            'archived' => Announcement::whereNull('deleted_at')->where('status', 'archived')->count(),
            'urgent' => Announcement::whereNull('deleted_at')->where('is_urgent', true)->count(),
            'trashed' => Announcement::onlyTrashed()->count()
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
     * Soft delete (move to trash)
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Soft delete only - don't delete files yet
        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement moved to trash successfully'
        ]);
    }

    /**
     * Restore from trash
     */
    public function restore($id)
    {
        try {
            $announcement = Announcement::withTrashed()->findOrFail($id);
            
            if (!$announcement->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This announcement is not in trash'
                ], 400);
            }
            
            $announcement->restore();

            return response()->json([
                'success' => true,
                'message' => 'Announcement restored successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error restoring announcement'
            ], 500);
        }
    }

    /**
     * Permanently delete from trash
     */
    public function forceDelete($id)
    {
        try {
            $announcement = Announcement::withTrashed()->findOrFail($id);
            
            if (!$announcement->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please move to trash first before permanent deletion'
                ], 400);
            }
            
            // Delete featured image
            if ($announcement->featured_image) {
                Storage::disk('public')->delete($announcement->featured_image);
            }

            // Delete attachments
            if ($announcement->attachments) {
                $attachments = is_array($announcement->attachments) 
                    ? $announcement->attachments 
                    : json_decode($announcement->attachments, true);
                
                foreach ($attachments as $attachment) {
                    if (is_array($attachment) && isset($attachment['path'])) {
                        Storage::disk('public')->delete($attachment['path']);
                    }
                }
            }

            // Permanently delete
            $announcement->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Announcement permanently deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting announcement'
            ], 500);
        }
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
     * Bulk soft delete (move to trash)
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

        // Soft delete only
        Announcement::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' announcements moved to trash successfully'
        ]);
    }

    /**
     * Bulk restore from trash
     */
    public function bulkRestore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $restored = 0;
        foreach ($request->ids as $id) {
            $announcement = Announcement::withTrashed()->find($id);
            if ($announcement && $announcement->trashed()) {
                $announcement->restore();
                $restored++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $restored . ' announcements restored successfully'
        ]);
    }

    /**
     * Bulk permanently delete
     */
    public function bulkForceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $deleted = 0;
        foreach ($request->ids as $id) {
            $announcement = Announcement::withTrashed()->find($id);
            if ($announcement && $announcement->trashed()) {
                // Delete files
                if ($announcement->featured_image) {
                    Storage::disk('public')->delete($announcement->featured_image);
                }
                if ($announcement->attachments) {
                    $attachments = is_array($announcement->attachments) 
                        ? $announcement->attachments 
                        : json_decode($announcement->attachments, true);
                    
                    foreach ($attachments as $attachment) {
                        if (is_array($attachment) && isset($attachment['path'])) {
                            Storage::disk('public')->delete($attachment['path']);
                        }
                    }
                }
                $announcement->forceDelete();
                $deleted++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $deleted . ' announcements permanently deleted'
        ]);
    }

    /**
     * Empty trash (permanently delete all)
     */
    public function emptyTrash()
    {
        $announcements = Announcement::onlyTrashed()->get();
        $count = $announcements->count();
        
        foreach ($announcements as $announcement) {
            // Delete featured image
            if ($announcement->featured_image) {
                Storage::disk('public')->delete($announcement->featured_image);
            }
            // Delete attachments
            if ($announcement->attachments) {
                $attachments = is_array($announcement->attachments) 
                    ? $announcement->attachments 
                    : json_decode($announcement->attachments, true);
                
                foreach ($attachments as $attachment) {
                    if (is_array($attachment) && isset($attachment['path'])) {
                        Storage::disk('public')->delete($attachment['path']);
                    }
                }
            }
        }

        // Permanently delete all
        Announcement::onlyTrashed()->forceDelete();

        return response()->json([
            'success' => true,
            'message' => $count . ' items permanently deleted from trash'
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
            ->whereNull('deleted_at') // Only update non-trashed
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
        // 'views' removed
        $newAnnouncement->created_by = auth()->id();
        $newAnnouncement->save();

        return response()->json([
            'success' => true,
            'message' => 'Announcement duplicated successfully',
            'data' => $newAnnouncement
        ]);
    }
}