<div class="min-h-screen">
        @include('livewire.components.landingpage.hero')
        @include('livewire.components.landingpage.features')
        @include('livewire.components.landingpage.gallery')
        @include('livewire.components.landingpage.location')
        @include('livewire.components.landingpage.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            const id = entry.target.getAttribute('id');
            const link = document.querySelector(`a[href="#${id}"]`);
            if (link) {
                if (entry.isIntersecting) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            }
        });
    }, { threshold: 0.6 });

    document.querySelectorAll('div[id]').forEach(section => {
        observer.observe(section);
    });
});
</script>