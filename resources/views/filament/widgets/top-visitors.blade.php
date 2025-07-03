<x-filament::widget>
    <x-filament::card>
        <h3 class="text-lg font-bold mb-4">Pengunjung Teratas Perpustakaan</h3>
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left py-2 px-3">#</th>
                    <th class="text-left py-2 px-3">Nama</th>
                    <th class="text-left py-2 px-3">Jumlah Kunjungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topVisitors as $i => $visitor)
                    <tr>
                        <td class="py-1 px-3">{{ $i + 1 }}</td>
                        <td class="py-1 px-3 font-semibold">{{ $visitor->name }}</td>
                        <td class="py-1 px-3">{{ $visitor->visits_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-2 px-3 text-center">Tidak ada data pengunjung.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-filament::card>
</x-filament::widget>
