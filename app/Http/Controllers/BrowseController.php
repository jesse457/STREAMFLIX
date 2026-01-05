<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Profile;
use App\Models\WatchProgress;

class BrowseController extends Controller
{
    /**
     * Show the main browse/homepage.
     */
    public function index()
    {
        if (! session()->has('current_profile_id')) {
            return redirect()->route('profiles.index');
        }

        $profile = Profile::findOrFail(session('current_profile_id'));

        // Hero Movie
        $heroQuery = Movie::inRandomOrder();
        if ($profile->is_kid) {
            $heroQuery->whereNotIn('rating', ['R', 'TV-MA']);
        }
        $heroMovie = $heroQuery->first();

        // Continue Watching
        $continueWatching = $profile->watchProgress()
            ->with(['movie', 'episode'])
            ->where('is_finished', false)
            ->orderBy('last_watched_at', 'desc')
            ->take(10)
            ->get();

        // Trending
        $trendingQuery = Movie::trending();
        if ($profile->is_kid) {
            $trendingQuery->whereNotIn('rating', ['R', 'TV-MA']);
        }
        $trending = $trendingQuery->get();

        // Action Movies
        $actionMovies = Movie::whereHas('genres', function ($q) {
            $q->where('slug', 'action');
        });
        if ($profile->is_kid) {
            $actionMovies->whereNotIn('rating', ['R', 'TV-MA']);
        }
        $actionMovies = $actionMovies->take(10)->get();

        return view('browse.index', [
            'hero_movie' => $heroMovie,
            'continue_watching' => $continueWatching,
            'trending' => $trending,
            'action_movies' => $actionMovies,
        ]);
    }

    /**
     * Show the series index page.
     */
    public function series()
    {
        if (! session()->has('current_profile_id')) {
            return redirect()->route('profiles.index');
        }

        $profile = Profile::findOrFail(session('current_profile_id'));

        // Hero Series
        $seriesQuery = Movie::where('type', 'series')->withCount('episodes')->inRandomOrder();
        if ($profile->is_kid) {
            $seriesQuery->whereNotIn('rating', ['R', 'TV-MA']);
        }
        $heroSeries = $seriesQuery->first();

        // Trending Series
        $trendingSeriesQuery = Movie::where('type', 'series')->withCount('episodes')->orderBy('views', 'desc');
        if ($profile->is_kid) {
            $trendingSeriesQuery->whereNotIn('rating', ['R', 'TV-MA']);
        }
        $trendingSeries = $trendingSeriesQuery->take(12)->get();

        // Continue Watching Series
        $continueWatchingSeries = WatchProgress::where('profile_id', $profile->id)
            ->whereHas('movie', fn ($q) => $q->where('type', 'series'))
            ->with(['movie', 'episode'])
            ->latest('last_watched_at')
            ->take(10)
            ->get();

        // Comedies
        $comedySeries = Movie::where('type', 'series')->take(12)->get();

        return view('browse.series', [
            'hero_series' => $heroSeries,
            'trending_series' => $trendingSeries,
            'continue_watching' => $continueWatchingSeries,
            'comedy_series' => $comedySeries,
        ]);
    }

    /**
     * Show the watch (player) screen.
     */
    public function watch($id)
    {
        $movie = Movie::findOrFail($id);
        
        $movie->increment('views');

        return view('browse.watch', compact('movie'));
    }
}
