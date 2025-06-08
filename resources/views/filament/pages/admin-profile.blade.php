<x-filament::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" color="success">
                SIMPAN
            </x-filament::button>
        </div>
    </form>

    <!-- Modal Notification -->
    <div
        x-data="{ open: false }"
        x-show="open"
        @profile-updated.window="
            open = true;
            setTimeout(() => window.location.reload(), 1500);
        "
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        style="display: none;">
        <div class="bg-white p-8 rounded-lg text-center space-y-4 shadow-lg">
            <h2 class="text-2xl font-bold">UBAH PROFIL BERHASIL!</h2>
            <p>Silahkan melakukan cek berkala pada menu Profil untuk melihat dan mengecek kesesuaian informasi.</p>
            <a href="{{ route('filament.admin.pages.admin-profile') }}" class="inline-block bg-green-500 text-white px-4 py-2 rounded">
                PERGI KE PROFIL SUPERADMIN
            </a>
        </div>
    </div>
</x-filament::page>
