@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Notifications</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $notifications->total() }} notification(s)
                </p>
            </div>

            @if($notifications->total() > 0)
                <form method="POST" action="{{ route('notifications.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm border border-slate-200 text-slate-500 px-4 py-2
                                       rounded-lg hover:bg-slate-50 transition">
                        Tout effacer
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            @forelse($notifications as $notification)
                <div class="flex items-start gap-4 px-5 py-4
                                border-b border-slate-100 last:border-0
                                {{ $notification->read_at ? '' : 'bg-green-50/50' }}">

                    <div class="w-9 h-9 rounded-full flex items-center justify-center
                                    flex-shrink-0 mt-0.5
                                    {{ $notification->read_at ? 'bg-slate-100' : 'bg-green-100' }}">
                        <svg class="w-4 h-4 {{ $notification->read_at ? 'text-slate-400' : 'text-green-600' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-800 font-medium">
                            {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                        </p>
                        @if(isset($notification->data['station_id']))
                            <a href="{{ route('stations.show', $notification->data['station_id']) }}"
                                class="text-xs text-green-600 hover:underline mt-1 inline-block">
                                Voir la station &rarr;
                            </a>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(!$notification->read_at)
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        @endif
                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-slate-300 hover:text-red-500 transition
                                               rounded hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center
                                    justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm">Aucune notification pour le moment.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection