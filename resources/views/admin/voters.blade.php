@extends('layouts.app')

@section('page_title', 'Verification Logs')
@section('page_subtitle', 'Audit voter activity, captured identity photos, and panchayat-level participation without jumping between disconnected card styles.')

@section('content')
<div class="page-shell">
    <x-ui.page-header eyebrow="Audit and Monitoring" title="Voter verification logs" subtitle="This page now shares the same visual system as the dashboard so auditing feels like part of one product, not a separate interface.">
        <x-slot:actions>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i>
                Back to Dashboard
            </a>
        </x-slot:actions>
        <span class="info-chip">
            <i class="bi bi-clock-history"></i>
            Real-time monitoring
        </span>
    </x-ui.page-header>

    @if(count($panchayats) > 0)
        @foreach($panchayats as $panchayat)
            <section class="surface-card content-auto" data-reveal>
                <div class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">{{ $panchayat->name }}</h2>
                        <p class="panel-card__subtitle">Review verification state, captured photos, and activity recency for this panchayat.</p>
                    </div>
                    <div class="cluster">
                        <span class="info-chip">Total voters: {{ $panchayat->users->count() }}</span>
                        <span class="status-pill status-pill--success">
                            <i class="bi bi-check2-circle"></i>
                            Voted: {{ $panchayat->users->filter(function($user){ return $user->voter && $user->voter->has_voted; })->count() }}
                        </span>
                        @if($panchayat->users->count() > 0)
                            <form action="{{ route('admin.voters.deleteAll', $panchayat->id) }}" method="POST" class="prevent-double" data-pending-text="Deleting..." onsubmit="return confirm('Are you sure you want to delete all voters in this panchayat? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                    Delete All
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="p-4 pt-3">
                    <div class="table-shell">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Voter</th>
                                        <th>Status</th>
                                        <th>Photo</th>
                                        <th>Last Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $voters = $panchayat->users->filter(fn($user) => $user->voter); @endphp
                                    @forelse($voters as $user)
                                        <tr>
                                            <td data-label="Voter">
                                                <strong class="d-block">{{ $user->name }}</strong>
                                                <span class="helper-copy">ID: {{ $user->voter->voter_id_number }}</span>
                                            </td>
                                            <td data-label="Status">
                                                @if($user->voter->status === 'approved')
                                                    <span class="status-pill status-pill--success">
                                                        <i class="bi bi-patch-check-fill"></i>
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="status-pill status-pill--warning">{{ ucfirst($user->voter->status) }}</span>
                                                @endif

                                                <div class="mt-2">
                                                    @if($user->voter->has_voted)
                                                        <span class="info-chip">Vote record stored</span>
                                                    @else
                                                        <span class="helper-copy">Not voted yet</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td data-label="Photo">
                                                @if($user->voter->has_voted && $user->voter->captured_photo)
                                                    <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#photoModal{{ $user->id }}">
                                                        <img src="{{ Storage::url($user->voter->captured_photo) }}" alt="Verification photo" class="rounded-3 shadow-sm" width="72" height="72" loading="lazy">
                                                    </button>

                                                    <div class="modal fade" id="photoModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-dark text-white">
                                                                    <h5 class="modal-title">Verification image: {{ $user->name }}</h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <img src="{{ Storage::url($user->voter->captured_photo) }}" class="w-100" alt="Full verification photo">
                                                                </div>
                                                                <div class="modal-footer bg-light border-0 justify-content-between">
                                                                    <small class="text-muted">Captured on {{ $user->voter->updated_at->format('M d, Y h:i A') }}</small>
                                                                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="helper-copy">No captured image</span>
                                                @endif
                                            </td>
                                            <td data-label="Last Activity">
                                                <span class="helper-copy">
                                                    <i class="bi bi-calendar3"></i>
                                                    {{ $user->voter->updated_at->diffForHumans() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">No registered voters in this panchayat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    @else
        <div class="status-banner" data-reveal>
            <i class="bi bi-folder2-open text-muted"></i>
            <div>
                <strong class="d-block mb-1">No panchayat configurations found</strong>
                <span class="helper-copy">Add a panchayat first so monitoring and verification logs have somewhere to group voter activity.</span>
            </div>
        </div>
    @endif
</div>
@endsection
