@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h4>Registra un Voucher de Pago</h4>
        <p></p>
        <div class="col-8">
            <p>Tu información de pago para</p>
            <p>Pedido: <b><?php echo $id_order; ?></b><br>Ha sido registrada.<br>
            En breve estaremos verificando la transacción y podrás ver la confirmación en el menú <b>"Mis Compras"</b>.
            </p>

            <p>Te enviaremos un correo electrónico con la confirmación</p>

            <div class="d-grid gap-2">
                <a href="/dashboard" class="btn btn-primary">FINALIZAR</a>
            </div> 
        </div>
        <div class="col-4">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>


@include('../layouts.Footer')