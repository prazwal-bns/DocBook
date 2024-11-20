<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        // if user is admin and he/she is trying to access patient or doctor's dashboard
        if ($user->role == 'admin' && in_array($role, ['patient', 'doctor'])) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect patient trying to access doctor dashboard
        if ($role == 'doctor' && $user->role == 'patient') {
            return redirect()->route('patient.dashboard');
        }

        // Redirect doctor trying to access patient dashboard
        if ($role == 'patient' && $user->role == 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        return $next($request);
    }
}
