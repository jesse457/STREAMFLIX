<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Profile;
use App\Models\WatchProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_series_index_page_loads_correctly()
    {
        $user = User::factory()->create();
        $profile = Profile::create(['user_id' => $user->id, 'name' => 'Test Profile']);
        
        // Create some series
        $series = Movie::create([
            'title' => 'Test Series',
            'slug' => 'test-series',
            'type' => 'series',
            'thumbnail_url' => 'test.jpg',
            'description' => 'A test series',
            'release_year' => 2024,
            'rating' => 'TV-MA'
        ]);



        // Create an episode
        $episode = Episode::create([
             'movie_id' => $series->id,
             'title' => 'Test Episode',
             'season_number' => 1,
             'episode_number' => 1,
             'duration_minutes' => 45,
             'video_url' => 'test.mp4',
        ]);

        // Create watch progress
        WatchProgress::create([
            'profile_id' => $profile->id,
            'movie_id' => $series->id,
            'episode_id' => $episode->id,
            'progress_seconds' => 100,
            'last_watched_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_profile_id' => $profile->id])
            ->get(route('browse.series')); // Ensure route exists

        $response->assertStatus(200);
        $response->assertViewHas(['hero_series', 'trending_series', 'continue_watching']);
        $response->assertSee('Test Series');
    }
}
