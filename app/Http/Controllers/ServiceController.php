<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $sort = trim((string) $request->get('sort', 'latest'));

        $servicesQuery = Service::query()
            ->where('is_active', true);

        if ($search !== '') {
            $servicesQuery->where(function (Builder $query) use ($search) {
                $query
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('features', 'like', '%' . $search . '%');
            });
        }

        match ($sort) {
            'featured' => $servicesQuery
                ->orderByDesc('is_featured')
                ->latest(),

            'price_low' => $servicesQuery
                ->orderBy('starting_price'),

            'price_high' => $servicesQuery
                ->orderByDesc('starting_price'),

            'name_asc' => $servicesQuery
                ->orderBy('title'),

            'name_desc' => $servicesQuery
                ->orderByDesc('title'),

            'oldest' => $servicesQuery
                ->oldest(),

            default => $servicesQuery
                ->latest(),
        };

        $services = $servicesQuery
            ->paginate(9)
            ->withQueryString();

        $serviceStatistics = [
            'total_services' => Service::query()
                ->where('is_active', true)
                ->count(),

            'featured_services' => Service::query()
                ->where('is_active', true)
                ->where('is_featured', true)
                ->count(),

            'starting_price' => Service::query()
                ->where('is_active', true)
                ->min('starting_price') ?? 0,
        ];

        return view('services.index', compact(
            'services',
            'serviceStatistics',
            'search',
            'sort'
        ));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $relatedServices = Service::query()
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(4)
            ->get();

        return view('services.show', compact(
            'service',
            'relatedServices'
        ));
    }
}
