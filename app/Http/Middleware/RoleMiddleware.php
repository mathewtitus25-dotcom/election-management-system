<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user = $request->user();
        $activeDashboard = session('active_dashboard');
        $authorized = false;

        foreach ($roles as $role) {
            if ($role === 'candidate') {
                // Must have candidate session AND exist in candidates table
                $exists = \App\Models\Candidate::where('user_id', $user->id)->exists();
                if ($exists && $activeDashboard === 'candidate') {
                    $authorized = true;
                    break;
                }
            } elseif ($role === 'voter') {
                // Voter routes require an actual voter profile.
                if ($user->voter) {
                    $authorized = true;
                    break;
                }
            } elseif ($role === 'admin' || $role === 'blo') {
                if ($user->role === $role) {
                    $authorized = true;
                    break;
                }
            }
        }

        if (! $authorized) {
            // Determine active home route to redirect unauthorized access
            $home = 'voter.dashboard';
            if ($activeDashboard === 'candidate') {
                $home = 'candidate.dashboard';
            } elseif ($activeDashboard === 'admin') {
                $home = 'admin.dashboard';
            } elseif ($activeDashboard === 'blo') {
                $home = 'blo.dashboard';
            }

            return redirect()->route($home)
                ->with('error', 'You are not authorized to access that page.');
        }

        return $next($request);
    }
}
