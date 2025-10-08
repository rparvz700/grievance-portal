@extends('layouts.admin')

@section('title', 'Grievance Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.grievances.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Grievance Details -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-alt"></i> Grievance Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Reference Number:</strong><br>
                        <code class="fs-5">{{ $grievance->reference_number }}</code>
                    </div>
                    <div class="col-md-6">
                        <strong>Submitted At:</strong><br>
                        {{ $grievance->submitted_at->format('d M Y, h:i A') }}
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="bg-light p-3 rounded mt-2">
                        {{ $grievance->description }}
                    </div>
                </div>
                
                <!-- Attachments -->
                @if($grievance->attachments->count() > 0)
                <div class="mb-3">
                    <strong>Attachments ({{ $grievance->attachments->count() }}):</strong>
                    <div class="mt-2">
                        @foreach($grievance->attachments as $attachment)
                        <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file"></i>
                                <strong>{{ $attachment->file_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $attachment->file_type }} - {{ $attachment->getFileSizeFormatted() }}
                                </small>
                            </div>
                            <a href="{{ route('admin.attachments.download', $attachment) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Investigation Report -->
        @if($grievance->investigation_report)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-search"></i> Investigation Report</h5>
            </div>
            <div class="card-body">
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($grievance->investigation_report)) !!}
                </div>
            </div>
        </div>
        @endif
        
        <!-- Admin Notes -->
        @if($grievance->admin_notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Admin Notes</h5>
            </div>
            <div class="card-body">
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($grievance->admin_notes)) !!}
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Update Form -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Update Grievance</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.grievances.update', $grievance) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $grievance->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $grievance->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_investigation" {{ $grievance->status == 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                            <option value="resolved" {{ $grievance->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $grievance->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Investigation Report</label>
                        <textarea name="investigation_report" class="form-control @error('investigation_report') is-invalid @enderror" rows="5" placeholder="Enter investigation findings...">{{ old('investigation_report', $grievance->investigation_report) }}</textarea>
                        @error('investigation_report')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="3" placeholder="Internal notes (not visible to public)...">{{ old('admin_notes', $grievance->admin_notes) }}</textarea>
                        @error('admin_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Update Grievance
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Status History -->
        @if($grievance->statusHistories->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Status History</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($grievance->statusHistories as $history)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <span class="badge {{ $history->new_status == 'resolved' ? 'bg-success' : 'bg-info' }}">
                                {{ ucfirst(str_replace('_', ' ', $history->new_status)) }}
                            </span>
                            <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                        </div>
                        @if($history->old_status)
                        <small class="text-muted">
                            Changed from: <strong>{{ ucfirst(str_replace('_', ' ', $history->old_status)) }}</strong>
                        </small><br>
                        @endif
                        @if($history->user)
                        <small class="text-muted">By: {{ $history->user->name }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Delete (Super Admin Only) -->
        @if(Auth::user()->isSuperAdmin())
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-trash"></i> Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Deleting this grievance will permanently remove all associated data and attachments.</p>
                <form action="{{ route('admin.grievances.destroy', $grievance) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this grievance? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Delete Grievance
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection