@extends('layouts.app')

@section('title', 'Submit Grievance')

<style>
    .card-header-custom {
        background-color: #39b0e8ff !important;
    }
    body {
        background: linear-gradient(to bottom, #e3d6dfff, #978b97ff);
        /* Optional: Set min-height to ensure the gradient covers the entire viewport height */
        min-height: 100vh; 
    }
    .card {
        box-shadow: 0 1.125rem 1.25rem rgba(0, 0, 0, 0.3) !important;
    }
</style>
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header card-header-custom text-white">
                    <h4 class="mb-0"><i class="fas fa-file-alt"></i> Submit your Grievance</h4>
                </div>
                <div class="card-body bg-border">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Anonymous Submission:</strong> Your identity will remain completely confidential. 
                        After submission, you'll receive a reference number for your grievance.
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('grievances.store') }}" method="POST" enctype="multipart/form-data" id="grievanceForm">
                        @csrf

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Grievance Description <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="8" 
                                placeholder="Please describe your grievance in detail (minimum 20 characters)..."
                                required>{{ old('description') }}</textarea>
                            <div class="form-text">Minimum 20 characters required</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="attachments" class="form-label">
                                Attachments (Optional)
                            </label>
                            <input 
                                class="form-control @error('attachments.*') is-invalid @enderror" 
                                type="file" 
                                id="attachments" 
                                name="attachments[]" 
                                multiple
                                accept=".jpg,.jpeg,.png,.pdf,.mp4">
                            <div class="form-text">
                                <i class="fas fa-paperclip"></i> You can upload up to 5 files (Images, PDF, or Video). Max size: 10MB per file.
                            </div>
                            @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="fileList" class="mt-2"></div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Important:</strong> Please review your submission carefully. Once submitted, you cannot edit the grievance.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Submit Grievance
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-shield-alt"></i> Privacy & Confidentiality</h5>
                    <ul class="mb-0">
                        <li>Your submission is completely anonymous</li>
                        <li>No personal information is collected</li>
                        <li>All data is encrypted and securely stored</li>
                        <li>Only authorized HR administrators can view submissions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('attachments').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    
    if (this.files.length > 5) {
        alert('Maximum 5 files allowed');
        this.value = '';
        return;
    }
    
    Array.from(this.files).forEach((file, index) => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        if (fileSize > 10) {
            alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
            this.value = '';
            fileList.innerHTML = '';
            return;
        }
        
        const div = document.createElement('div');
        div.className = 'badge bg-secondary me-2 mb-2';
        div.innerHTML = `<i class="fas fa-file"></i> ${file.name} (${fileSize} MB)`;
        fileList.appendChild(div);
    });
});
</script>
@endpush
@endsection