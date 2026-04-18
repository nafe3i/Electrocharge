<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Station;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'station'])
            ->latest('created_at')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request, Station $station)
    {
        // Utiliser la permission Spatie déjà existante
        abort_if(!auth()->user()->can('create-review'), 403);

        $dejaDonne = Review::where('user_id', auth()->id())
            ->where('station_id', $station->id)
            ->exists();

        if ($dejaDonne) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour cette station.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'station_id' => $station->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }

    public function destroy(Review $review)
    {
        // L'auteur OU un admin peut supprimer
        abort_if(
            $review->user_id !== auth()->id() && !auth()->user()->can('manage-reviews'),
            403
        );

        $review->delete();

        return back()->with('success', 'Avis supprimé.');
    }

}