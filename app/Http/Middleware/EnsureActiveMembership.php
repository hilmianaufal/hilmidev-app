<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        $membership = Membership::query()
            ->with('plan')
            ->where('user_id', $request->user()->id)
            ->active()
            ->latest('id')
            ->first();

        if (! $membership) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Akses kelas membutuhkan membership aktif.');
        }

        $request->attributes->set('activeMembership', $membership);

        return $next($request);
    }
}
