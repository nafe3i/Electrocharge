@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto">

        <h1 class="text-xl font-medium text-gray-800 mb-6">Notifications</h1>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            @forelse($notifications as $notification)
                <div class="flex items-center gap-4 px-5 py-4
                                border-b border-gray-100 last:border-0
                                {{ $notification->read_at ? 'bg-white' : 'bg-green-50' }}">
                    <div class="text-2xl">⚡</div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">
                            {{ $notification->data['message'] }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ route('stations.show', $notification->data['station_id']) }}"
                        class="text-sm text-green-600 hover:underline flex-shrink-0">
                        Voir →
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-4xl mb-3">🔔</div>
                    <p class="text-gray-500 text-sm">Aucune notification.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
    </div>

@endsection