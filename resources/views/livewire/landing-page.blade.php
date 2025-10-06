<div class="min-h-screen pt-8">
    @include('livewire.components.landingpage.hero')
    @if($totalKegiatan > 0)
      <livewire:components.landingpage.kegiatan/>
    @endif
    @include('livewire.components.landingpage.features')
    @include('livewire.components.landingpage.gallery')
    @include('livewire.components.landingpage.location')
    @include('livewire.components.landingpage.footer')
</div>
