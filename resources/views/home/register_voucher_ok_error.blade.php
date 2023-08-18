@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <div class="col-8">
        @if (session('message'))
            <div class="alert alert-{{ session('class') }} d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                <div>
                </div>
                {{ session('message') }}
            </div>
        @endif
    <h4>Registra un Voucher de Pago</h4>
        <p></p>
        <div class="col-8">
            <p>Tu información de pago para el</p>
            <p>Pedido: <b><?php echo $id_order; ?></b><br>No se ha podido registrar correctamente.<br><b></b>
                Por favor valide la informacion he intente nuevamente.
            </p>

            <p></p>

        </div>
            <div class="d-grid gap-4">
                <a href="/register_voucher" class="btn btn-primary">Regresar</a>
            </div>
        </div>
        <div class="col-4">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>


@include('../layouts.Footer')
