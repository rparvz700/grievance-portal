<?php
// app/Http/Controllers/Admin/GrievanceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use App\Models\Category;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GrievanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Grievance::with(['category', 'attachments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        // Search by reference number or description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('reference_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $grievances = $query->latest('submitted_at')->paginate(15);
        $categories = Category::active()->get();

        return view('admin.grievances.index', compact('grievances', 'categories'));
    }

    public function show(Grievance $grievance)
    {
        $grievance->load(['category', 'attachments', 'statusHistories.user']);
        $categories = Category::active()->get();
        
        return view('admin.grievances.show', compact('grievance', 'categories'));
    }

    public function update(Request $request, Grievance $grievance)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:pending,under_investigation,resolved,closed',
            'investigation_report' => 'nullable|string|max:10000',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $grievance->update($request->only([
            'category_id',
            'status',
            'investigation_report',
            'admin_notes'
        ]));

        return back()->with('success', 'Grievance updated successfully.');
    }

    public function downloadAttachment(Attachment $attachment)
    {
        if (!Storage::disk('local')->exists($attachment->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('local')->download(
            $attachment->file_path,
            $attachment->file_name
        );
    }

    public function destroy(Grievance $grievance)
    {
        // Only super admin can delete
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can delete grievances.');
        }

        $grievance->delete();
        return redirect()->route('admin.grievances.index')
            ->with('success', 'Grievance deleted successfully.');
    }
}