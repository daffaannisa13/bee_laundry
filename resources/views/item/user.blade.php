@extends('layouts.app')

@section('title','Laundry Service')

@section('content')
<div class="main-content">
    <div class="header">Laundry Item</div>

    {{-- Search --}}
    <div class="order-actions mb-3">
    <div class="search-box">
        <i class="fa-solid fa-search"></i>
        <input type="text" id="search" placeholder="Cari layanan..." class="search-input">
    </div>
</div>


    {{-- ITEMS (CARD VIEW) --}}
    <div id="items-list">
        <div class="item-card-container">
            @foreach($items as $item)
            <div class="item-card">
                <div class="item-card-header">
                    <h3>{{ $item->nama_service }}</h3>
                    <span class="badge">{{ $item->tipe_service }}</span>
                </div>

                <div class="item-card-body">
                    <p class="price">Rp {{ number_format($item->harga,0,",",".") }}</p>
                    <p class="description">
                        {{ $item->deskripsi ?: '-' }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- PAGINATION --}}
<div class="pagination-container" id="pagination-links">
    <ul class="pagination">
        @if ($items->onFirstPage())
            <li class="disabled"><a href="#">Prev</a></li>
        @else
            <li><a href="{{ route('item.user.search', ['page'=>$items->currentPage()-1, 'search'=>request('search')]) }}">Prev</a></li>
        @endif

        @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
            <li class="{{ $page == $items->currentPage() ? 'active' : '' }}">
                <a href="{{ route('item.user.search', ['page'=>$page, 'search'=>request('search')]) }}">{{ $page }}</a>
            </li>
        @endforeach

        @if ($items->hasMorePages())
            <li><a href="{{ route('item.user.search', ['page'=>$items->currentPage()+1, 'search'=>request('search')]) }}">Next</a></li>
        @else
            <li class="disabled"><a href="#">Next</a></li>
        @endif
    </ul>
</div>
</div>

{{-- SweetAlert + Ajax Search --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    function fetchItems(url = null, keyword = "") {
    let route = url || "{{ route('item.user.search') }}";
    $.ajax({
        url: route,
        data: { search: keyword },
        success: function(response){
            $("#items-list").html(response.data);
            $("#pagination-links").html(response.pagination);
        }
    });
}

$("#search").on("keyup", function(){
    fetchItems(null, $(this).val());
});

$(document).on("click", ".pagination a", function(e){
    e.preventDefault();
    let url = $(this).attr("href");
    if(!url || url == "#") return;
    fetchItems(url);
});
});
</script>
@endsection
