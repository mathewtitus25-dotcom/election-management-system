@extends('layouts.app')

@section('content')
<style>
    :root {
        --admin-blue-dark: #1e3a8a;
        --admin-blue-main: #2563eb;
        --admin-blue-light: #eff6ff;
        --admin-blue-border: #3b82f6;
        --admin-bg: #f8fafc;
        --voter-card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.01);
    }

    body {
        background-color: var(--admin-bg);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .voter-header-title {
        color: var(--admin-blue-dark);
        border-left: 5px solid var(--admin-blue-main);
        padding-left: 1rem;
    }

    .panchayat-section {
        background: white;
        border-radius: 12px;
        box-shadow: var(--voter-card-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .panchayat-header {
        background: var(--admin-blue-light);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panchayat-title {
        color: var(--admin-blue-dark);
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .voter-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .voter-table td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .captured-photo-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .captured-photo-thumb:hover {
        transform: scale(1.1);
    }

    .status-badge {
        padding: 0.4rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    .back-btn {
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s;
    }

    .back-btn:hover {
        color: var(--admin-blue-main);
    }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="back-btn mb-2">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <h2 class="voter-header-title mb-0">Voter Verification Logs</h2>
            <p class="text-muted ms-3">Review voter activity and captured identification photos by Panchayat.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-dark border p-2 shadow-sm">
                <i class="bi bi-clock-history me-1"></i> Real-time Monitoring
            </span>
        </div>
    </div>

    @forelse($panchayats as $panchayat)
        <div class="panchayat-section">
            <div class="panchayat-header">
                <h4 class="panchayat-title">
                    <i class="bi bi-geo-alt-fill text-primary"></i>
                    {{ $panchayat->name }}
                </h4>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-white text-primary border px-3 py-2">
                        Total Voters: {{ $panchayat->users->count() }}
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                        Voted: {{ $panchayat->users->filter(function($u){ return $u->voter && $u->voter->has_voted; })->count() }}
                    </span>
                    @if($panchayat->users->count() > 0)
                    <form action="{{ route('admin.voters.deleteAll', $panchayat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL voters in this panchayat? This action cannot be undone.');" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" title="Delete All Voters">
                            <i class="bi bi-trash"></i> Delete All
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table voter-table mb-0">
                    <thead>
                        <tr>
                            <th>Voter Name & ID</th>
                            <th>Status & Verification</th>
                            <th>Photos</th>
                            <th>Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($panchayat->users as $u)
                            @if($u->voter)
                            <tr>
                                <td>
                                    <div class="fw-bold fs-6">{{ $u->name }}</div>
                                    <div class="small text-muted">ID: {{ $u->voter->voter_id_number }}</div>
                                </td>
                                <td>
                                    @if($u->voter->status == 'approved')
                                        <span class="status-badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                            <i class="bi bi-patch-check-fill me-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="status-badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                            {{ ucfirst($u->voter->status) }}
                                        </span>
                                    @endif

                                    <div class="mt-2">
                                        @if($u->voter->has_voted)
                                            <span class="badge bg-primary rounded-pill px-3">
                                                <i class="bi bi-check2-circle me-1"></i>Vote Record Stored
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border rounded-pill px-3">Not Voted Yet</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($u->voter->has_voted && $u->voter->captured_photo)
                                            <div class="text-center">
                                                <small class="d-block text-muted mb-1 fw-bold" style="font-size: 0.65rem;">CAPTURED IMAGE</small>
                                                <img src="{{ asset('storage/' . $u->voter->captured_photo) }}" 
                                                     class="captured-photo-thumb" 
                                                     alt="Verification Photo"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#photoModal{{ $u->id }}">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Photo Modal -->
                                    @if($u->voter->captured_photo)
                                    <div class="modal fade" id="photoModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title">Verification Image: {{ $u->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <img src="{{ asset('storage/' . $u->voter->captured_photo) }}" class="w-100" alt="Full Verification Photo">
                                                </div>
                                                <div class="modal-footer bg-light border-0">
                                                    <div class="w-100 d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">Captured on: {{ $u->voter->updated_at->format('M d, Y h:i A') }}</small>
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i class="bi bi-calendar3 me-1"></i> 
                                        {{ $u->voter->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No registered voters in this panchayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="panchayat-section py-5 text-center">
            <div class="display-1 text-light mb-3"><i class="bi bi-folder2-open"></i></div>
            <h4 class="text-muted">No Panchayat configurations found.</h4>
        </div>
    @endforelse
</div>
@endsection
