<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Movie;
use App\Models\Profile;

class HomeController extends Controller
{
    public function index()
    {
        // 1. SECURITY: Ensure a Profile is selected
        // If the user manually types /browse without clicking a face, send them back
        if (!session()->has('current_profile_id')) {
            return redirect()->route('profiles.index');
        }

        // 2. Get the Current Profile
        $profileId = session('current_profile_id');
        $profile = Profile::findOrFail($profileId);

        // 3. FETCH DATA

        // A. Hero Movie (Random "Featured" content)
        // If it's a kid, ensure we don't show R-rated content
        $heroQuery = Movie::inRandomOrder();
        if ($profile->is_kid) {
            $heroQuery->where('rating', '!=', 'R')->where('rating', '!=', 'TV-MA');
        }
        $heroMovie = $heroQuery->first();

        // B. Continue Watching Row
        // Get entries from 'watch_progress' table where is_finished is false
        $continueWatching = $profile->watchProgress()
            ->with('movie') // Eager load the movie details to avoid N+1 queries
            ->where('is_finished', false)
            ->orderBy('last_watched_at', 'desc')
            ->take(10)
            ->get();

        // C. Trending Row (Using the scopeTrending we made in the Model)
        $trendingQuery = Movie::trending();
        if ($profile->is_kid) {
             $trendingQuery->where('rating', '!=', 'R');
        }
        $trending = $trendingQuery->get();

        // D. Action Movies Row (Example of Genre filtering)
        // assuming you have a genre relationship setup
        $actionMovies = Movie::whereHas('genres', function($q) {
            $q->where('slug', 'action'); // Ensure your DB has a genre with slug 'action'
        });
        if ($profile->is_kid) {
            $actionMovies->where('rating', '!=', 'R');
        }
        $actionMovies = $actionMovies->take(10)->get();


        // 4. RETURN VIEW
        return view('videos.index', [
            'hero_movie'        => $heroMovie,
            'continue_watching' => $continueWatching,
            'trending'          => $trending,
            'action_movies'     => $actionMovies,
        ]);
    }

    // A simple placeholder for the watch screen
   public function watch($id)
{
    $movie = Movie::findOrFail($id);
    // Record a view
    $movie->increment('views');

    return view('movies.watch', compact('movie'));
}
}
