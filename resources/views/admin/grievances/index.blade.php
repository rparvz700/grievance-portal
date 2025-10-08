@extends('layouts.admin')

@section('title', 'All Grievances')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-list"></i> All Grievances</h2>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.grievances.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Reference or description..." value="{{ request('search') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_investigation" {{ request('status') == 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Grievances Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Attachments</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grievances as $grievance)
                    <tr>
                        <td><code>{{ $grievance->reference_number }}</code></td>
                        <td>{{ Str::limit($grievance->description, 60) }}</td>
                        <td>
                            @if($grievance->category)
                                <span class="badge bg-secondary">{{ $grievance->category->name }}</span>
                            @else
                                <span class="text-muted"><i>Uncategorized</i></span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $grievance->getStatusBadgeClass() }}">
                                {{ ucfirst(str_replace('_', ' ', $grievance->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($grievance->attachments->count() > 0)
                                <span class="badge bg-info">
                                    <i class="fas fa-paperclip"></i> {{ $grievance->attachments->count() }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $grievance->submitted_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <a href="{{ route('admin.grievances.show', $grievance) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            No grievances found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($grievances->hasPages())
        <div class="mt-3">
            {{ $grievances->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection