<?php

namespace App\Http\Controllers;

use App\Models\ProjectRequest;
use App\Models\Service;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;

class ProjectRequestController extends Controller
{
    public function create(Service $service)
    {
        abort_if(! $service->is_active, 404);

        return view('project-requests.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        abort_if(! $service->is_active, 404);

        $data = $request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'budget' => ['nullable', 'string', 'max:100'],
            'deadline' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string'],
        ]);

        $projectRequest = ProjectRequest::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'project_name' => $data['project_name'],
            'company_name' => $data['company_name'] ?? null,
            'phone' => $data['phone'],
            'budget' => $data['budget'] ?? null,
            'deadline' => $data['deadline'] ?? null,
            'description' => $data['description'],
            'status' => 'pending',
        ]);

        app(WhatsAppNotificationService::class)->sendToAdmin(
            "📩 *Project Request Baru HilmiDev*\n\n" .
            "Project: {$projectRequest->project_name}\n" .
            "Client: " . auth()->user()->name . "\n" .
            "Layanan: {$service->title}\n" .
            "WhatsApp: {$projectRequest->phone}\n" .
            "Budget: " . ($projectRequest->budget ?? '-') . "\n" .
            "Deadline: " . ($projectRequest->deadline ?? '-') . "\n\n" .
            "Segera follow up client."
        );

        return redirect()
            ->route('project-requests.index')
            ->with('success', 'Request project berhasil dikirim. Tim HilmiDev akan segera menghubungi kamu.');
    }

    public function index()
    {
        $projectRequests = ProjectRequest::with('service')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('project-requests.index', compact('projectRequests'));
    }

    public function show(ProjectRequest $projectRequest)
    {
        abort_if($projectRequest->user_id !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $projectRequest->load(['service', 'user']);

        return view('project-requests.show', compact('projectRequest'));
    }
}