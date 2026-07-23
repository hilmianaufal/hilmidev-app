<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $members = User::query()
            ->where('role', '!=', 'admin')
            ->whereHas('memberships')
            ->with(['memberships.plan'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.members.index', compact('members', 'search'));
    }

    public function create()
    {
        $plans = MembershipPlan::active()
            ->orderBy('price')
            ->get();

        return view('admin.members.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'status' => ['required', 'in:pending,active,suspended,expired,cancelled'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = new User();

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'client',
        ])->save();

        $this->saveMembership($user, $data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Akun member berhasil dibuat.');
    }

    public function edit(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $membership = $user->memberships()
            ->with('plan')
            ->latest('id')
            ->first();

        $plans = MembershipPlan::query()
            ->where(function ($query) use ($membership) {
                $query->where('is_active', true);

                if ($membership?->membership_plan_id) {
                    $query->orWhereKey($membership->membership_plan_id);
                }
            })
            ->orderBy('price')
            ->get();

        return view(
            'admin.members.edit',
            compact('user', 'plans', 'membership')
        );
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'status' => ['required', 'in:pending,active,suspended,expired,cancelled'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'notes' => ['nullable', 'string'],
        ]);

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->saveMembership($user, $data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Data dan akses member berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $user->memberships()
            ->whereIn('status', ['pending', 'active'])
            ->update(['status' => 'cancelled']);

        return back()->with('success', 'Akses membership berhasil dinonaktifkan.');
    }

    private function saveMembership(User $user, array $data): void
    {
        $plan = MembershipPlan::findOrFail($data['membership_plan_id']);

        $startsAt = ! empty($data['starts_at'])
            ? Carbon::parse($data['starts_at'])
            : now();

        $expiresAt = ! empty($data['expires_at'])
            ? Carbon::parse($data['expires_at'])
            : (
                $plan->duration_days
                    ? $startsAt->copy()->addDays($plan->duration_days)
                    : null
            );

        $membership = $user->memberships()
            ->latest('id')
            ->first();

        $payload = [
            'membership_plan_id' => $plan->id,
            'status' => $data['status'],
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'activated_at' => $data['status'] === 'active'
                ? ($membership?->activated_at ?: now())
                : null,
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ];

        if ($membership) {
            $membership->update($payload);
        } else {
            $user->memberships()->create($payload);
        }
    }
}
