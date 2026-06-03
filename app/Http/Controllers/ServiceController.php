<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $relatedServices = Service::where('id', '!=', $service->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }
}