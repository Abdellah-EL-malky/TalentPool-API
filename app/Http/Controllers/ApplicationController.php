<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function apply(Request $request, Announcement $announcement)
    {
        $validatedData = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'motivation_letter' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $cvPath = $request->file('cv')->store('cvs');
        $motiveLetterPath = $request->file('motivation_letter')->store('motivation_letters');

        $application = Application::create([
            'user_id' => auth()->id(),
            'announcement_id' => $announcement->id,
            'cv_path' => $cvPath,
            'motivation_letter_path' => $motiveLetterPath
        ]);

        return response()->json($application, 201);
    }

    public function updateStatus(Request $request, Application $application)
    {
        Gate::authorize('updateStatus', $application);

        $validatedData = $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected'
        ]);

        $application->update($validatedData);

        // Notification par email serait implémentée ici

        return response()->json($application);
    }

    public function destroy(Application $application)
    {
        Gate::authorize('delete', $application);
        $application->delete();
        return response()->json(null, 204);
    }
}
