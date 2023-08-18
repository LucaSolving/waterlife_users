@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <div class="col-10">
        
        <h4>DETALLE DE COMISIONES</h4>
        <p><a href="/my_commissions">« REGRESAR</a></p>
        <p>
        <?php
            foreach ($commissions_user_history_detail as $list) {
                echo '<b>Periodo:</b> '.$list["period"].'<br>
                    <b>ID Socio:</b> '.$list["partner_id"].'<br>
                    <b>Socio:</b> '.$list["partner_name"].'<br>
                    <b>Rango Del Periodo:</b> '.$list["range"].'<br>';
            }
        ?>
            
        </p>
        </div>
        <div class="col-2">
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-secondary">DESCARGAR</button>
            </div>
        </div>
        <div class="col-12 pt-3">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <th scope="col">Tipo</th>
                    <th scope="col">ID Socio</th>
                    <th scope="col">Nombre Socio</th>
                    <th scope="col">Nivel</th>
                    <th scope="col">Nro Pedido</th>
                    <th scope="col">Fecha de Pago</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Puntos</th>
                    <th scope="col">Porcentaje</th>
                    <th scope="col">Comisión Pts.</th>
                    <th scope="col">Comisión S/</th>
                </thead>
                <tbody>
                <?php
                    foreach ($commissions_user_history_detail2 as $list) {
                        echo '<tr>
                            <td>'.$list["type"].'</td>
                            <td>'.$list["partner_id"].'</td>
                            <td>'.$list["partner_name"].'</td>
                            <td>'.$list["level"].'</td>
                            <td>'.$list["id_order"].'</td>
                            <td>'.$list["payment_date"].'</td>
                            <td>S/ '.$list["amount"].'</td>
                            <td>'.$list["points"].'</td>
                            <td>'.$list["percentage"].'%</td>
                            <td>'.$list["commissions_points"].'</td>
                            <td>S/ '.$list["commissions_money"].'</td>
                        </tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('../layouts.Footer')
