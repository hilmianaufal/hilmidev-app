<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::withCount('memberships')->latest()->paginate(12);

        return view('admin.membership-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.membership-plans.create');
    }

    public function store(Request $request)
    {
        MembershipPlan::create($this->validatedData($request));

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Paket membership berhasil ditambahkan.');
    }

    public function edit(MembershipPlan $membershipPlan)
    {
        return view('admin.membership-plans.edit', compact('membershipPlan'));
    }

    public function update(Request $request, MembershipPlan $membershipPlan)
    {
        $membershipPlan->update($this->validatedData($request, $membershipPlan));

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Paket membership berhasil diperbarui.');
    }

    public function destroy(MembershipPlan $membershipPlan)
    {
        if ($membershipPlan->memberships()->exists()) {
            $membershipPlan->update(['is_active' => false]);

            return back()->with(
                'success',
                'Paket sudah digunakan sehingga dinonaktifkan, bukan dihapus.'
            );
        }

        $membershipPlan->delete();

        return back()->with('success', 'Paket membership berhasil dihapus.');
    }

    private function validatedData(
        Request $request,
        ?MembershipPlan $membershipPlan = null
    ): array {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_plans', 'name')->ignore($membershipPlan?->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'features' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name'])
            . ($membershipPlan ? '' : '-' . Str::lower(Str::random(4)));

        $data['features'] = $request->filled('features')
            ? array_values(array_filter(array_map(
                'trim',
                preg_split('/\r\n|\r|\n/', (string) $request->features)
            )))
            : null;

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
