<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Station;

class FavoriteController extends Controller
{
    /**
     * Get /Favorites
     * liste des stations favoriser de user authentifier 
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('station.connectors.status')->latest('favorites.created_at')->get();

        return view('user.favorites', compact('favorites'));
    }

    public function store(Station $station)
    {
        if (!$station->is_active) {
            return redirect()->back()->with('error', 'Station inactive');
        }
        $createFavorite = Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'station_id' => $station->id,
        ]);
        return back()->with('success', "« {$station->name} » ajouté aux favoris.");
    }

    public function destroy(Station $station)
    {
        $favorite = auth()->user()->favorites()->where('station_id', $station->id)->first()->delete();
        // $favorite->delete();
// dd($favorite)
        return back()->with('info', "« {$station->name} » supprimé des favoris.");

    }
}
