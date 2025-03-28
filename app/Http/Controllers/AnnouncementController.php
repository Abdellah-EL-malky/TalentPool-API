<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnnouncementController extends Controller
{
    public function index()
    {
        return Announcement::where('status', 'active')->get();
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Announcement::class);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string',
            'location' => 'required|string'
        ]);

        $announcement = auth()->user()->announcements()->create($validatedData);

        return response()->json($announcement, 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        Gate::authorize('update', $announcement);

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'company' => 'sometimes|string',
            'location' => 'sometimes|string',
            'status' => 'sometimes|in:active,closed'
        ]);

        $announcement->update($validatedData);

        return response()->json($announcement);
    }

    public function destroy(Announcement $announcement)
    {
        Gate::authorize('delete', $announcement);
        $announcement->delete();
        return response()->json(null, 204);
    }
}
