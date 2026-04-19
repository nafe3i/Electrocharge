@extends('layouts.admin')

@section('page-title', 'Journal des actions')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-medium text-gray-800">Journal des actions</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $logs->total() }} action(s) enregistrée(s)</p>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Admin</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Action</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Cible</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Détails</th>
                <th class="text-center px-4 py-3 text-gray-500 font-medium">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center
                                        justify-center text-green-700 text-xs font-medium">
                                {{ strtoupper(substr($log->admin->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-700">
                                {{ $log->admin->name ?? 'Système' }}
                            </span>
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                            {{ $log->action }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $log->target_type }} #{{ $log->target_id }}
                    </td>

                    <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate">
                        @if($log->details)
                            {{ json_encode($log->details) }}
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center text-gray-400 text-xs">
                        {{ $log->created_at->diffForHumans() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        Aucune action enregistrée.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection
