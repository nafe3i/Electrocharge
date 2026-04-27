@extends('layouts.admin')

@section('page-title', 'Modération des avis')

@section('content')

<div class="mb-6">
    <h2 class="text-base font-semibold text-slate-800">Avis utilisateurs</h2>
    <p class="text-sm text-slate-500 mt-0.5">{{ $reviews->total() }} avis au total</p>
</div>

<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-4 py-3 text-slate-500 font-medium">Utilisateur</th>
                <th class="text-left px-4 py-3 text-slate-500 font-medium">Station</th>
                <th class="text-center px-4 py-3 text-slate-500 font-medium">Note</th>
                <th class="text-left px-4 py-3 text-slate-500 font-medium">Commentaire</th>
                <th class="text-center px-4 py-3 text-slate-500 font-medium">Date</th>
                <th class="text-right px-4 py-3 text-slate-500 font-medium">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($reviews as $review)
                <tr class="hover:bg-slate-50 transition">

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center
                                        justify-center text-green-700 text-xs font-semibold">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-slate-700">
                                {{ $review->user->name }}
                            </span>
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        <a href="{{ route('stations.show', $review->station) }}"
                           target="_blank"
                           class="text-slate-600 hover:text-green-600 transition">
                            {{ $review->station->name }}
                        </a>
                    </td>

                    <td class="px-4 py-3 text-center">
                        <span class="bg-amber-50 text-amber-700 border border-amber-200
                                     text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ $review->rating }}/5
                        </span>
                    </td>

                    <td class="px-4 py-3 text-slate-600 max-w-xs truncate">
                        {{ $review->comment ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-center text-slate-400 text-xs">
                        {{ $review->created_at->diffForHumans() }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        <form method="POST"
                              action="{{ route('admin.reviews.destroy', $review) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-500 hover:text-red-700 text-xs transition">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                        Aucun avis pour le moment.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($reviews->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

@endsection
