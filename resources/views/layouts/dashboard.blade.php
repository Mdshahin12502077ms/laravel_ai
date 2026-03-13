@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">
                            <span data-feather="home"></span>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#dropdownMenu" aria-expanded="false">
                            <span data-feather="file"></span>
                            Menu Dropdown
                        </a>
                        <ul class="collapse list-unstyled ps-3" id="dropdownMenu">
                            <li><a class="nav-link" href="#">Subitem 1</a></li>
                            <li><a class="nav-link" href="#">Subitem 2</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="users"></span>
                            Users
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>
            <!-- CRUD content will go here -->
            <div id="crud-content">
                <!-- AJAX CRUD Table -->
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Example AJAX setup for CRUD
// Replace with your actual endpoints and logic
$(document).ready(function() {
    // Fetch items
    function fetchItems() {
        $.ajax({
            url: '/items',
            method: 'GET',
            success: function(data) {
                $('#crud-content').html(data);
            }
        });
    }
    fetchItems();

    // Add more AJAX CRUD logic here
});
</script>
@endpush
