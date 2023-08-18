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
            <p>Medio de Pago elegido: Transferencia Bancaria</p>
            <p><i>Cuentas WaterLife</i></p>
            <ul>
                <li>BCP Cta. Cte.: 000-03040230-12 / CCI: 0002-3424332232-24234-22</li>
                <li>Interbank Cta. Cte.: 000-03040230-12 / CCI: 0002-3424332232-24234-22</li>
                <li>Scotiabank Cta. Cte.: 000-03040230-12 / CCI: 0002-3424332232-24234-22</li>
            </ul>
            <form action="/transactions_confirmation_ok" method="POST" accept-charset="UTF-8" class=" needs-validation" novalidate>  
                @csrf
                <input type="hidden" name="partner_id" value="{{ $data->id }}">
                <input type="hidden" name="partner_name" value="{{ $data->firts_name.' '.$data->last_name }}">
                <input type="hidden" name="total_amount" value="<?php echo $product_affiliation_price; ?>">
                <input type="hidden" name="total_points" value="<?php echo $product_affiliation_points; ?>">
                <input type="hidden" name="payment_method" value="2">
                <div class="row pb-3">
                    <p>Registra tu voucher de pago para que sea validado.</p>
                    <div id="orders_affiliation"></div>
                    <div class="col">
                        <select name="bank" id="bank" class="form-select" required>
                            <option value="">Banco</option>
                            <option value="Scotiabank">Scotiabank</option>
                            <option value="BBVA">BBVA</option>
                            <option value="Interbank">Interbank</option>
                            <option value="BanBif">BanBif</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" name="operation_number" id="operation_number" class="form-control" placeholder="Nro de Operación" required>
                    </div>
                </div>
                <div class="row pb-3">
                    <div class="col"><div class="d-grid gap-2"><button type="button" onclick="history.back()" class="btn btn-primary"> << ANTERIOR</button></div></div>
                    <div class="col"><div class="d-grid gap-2"><button type="submit" class="btn btn-primary">PAGAR</button></div></div>
                </div>  
            </form>
        </div>
        <div class="col">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>
<script src="{{ asset('js/profilevalidation.js') }}"></script>
@include('../layouts.Footer')
