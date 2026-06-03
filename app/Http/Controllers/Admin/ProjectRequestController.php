<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class ProjectRequestController extends Controller
{
    public function index()
    {
        $projectRequests = ProjectRequest::with([
            'user',
            'service'
        ])->latest()->paginate(10);

        return view(
            'admin.project-requests.index',
            compact('projectRequests')
        );
    }

    public function show(ProjectRequest $projectRequest)
    {
        $projectRequest->load([
            'user',
            'service'
        ]);

        return view(
            'admin.project-requests.show',
            compact('projectRequest')
        );
    }

    public function updateStatus(
        Request $request,
        ProjectRequest $projectRequest
    ) {
        $request->validate([
            'status' => [
                'required',
                'in:pending,review,quotation,development,completed,cancelled'
            ],
        ]);

        $projectRequest->update([
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Status project berhasil diperbarui.'
        );
    }
}