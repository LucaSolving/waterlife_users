<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="{{asset('css/app.css')}}">
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
        <div class="col-md-6 col-sm-12 logo-top">
			<img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
        <div class="col-md-6 col-sm-12">
            <h2>Bienvenido</h2>
            <p>Inicia sesión y empieza a construir tu futuro.</p>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-{{ session('class') }} alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            <form method="POST" action="/login">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Usuario">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary button-active">INGRESAR</button>
                    <a href="#" class="link-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Olvidé mi contraseña</a>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-sm-12 logo-bottom">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>
<footer class="text-muted py-5">
    <div class="container">
        <p class="mb-1">Copyright © 2022 Waterlife Perú</p>
    </div>
</footer>

<!-- Modal Recovery Password -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Olvidé mi contraseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/login_recovery" method="POST">
      @csrf
      <div class="modal-body">
        <div class="mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.blunde.min.js"></script>
</body>
</html>
