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
            <h2>Bienvenido {{ $data->firts_name }}</h2>
            <p>Completa tu registro y forma parte de este gran negocio</p>

            <p><b>Resumen de tu compra</b></p>
            <p>1 Kit de Afiliación red Water Life: S/<?php echo $product_affiliation_price; ?></p>
            <hr>
            <p>Medio de Pago elegido: Tarjeta de Crédito</p>
            <form action="/payment_confirmation_ok" method="POST" accept-charset="UTF-8" class=" needs-validation" novalidate>  
                @csrf
                <input type="hidden" name="txt" value="{{ $data->id }}">
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_email_sponsor" value="1" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        Al hacer click en PAGAR estás aceptando los <a href="/term">Términos y Condiciones</a> de la red Water Life.
                        </label>
                    </div>                    
                </div>  
                <div class="row pb-3">
                    <div class="col"><div class="d-grid gap-2"><button type="button" class="btn btn-primary"> << ANTERIOR</button></div></div>
                    <div class="col"><div class="d-grid gap-2"><button type="submit" class="btn btn-primary">PAGAR</button></div></div>
                </div>  
            </form>
        </div>
        <div class="col">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
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