@extends('layouts.app')

@section('title', 'Order Management')
@section('body-class', 'order-layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-content">
  <div class="header">Order</div>

  <div class="order-actions">
    <div class="search-box">
      <i class="fa-solid fa-search"></i>
      <input type="text" id="searchOrder" placeholder="Cari pesanan..." class="search-input">
    </div>

     <div class="action-buttons">
       @if(auth()->user()->role === 'admin')
        <!-- Export PDF -->
        <a href="{{ route('order.export.pdf') }}" class="btn btn-create" style="background:#dc3545;">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </a>

        <!-- Export Excel -->
        <a href="{{ route('order.export.excel') }}" class="btn btn-create" style="background:#28a745;">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
    @endif

      <button class="btn btn-create" onclick="window.location.href='{{ route('order.create') }}'">
        <i class="fa-solid fa-plus"></i> Create New Order
      </button>
    </div>
  </div>

  <div id="orders-list">
    <div class="orders-table-container">
      <div class="orders-table">
        <div class="table-header">
          <span>Order ID</span>
           @if(auth()->user()->role === 'admin')
      <span>Customer</span>
  @endif
          <span>Date</span>
          <span>Status</span>
          <span>Total</span>
          <span>Action</span>
        </div>

        @foreach($pesanan as $p)
        <div class="table-row">
          <span class="order-id">#{{ $p->nomor_invoice ?? 'INV-'.$p->id }}</span>
          @if(auth()->user()->role === 'admin')
    <span>{{ $p->user->name }}</span>
@endif

          <span>{{ $p->created_at->format('M d, Y') }}</span>
          <span>
            <span class="status 
              {{ $p->status == 'done' ? 'completed' : ($p->status == 'processing' ? 'processing' : 'pending') }}">
              {{ ucfirst($p->status) }}
            </span>
          </span>
          <span class="total">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
         <span class="action-buttons">
    @if(auth()->user()->role === 'admin')
        <button class="btn btn-edit" onclick="window.location.href='{{ route('order.edit', $p->id) }}'">
            <i class="fa-solid fa-edit"></i>
        </button>
    @endif

            <button class="btn btn-detail" onclick="window.location.href='{{ route('order.show', $p->id) }}'">
              <i class="fa-solid fa-info-circle"></i>
            </button>
          </span>
        </div>
        @endforeach
      </div>
    </div>
    </div>

    <div class="pagination-container" id="pagination-links">
      <ul class="pagination">
        @if ($pesanan->onFirstPage())
          <li class="disabled"><a href="#">Prev</a></li>
        @else
          <li><a href="{{ route('order.search', ['page'=>$pesanan->currentPage()-1, 'search'=>request('search')]) }}">Prev</a></li>
        @endif

        @foreach ($pesanan->getUrlRange(1, $pesanan->lastPage()) as $page => $url)
          <li class="{{ $page == $pesanan->currentPage() ? 'active' : '' }}">
            <a href="{{ route('order.search', ['page'=>$page, 'search'=>request('search')]) }}">{{ $page }}</a>
          </li>
        @endforeach

        @if ($pesanan->hasMorePages())
          <li><a href="{{ route('order.search', ['page'=>$pesanan->currentPage()+1, 'search'=>request('search')]) }}">Next</a></li>
        @else
          <li class="disabled"><a href="#">Next</a></li>
        @endif
      </ul>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function(){

  function fetchOrders(url = null, keyword = "") {
    let route = url || "{{ route('order.search') }}";
    $.ajax({
            url: route,
            data: { search: keyword },
            success: function(response){
                $("#orders-list").html(response.data);
                $("#pagination-links").html(response.pagination);
            }
        });
  }
  

  // Live search
  $("#searchOrder").on("keyup", function(){
    fetchOrders(null, $(this).val());
  });

  // Pagination click
  $(document).on("click", ".pagination a", function(e){
    e.preventDefault();
    let url = $(this).attr("href");
    if(!url || url == "#") return;
    fetchOrders(url, $("#searchOrder").val());
});

});
</script>
@endsection
