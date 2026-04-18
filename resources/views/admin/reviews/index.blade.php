@extends('layouts.admin')

@section('page-title', 'Modération des avis')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-medium text-gray-800">Avis utilisateurs</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $reviews->total() }} avis au total</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">Utilisateur</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">Station</th>
                    <th class="text-center px-4 py-3 text-gray-500 font-medium">Note</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">Commentaire</th>
                    <th class="text-center px-4 py-3 text-gray-500 font-medium">Date</th>
                    <th class="text-right px-4 py-3 text-gray-500 font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ $review->user->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <a href="{{ route('stations.show', $review->station) }}" class="hover:text-green-600"
                                target="_blank">
                                {{ $review->station->name }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-center text-yellow-500">
                            {{ str_repeat('⭐', $review->rating) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                            {{ $review->comment ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-400 text-xs">
                            {{ $review->created_at->diffForHumans() }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                onsubmit="return confirm('Supprimer cet avis ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                            Aucun avis pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($reviews->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

@endsection