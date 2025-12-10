<div class="sidebar" id="sidebar">
  <div class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </div>
  <div class="sidebar-icons">
  {{-- ADMIN MENU --}}
@if(auth()->check() && auth()->user()->role === 'admin')
    <a href="{{ route('dashboard') }}" title="Dashboard"><i class="fas fa-th-large"></i></a>
    <a href="{{ route('users.index') }}" title="Users"><i class="fas fa-user"></i></a>
    <a href="{{ route('order.index') }}" title="Orders"><i class="fas fa-shopping-cart"></i></a>
    <a href="{{ route('item.index') }}" title="Laundry Items"><i class="fas fa-bucket"></i></a>
@endif

{{-- USER MENU --}}
@if(auth()->check() && auth()->user()->role === 'user')
    <a href="{{ route('dashboard.user') }}" title="Dashboard"><i class="fas fa-th-large"></i></a>

    {{-- My Orders --}}
    <a href="{{ route('order.user') }}" title="My Orders">
        <i class="fas fa-shopping-cart"></i>
    </a>
    <a href="{{ route('item.user') }}" title="Laundry Items"><i class="fas fa-bucket"></i></a>
@endif

<!-- Settings -->
<a href="{{ route('account.edit') }}" title="Settings"><i class="fas fa-cog"></i></a>

<!-- Logout -->
<a href="#" title="Logout" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
    <i class="fas fa-sign-out-alt"></i>
</a>

<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      sidebar.classList.toggle('expanded');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      if (window.innerWidth <= 600) {
        const isClickInside = sidebar.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('expanded')) {
          sidebar.classList.remove('expanded');
        }
      }
    });
  }
});
</script>
