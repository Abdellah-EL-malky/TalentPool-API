<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Announcement;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruiter_can_create_announcement()
    {
        $recruiter = User::factory()->create(['role' => 'recruiter']);

        $announcementData = [
            'title' => 'Software Engineer',
            'description' => 'Looking for talented engineers',
            'company' => 'Tech Corp',
            'location' => 'Remote'
        ];

        $response = $this
            ->actingAs($recruiter)
            ->postJson('/api/announcements', $announcementData);

        $response
            ->assertStatus(201)
            ->assertJson($announcementData);

        $this->assertDatabaseHas('announcements', $announcementData);
    }

    public function test_candidate_cannot_create_announcement()
    {
        $candidate = User::factory()->create(['role' => 'candidate']);

        $announcementData = [
            'title' => 'Software Engineer',
            'description' => 'Looking for talented engineers',
            'company' => 'Tech Corp',
            'location' => 'Remote'
        ];

        $response = $this
            ->actingAs($candidate)
            ->postJson('/api/announcements', $announcementData);

        $response->assertStatus(403);
    }
}
