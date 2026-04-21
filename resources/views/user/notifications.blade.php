@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium text-gray-800">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $notifications->total() }} notification(s)
            </p>
        </div>

        @if($notifications->total() > 0)
            <form method="POST" action="{{ route('notifications.clear') }}"
                  onsubmit="return confirm('Supprimer toutes les notifications ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-sm border border-red-200 text-red-500 px-4 py-2
                               rounded-lg hover:bg-red-50 transition">
                    Tout supprimer
                </button>
            </form>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg
                    px-4 py-3 text-sm mb-4 flex justify-between">
            {{ session('success') }}
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        @forelse($notifications as $notification)
            <div class="flex items-start gap-4 px-5 py-4
                        border-b border-gray-100 last:border-0
                        {{ $notification->read_at ? '' : 'bg-green-50' }}">

                {{-- Icône --}}
                <div class="w-9 h-9 rounded-full flex items-center justify-center
                            text-lg flex-shrink-0 mt-0.5
                            {{ $notification->read_at ? 'bg-gray-100' : 'bg-green-100' }}">
                    🔔
                </div>

                {{-- Contenu --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 font-medium">
                        {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                    </p>
                    @if(isset($notification->data['station_id']))
                        <a href="{{ route('stations.show', $notification->data['station_id']) }}"
                           class="text-xs text-green-600 hover:underline mt-1 inline-block">
                            Voir la station →
                        </a>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Badge non lu --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(!$notification->read_at)
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    @endif

                    {{-- Supprimer --}}
                    <form method="POST"
                          action="{{ route('notifications.destroy', $notification->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-gray-300 hover:text-red-500
                                       transition text-xl leading-none">
                            ×
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-4xl mb-3">🔔</div>
                <p class="text-gray-500 text-sm">
                    Aucune notification pour le moment.
                </p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@endsection