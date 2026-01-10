@extends('layouts.app')

@section('title', 'New Order')
@section('body-class', 'dashboard-layout')

@section('content')
<div class="orders-page">
<div class="main-content">
  <div class="breadcrumb">
      <a href="{{ route('order.index') }}">Order</a> / <span>New Order</span>
  </div>

  <div class="header">New Order</div>

  <div class="create-order-container">

    <!-- LEFT SECTION - FORM -->
    <div class="order-form-section">
      <form id="submitOrderForm" method="POST" action="{{ route('order.store') }}">
        @csrf

        {{-- IF ADMIN --}}
        @if (auth()->user()->role === 'admin')
        <div class="form-group">
            <label>Customer</label>
            <select id="customerSelect" name="user_id" class="form-control" required>
                <option disabled selected>-- Select Customer --</option>
                @foreach ($customers as $c)
                    <option value="{{ $c->id }}" data-address="{{ $c->address }}">
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" id="customerAddress" class="form-control" disabled>
        </div>

        @else
        {{-- IF USER BIASA --}}
<div class="form-group">
    <label>Customer</label>
    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled
           style="background-color: #f0f0f0; color: #555; cursor: not-allowed;">
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
</div>

<div class="form-group">
    <label>Address</label>
    <input type="text" class="form-control" value="{{ Auth::user()->address ?? '-' }}" disabled
           style="background-color: #f0f0f0; color: #555; cursor: not-allowed;">
</div>

        @endif

        <div class="form-group">
            <label>Service</label>
            <select id="itemSelect" class="form-control" required>
                <option disabled selected>-- Select Item --</option>
                @foreach ($items as $i)
                    <option value="{{ $i->id }}" data-harga="{{ $i->harga }}" data-nama="{{ $i->nama_service }}">
                        {{ $i->nama_service }} — Rp {{ number_format($i->harga,0,',','.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" id="quantityInput" class="form-control" min="1" value="1">
        </div>

        <div class="form-actions">
          <button type="button" class="btn-add" id="btnAddCart">
            <i class="fa-solid fa-cart-plus"></i> Add to Cart
          </button>
        </div>

        <!-- HIDDEN INPUT UNTUK KIRIM KE BACKEND -->
        <input type="hidden" name="items" id="itemsInput">
        <input type="hidden" name="total_harga" id="totalHargaInput">

      </form>
    </div>

    <!-- RIGHT SECTION - CART -->
    <div class="cart-summary-section">
        <div class="cart-box" id="cartItems">
            <p class="empty-cart">Your cart is empty</p>
        </div>
        <div class="total-section">
            <h2 class="total-price" id="totalHargaText">Rp. 0</h2>
            <button type="submit" class="btn-process" form="submitOrderForm">
                Process to Payment
            </button>
        </div>
    </div>

  </div>
</div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];

// Reset cart
document.addEventListener("DOMContentLoaded", () => {
    localStorage.removeItem("cart");
    cart = [];
    renderCart();

    // Add to cart
    document.getElementById("btnAddCart").addEventListener("click", () => {
        const select = document.getElementById("itemSelect");
        const id = select.value;
        if (!id) return alert("Pilih item dulu!");

        const harga = parseInt(select.options[select.selectedIndex].dataset.harga);
        const nama  = select.options[select.selectedIndex].dataset.nama;
        const qty   = parseInt(document.getElementById("quantityInput").value);

        cart.push({ id, name: nama, harga, jumlah: qty, subtotal: harga*qty });
        renderCart();
    });

    // Submit form
    const form = document.getElementById("submitOrderForm");
    form.addEventListener("submit", () => {
        if(cart.length === 0){
            alert("Cart kosong!");
            event.preventDefault();
            return;
        }
        document.getElementById("itemsInput").value = JSON.stringify(cart);
        let total = cart.reduce((sum,c)=>sum+c.subtotal,0);
        document.getElementById("totalHargaInput").value = total;
    });

    // Admin: update address otomatis
    const customerSelect = document.getElementById("customerSelect");
    if(customerSelect){
        customerSelect.addEventListener("change", function(){
            const address = this.options[this.selectedIndex].dataset.address;
            document.getElementById("customerAddress").value = address;
        });
    }
});

// ===============================
//   UPDATE: Fungsi Hapus Item
// ===============================
function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

// Render cart
function renderCart(){
    const container = document.getElementById("cartItems");
    container.innerHTML = "";
    let total = 0;

    if (cart.length === 0) {
        container.innerHTML = `<p class="empty-cart">Keranjang kosong</p>`;
        document.getElementById("totalHargaText").textContent = "Rp 0";
        return;
    }

    cart.forEach((c, index) => {
        total += c.subtotal;

       container.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <h4>${c.name}</h4>
                    <p>Qty: ${c.jumlah} × Rp ${c.harga.toLocaleString()}</p>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="cart-item-price">Rp ${c.subtotal.toLocaleString()}</span>

                    <button onclick="removeItem(${index})" class="cart-item-remove">
    hapus
</button>

                </div>
            </div>
        `;
    });

    document.getElementById("totalHargaText").textContent =
        "Rp " + total.toLocaleString();
}
</script>

@endpush
