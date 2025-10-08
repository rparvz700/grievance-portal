@extends('layouts.app')

@section('title', 'Submission Success')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-success">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h2 class="text-success mb-3">Grievance Submitted Successfully!</h2>
                    
                    <p class="lead">Your grievance has been received and will be reviewed by our team.</p>
                    
                    <div class="card bg-light my-4">
                        <div class="card-body">
                            <h5 class="mb-3">Your Reference Number</h5>
                            <div class="alert alert-info mb-0">
                                <h3 class="mb-0 font-monospace">{{ $grievance->reference_number }}</h3>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Please save this reference number for your records
                            </small>
                        </div>
                    </div>
                    
                    <div class="text-start mb-4">
                        <h6>What happens next?</h6>
                        <ol>
                            <li>Your grievance will be reviewed by an administrator</li>
                            <li>It will be categorized and assigned for investigation</li>
                            <li>The investigation team will look into the matter</li>
                            <li>Appropriate action will be taken based on findings</li>
                        </ol>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('grievances.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Submit Another Grievance
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Go to Home
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Note:</strong> This portal does not provide tracking functionality. 
                The reference number is for internal record-keeping only.
            </div>
        </div>
    </div>
</div>
@endsection