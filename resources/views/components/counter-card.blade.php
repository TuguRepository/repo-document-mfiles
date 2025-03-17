<!--
Save this as resources/views/components/stk/counter-card.blade.php
-->
@props([
    'title' => 'Counter',
    'count' => 0,
    'type' => 'default',
    'icon' => null,
    'id' => '',
])

@php
    $bgColor = match($type) {
        'pending' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'approved' => 'bg-green-50 dark:bg-green-900/20',
        'rejected' => 'bg-red-50 dark:bg-red-900/20',
        'total' => 'bg-blue-50 dark:bg-blue-900/20',
        default => 'bg-gray-50 dark:bg-gray-800'
    };

    $textColor = match($type) {
        'pending' => 'text-yellow-600 dark:text-yellow-400',
        'approved' => 'text-green-600 dark:text-green-400',
        'rejected' => 'text-red-600 dark:text-red-400',
        'total' => 'text-blue-600 dark:text-blue-400',
        default => 'text-gray-600 dark:text-gray-400'
    };

    $borderColor = match($type) {
        'pending' => 'border-yellow-200 dark:border-yellow-700',
        'approved' => 'border-green-200 dark:border-green-700',
        'rejected' => 'border-red-200 dark:border-red-700',
        'total' => 'border-blue-200 dark:border-blue-700',
        default => 'border-gray-200 dark:border-gray-700'
    };

    $iconColor = match($type) {
        'pending' => 'text-yellow-500 dark:text-yellow-400',
        'approved' => 'text-green-500 dark:text-green-400',
        'rejected' => 'text-red-500 dark:text-red-400',
        'total' => 'text-blue-500 dark:text-blue-400',
        default => 'text-gray-500 dark:text-gray-400'
    };
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-lg border $bgColor $borderColor transition-all duration-200"]) }}>
    <div class="flex justify-between items-center">
        <h3 class="font-medium text-gray-700 dark:text-gray-300">{{ $title }}</h3>

        @if($icon)
            <div class="{{ $iconColor }}">
                {{ $icon }}
            </div>
        @endif
    </div>

    <div class="mt-2">
        <span id="{{ $id }}" class="text-3xl font-bold {{ $textColor }} transition-all duration-200">
            {{ $count }}
        </span>
    </div>

    {{ $slot }}
</div>

<!--
Save this as resources/views/stk/approvals/dashboard.blade.php or add to an existing view file
-->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stk.counter-card
        title="Permintaan Tertunda"
        :count="$stats['pending_count']"
        type="pending"
        id="pending-count"
        icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>'
    >
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total ditolak</p>
    </x-stk.counter-card>

    <x-stk.counter-card
        title="Total Permintaan"
        :count="$stats['total_count']"
        type="total"
        id="total-count"
        icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>'
    >
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Semua permintaan</p>
    </x-stk.counter-card>
</div>

<!-- Chart containers for dashboard -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Tren Permintaan</h3>
        <div class="h-64">
            <canvas id="request-trend-chart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Rasio Persetujuan</h3>
        <div class="h-64">
            <canvas id="approval-rate-chart"></canvas>
        </div>
    </div>
</div>

<!-- Recent activity section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Aktivitas Terbaru</h3>

    @if($recentActivity->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Referensi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Pengguna
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Dokumen
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Diproses Oleh
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Waktu
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentActivity as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('stk.approvals.show', $activity) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $activity->reference_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $activity->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ Str::limit($activity->document->title, 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($activity->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $activity->approver->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $activity->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru.</p>
    @endif
</div>

<!-- Real-time notification element (hidden) -->
<div id="notification-container" class="fixed top-4 right-4 z-50"></div>

<!-- CSS for counter animation -->
<style>
    .counter-updated {
        animation: pulse 1s;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

@push('scripts')
<!-- Include Chart.js if not already included -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<!-- Include our custom JS -->
<script>
    // The JavaScript will be loaded from the separate file we created earlier
    // But you could also include it inline here
</script>
@endpush 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>'
    >
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Menunggu persetujuan</p>
    </x-stk.counter-card>

    <x-stk.counter-card
        title="Permintaan Disetujui"
        :count="$stats['approved_count']"
        type="approved"
        id="approved-count"
        icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>'
    >
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total disetujui</p>
    </x-stk.counter-card>

    <x-stk.counter-card
        title="Permintaan Ditolak"
        :count="$stats['rejected_count']"
        type="rejected"
        id="rejected-count"
        icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24
