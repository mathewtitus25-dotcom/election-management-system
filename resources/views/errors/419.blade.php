@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-danger">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Session Expired (419)</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <strong>Your session has expired or the page was open too long.</strong><br>
                    This is a security feature to protect your data.<br>
                    <ul class="mt-2">
                        <li>Try refreshing the page and submitting the form again.</li>
                        <li>Make sure cookies are enabled in your browser.</li>
                        <li>If you recently reset the app key or migrated the database, log out and start a new registration.</li>
                        <li>Do not use the back button or open multiple registration tabs.</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="btn btn-danger">Go to Registration</a>
            </div>
        </div>
    </div>
</div>
@endsection
