@extends('layouts.app')

@section('title','Laundry Service')

@section('content')
<div class="items-page">
<div class="main-content">
    <div class="header">Laundry Item</div>

    {{-- Search & Create --}}
    <div class="order-actions mb-3">
        <div class="search-box">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="search" placeholder="Cari layanan..." class="search-input">
        </div>
        <div class="action-buttons">
            <button class="btn btn-create" onclick="window.location='{{ route('item.create') }}'">
                <i class="fa-solid fa-plus"></i> Tambah Layanan
            </button>
        </div>
    </div>

    {{-- Items List --}}
    <div id="items-list">
        <div class="orders-table-container">
            <div class="orders-table">
                <div class="table-header">
                    <span>ID</span>
                    <span>Layanan</span>
                    <span>Harga</span>
                    <span>Tipe</span>
                    <span>Deskripsi</span>
                    <span>Action</span>
                </div>

                @foreach($items as $item)
                <div class="table-row">
                    <span>#{{ $item->id }}</span>
                    <span>{{ $item->nama_service }}</span>
                    <span>Rp {{ number_format($item->harga,0,",",".") }}</span>
                    <span>{{ $item->tipe_service }}</span>
                    <span>{{ $item->deskripsi }}</span>
                    <span class="action-buttons">
                        <button class="btn btn-edit" onclick="window.location='{{ route('item.edit',$item->id) }}'">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button class="btn btn-delete" onclick="confirmDelete({{ $item->id }})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('item.delete',$item->id) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <!-- Detail -->
    <button class="btn btn-detail" onclick="window.location='{{ route('item.show', $item->id) }}'">
        <i class="fa-solid fa-info-circle"></i>
    </button>
                    </span>
                </div>
                @endforeach

            </div>
            </div>
        </div>
        {{-- PAGINATION --}}
        <div class="pagination-container" id="pagination-links">
            <ul class="pagination">
                @if ($items->onFirstPage())
                    <li class="disabled"><a href="#">Prev</a></li>
                @else
                    <li><a href="{{ route('item.search', ['page'=>$items->currentPage()-1, 'search'=>request('search')]) }}">Prev</a></li>
                @endif

                @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                    <li class="{{ $page == $items->currentPage() ? 'active' : '' }}">
                        <a href="{{ route('item.search', ['page'=>$page, 'search'=>request('search')]) }}">
                            {{ $page }}
                        </a>
                    </li>
                @endforeach

                @if ($items->hasMorePages())
                    <li><a href="{{ route('item.search', ['page'=>$items->currentPage()+1, 'search'=>request('search')]) }}">Next</a></li>
                @else
                    <li class="disabled"><a href="#">Next</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    function fetchItems(url = null, keyword = "") {
        let route = url || "{{ route('item.search') }}";
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
        fetchItems(url, $("#search").val());
    });

}); // <- Ini wajib ditutup

// confirmDelete tetap di luar supaya bisa dipanggil dari HTML
function confirmDelete(id){
    Swal.fire({
        title: 'Hapus layanan?',
        text: "Data akan dihapus permanen!",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor:'#d33',
        cancelButtonColor:'#3085d6',
        confirmButtonText:'Yes, delete it!',
        cancelButtonText:'Cancel'
    }).then((result)=>{
        if(result.isConfirmed){
            document.getElementById('delete-form-'+id).submit();
        }
    });
}
</script>
@endsection
