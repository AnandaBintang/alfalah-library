<div class="mx-auto mb-19 w-10/12">
  <h1 class="text-2xl font-bold mb-6">Keranjang Buku</h1>

  @if($cartItems && $cartItems->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($cartItems as $item)
        @livewire('app.cart.cart-item', ['cartItem' => $item], key($item->id))
      @endforeach
    </div>

    <div class="mt-8 flex justify-end gap-4">
      <a href="{{ route('book.index') }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
        Kembali ke daftar buku
      </a>

      <button wire:click="checkout" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
        Checkout
      </button>
    </div>
  @else
    <p class="text-center text-gray-500 whitespace-pre-line p-10">
      Keranjang kamu kosong. <br>
      Silakan pilih buku untuk ditambahkan ke keranjang.
    </p>
  @endif
</div>
