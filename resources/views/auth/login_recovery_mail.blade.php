<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{('https://waterlife.com.pe/css/app.css')}}">
        <title>Admin</title>

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
            <form action="/login_recovery_mail_ok" method="POST"> 
                @csrf                   
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Nueva Contraseña">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Confirme su Nueva Contraseña">
                </div>          
                <div class="d-grid gap-2">
                    <input type="hidden" name="mail_token" value="{{$mail_token}}">
                    <button type="submit" class="btn btn-primary">ACEPTAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="text-muted py-5">
    <div class="container">
        <p class="mb-1">Copyright © 2022 Waterlife Perú</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.blunde.min.js"></script>
</body>
</html>