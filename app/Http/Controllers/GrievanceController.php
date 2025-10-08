<?php
// app/Http/Controllers/GrievanceController.php

namespace App\Http\Controllers;

use App\Models\Grievance;
use App\Models\Attachment;
use App\Notifications\NewGrievanceNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GrievanceController extends Controller
{
    public function create()
    {
        return view('grievances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:20|max:5000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,mp4|max:10240', // 10MB
        ]);

        DB::beginTransaction();
        try {
            // Create grievance
            $grievance = Grievance::create([
                'description' => $request->description,
                'status' => 'pending',
            ]);

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('grievances/' . $grievance->id, 'local');
                    
                    Attachment::create([
                        'grievance_id' => $grievance->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            // Send notification to admins
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewGrievanceNotification($grievance));
            }

            return redirect()->route('grievances.success', $grievance->reference_number)
                ->with('success', 'Your grievance has been submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to submit grievance. Please try again.');
        }
    }

    public function success($referenceNumber)
    {
        $grievance = Grievance::where('reference_number', $referenceNumber)->firstOrFail();
        return view('grievances.success', compact('grievance'));
    }
}