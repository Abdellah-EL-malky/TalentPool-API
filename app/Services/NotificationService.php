<?php
namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendApplicationStatusChangedEmail(Application $application)
    {
        $candidate = $application->user;
        $announcement = $application->announcement;

        Mail::send('emails.application_status', [
            'candidate_name' => $candidate->name,
            'announcement_title' => $announcement->title,
            'status' => $application->status
        ], function ($message) use ($candidate) {
            $message->to($candidate->email)
                ->subject('Mise à jour du statut de votre candidature');
        });
    }

    public function sendApplicationReceivedEmail(Application $application)
    {
        $recruiter = $application->announcement->user;
        $candidate = $application->user;

        Mail::send('emails.application_received', [
            'recruiter_name' => $recruiter->name,
            'candidate_name' => $candidate->name,
            'announcement_title' => $application->announcement->title
        ], function ($message) use ($recruiter) {
            $message->to($recruiter->email)
                ->subject('Nouvelle candidature reçue');
        });
    }
}
