<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Users</title>

    </head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="{{ URL ('images/logo.svg')}}" width="200"></a>            
    </div>
</nav>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            @if (session('message'))
                <div class="alert alert-{{ session('class') }} d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div>
                    </div>
                    {{ session('message') }}
                </div>
            @endif
            <h2>Bienvenido</h2>
            <p>Completa tu registro y forma parte de este gran negocio</p>
            <div class="row pb-3">
                <div class="col"><div class="d-grid gap-2"><a type="button" onclick="history.back()" class="btn btn-primary"> << ANTERIOR</a></div></div>
            </div>  
        </div>
        <div class="col">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>
<script src="{{ asset('js/profilevalidation.js') }}"></script>
@include('../layouts.Footer')
