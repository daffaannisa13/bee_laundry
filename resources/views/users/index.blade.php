@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="main-content">
    <div class="header">User Management</div>

    {{-- SEARCH --}}
    <div class="order-actions" style="margin-bottom: 20px;">
        <div class="search-box">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="search" placeholder="Cari user..." class="search-input">
        </div>

        <div class="action-buttons">
            <button class="btn btn-create" onclick="window.location.href='{{ route('users.create') }}'">
                <i class="fa-solid fa-plus"></i> Create New User
            </button>
        </div>
    </div>

    {{-- USERS LIST --}}
    <div id="users-list">
        <div class="orders-table-container">
            <div class="orders-table">
                <div class="table-header">
                    <span>User ID</span>
                    <span>Name</span>
                    <span>Email</span>
                    <span>Role</span>
                    <span>Action</span>
                </div>

                @foreach($users as $user)
                <div class="table-row">
                    <span>#{{ $user->id }}</span>
                    <span>{{ $user->name }}</span>
                    <span>{{ $user->email }}</span>
                    <span>{{ $user->role }}</span>
                    <span class="action-buttons">
    <!-- Edit -->
    <button class="btn btn-edit" onclick="window.location='{{ route('users.edit', $user->id) }}'">
        <i class="fa-solid fa-edit"></i>
    </button>

    <!-- Delete -->
    <!-- Delete button -->
<button class="btn btn-delete" onclick="confirmDelete({{ $user->id }})">
    <i class="fa-solid fa-trash"></i>
</button>

<!-- Hidden form -->
<form id="delete-form-{{ $user->id }}" action="{{ route('users.delete', $user->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>



    <!-- Detail -->
    <button class="btn btn-detail" onclick="window.location='{{ route('users.show', $user->id) }}'">
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
            @if ($users->onFirstPage())
                <li class="disabled"><a href="#">Prev</a></li>
            @else
                <li><a href="{{ route('users.search', ['page'=>$users->currentPage()-1, 'search'=>request('search')]) }}">Prev</a></li>
            @endif

            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <li class="{{ $page == $users->currentPage() ? 'active' : '' }}">
                    <a href="{{ route('users.search', ['page'=>$page, 'search'=>request('search')]) }}">
                        {{ $page }}
                    </a>
                </li>
            @endforeach

            @if ($users->hasMorePages())
                <li><a href="{{ route('users.search', ['page'=>$users->currentPage()+1, 'search'=>request('search')]) }}">Next</a></li>
            @else
                <li class="disabled"><a href="#">Next</a></li>
            @endif
        </ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    function fetchUsers(url = null, keyword = "") {
        let route = url || "{{ route('users.search') }}";
        $.ajax({
            url: route,
            data: { search: keyword },
            success: function(response){
                $("#users-list").html(response.data);
                $("#pagination-links").html(response.pagination);
            }
        });
    }

    // Live search
    $("#search").on("keyup", function(){
        fetchUsers(null, $(this).val());
    });

    // Pagination click
    $(document).on("click", ".pagination a", function(e){
        e.preventDefault();
        let url = $(this).attr("href");
        if(!url || url == "#") return;
        fetchUsers(url, $("#search").val());
    });

});
</script>

<script>
function confirmDelete(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // submit form yang tersembunyi
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}
</script>


@endsection
