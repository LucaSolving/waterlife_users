<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <style>
             body {
                font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                font-size: 12px;
                line-height: 1.4;
            }
            table {
                border-collapse: collapse;
            }
            .table-width {
                width: 700px;
            }
            .items-width {
                width: 90px;
            }
            #items-table thead td, #items-table tfoot td {
                border: 1px solid black;
            }
            .text-center {
                text-align: center;
            }
            .text-right, .text-right td {
                text-align: right;
            }
            #items-table tbody td {
                border-left: 1px solid black;
                border-right: 1px solid black;
            }
            .td-table {
                width: 50%;
                padding: 0px;
            }
            #invoice-title {
                border: 0px !important;
                font-size: 24px;
                font-weight: bold;
            }
            .invisible {
                visibility: hidden;
            }
            .no-border, .no-border td {
                border: 0px !important;
            }
            .no-border-left, .no-border-left td {
                border-left: 0px !important;
            }
            .no-border-right, .no-border-right td {
                border-right: 0px !important;
            }
            .no-border-top, .no-border-top td {
                border-top: 0px !important;
            }
            .no-border-bottom, .no-border-bottom td {
                border-bottom: 0px !important;
            }
            .invoice-border-top td {
                border: 0px !important;
                border-top: 1px solid black !important;
            }
            .invoice-border-bottom td {
                border: 0px !important;
                border-bottom: 2px solid black !important;
            }
            .total-cell {
                font-size: 15px;
            }
            .total-padding-left {
                padding-left: 10px;
            }
            .total-padding-right {
                padding-right: 10px;
            }
            td {
                padding: 2px 4px;
            }
            .firstLine td{
                border-bottom: 1px solid rgb(90, 90, 90);
            }
            .table-right {
                width: auto;
                margin-right: 0px;
                margin-left: auto;
            }
            .no-padding td {
                padding: 0px;
            }
            .half-width {
                width: 50%;
            }
            .padding-left {
                padding-left: 10px;
            }
            .invoice-padding-left {
                padding-left: 125px;
            }
            .padding-right {
                padding-right: 10px;
            }

            @page { margin: 30px 50px 50px 50px; }
            .info { position: absolute; top: 0px; }
            footer { position: fixed; bottom: 0px; }
            footer .pagenum:before {
                content: counter(page);
            }
            .warning {
                background: rgb(97, 146, 238);
            }
            .let{
                font-weight: normal;
                font-weight: 900;
                font: normal 120%;
            },
        </style>
    </head>
    <body>
    <nav class="navbar bg-light fixed-top">
        <div class="container-fluid">
            <div class="col-3">
                <div class="col-9">
                    &nbsp;<a class="navbar-brand" href="#"><img src="{{ public_path('images/logo.svg')}}" width="200"></a>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-3">
                            <div aria-labelledby="offcanvasNavbarLabel" class="offcanvas offcanvas-end" id="offcanvasNavbar" tabindex="-1">
                                <p>&nbsp;</p>
                                <span style="color: #000000;"><strong>Detalle de Pedido:</strong>&nbsp;</span>
                                <span style="background-color: #ecf0f1;">
                                    <strong>
                                        @foreach ($orders_pending_detail as $list)
                                            @if( $list['type_member'] == 1)
                                                <strong>Paga el Socio</strong>
                                            @else
                                                <strong class="text-primary">Paga el Cliente</strong>
                                            @endif
                                        @endforeach
                                    </strong>
                                </span>
                            </div>
                            <p><br></p>
                            <p><br></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="col-10">
                    <div class="row pb-3">
                            <div class="col-2">
                                <label class="col-form-label"><strong>Estado del Pago :</strong></label>
                                <?php
                                    foreach ($orders_pending_detail as $list) {
                                        echo '<input type="text" value="'.$list['payment_status'].'" class="form-control" readonly>';
                                    }
                                ?>
                                <label class="col-form-label">
                                    <strong>Estado de la Entrega :</strong>
                                </label>
                                    <?php
                                        foreach ($orders_pending_detail as $list) {
                                            echo '<input type="text" value="'.$list['delivery_status'].'" class="form-control"  readonly>';
                                        }
                                    ?>
                            </div>
                        <div class="col-2">
                            <div class="d-grid gap-2"><input class="form-control" type="hidden" value="546"></div>
                        </div>
                    </div>
                </div>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <div class="col-10">
                <hr>
            </div>
            <p><strong>DATOS DE ENV&Iacute;O</strong></p>
            <div class="col-12">
                <table class="table-width items-table" style="width: 100%;" id="items-table">
                    <thead>
                        <tr class="bank_statements_header warning text-center">
                            <th>Persona de Contacto</th>
                            <th>Tel&eacute;fono de contacto</th>
                            <th>Direcci&oacute;n</th>
                            <th>Referencia</th>
                            <th>Indicaciones</th>
                            <th>Distrito</th>
                            <th>Provincia</th>
                            <th>Departamento</th>
                        </tr>
                    </thead>
                    <thead>
                        <tbody>
                            <?php
                                foreach ($orders_pending_detail as $list) {
                                    echo '
                                        <tr class="firstLine">
                                            <td>'.$list['person_client'].'</td>
                                            <td>'.$list['phone_number_client'].'</td>
                                            <td>'.$list['address'].'</td>
                                            <td>'.$list["reference"].'</td>
                                            <td>'.$list["indication"].'</td>
                                            <td>'.$list["district"].'</td>
                                            <td>'.$list["province"].'</td>
                                            <td>'.$list["department"].'</td>
                                        </tr>
                                    ';
                                }
                            ?>
                        </tbody>
                    </thead>
                </table>
            </div>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-10">
                        <p><strong>PEDIDO</strong></p>
                    </div>
                </div>
                <table class="table-width items-table"  id="items-table">
                    <thead>
                        <tr class="bank_statements_header warning text-center">
                            <th style="width: 8.46824%;">Nro. Pedido</th>
                            <th style="width: 12.4533%;">Fecha de Pedido</th>
                            <th style="width: 10.47572%;">ID Socio</th>
                            <th style="width: 18.9589%;">Socio</th>
                            <th style="width: 16.09465%;">Monto Total</th>
                            <th style="width: 1.9477%;">Descuento Obtenido</th>
                            <th style="width: 2.3138%;">Monto Total con Descuento</th>
                            <th style="width: 10.34745%;">Puntos</th>
                            <th style="width: 10.9402%;">Medio de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($orders_pending_detail as $list) {
                                echo '
                                <tr class="firstLine">
                                    <td style="width: 8.46824%;">'.$list['id'].'</td>
                                    <td style="width: 12.4533%;">'.date('d/m/Y', strtotime($list["order_date"])).'</td>
                                    <td style="width: 6.47572%;">'.$list["partner_id"].'</td>
                                    <td style="width: 10.9589%;">'.$list["partner_name"].'</td>
                                    <td style="width: 8.09465%;">S/ '.$list["total"].'</td>
                                    <td style="width: 13.9477%;">'.$list["discount_applied"].'%</td>
                                    <td style="width: 16.3138%;">S/ '.$list["total_amount"].'</td>
                                    <td style="width: 7.34745%;">'.$list["total_points"].'</td>
                                    <td style="width: 15.9402%;">Dep&oacute;sito / Transferencia</td>
                                </tr>
                                ';
                            }
                        ?>
                    </tbody>
                </table>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <div class="col-12">
                    <hr>
                </div>
                <table class="table-width items-table"  id="items-table" >
                    <thead>
                        <tr class="bank_statements_header warning text-center">
                            <th style="width: 7.00876%;">Item</th>
                            <th style="width: 7.38423%;">SKU</th>
                            <th style="width: 19.0238%;">Producto</th>
                            <th style="width: 14.0175%;">Cantidad</th>
                            <th style="width: 21.9584%;">Precio Unitario</th>
                            <th style="width: 19.5244%;">Importe Total</th>
                            <th style="width: 11.0881%;">Puntos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i_products = 1;
                            foreach ($orders_pending_detail_products as $products) {
                                echo '
                                    <tr class="firstLine">
                                        <td style="width: 7.00876%;">'.$i_products.'</td>
                                        <td style="width: 7.38423%;">'.$products["sku"].'</td>
                                        <td style="width: 19.0238%;">'.$products["product"].'</td>
                                        <td style="width: 14.0175%;">'.$products["quantity"].'</td>
                                        <td style="width: 21.9584%;">S/ '.$products["unit_price"].'</td>
                                        <td style="width: 19.5244%;">S/ '.$products["total_import"].'</td>
                                        <td style="width: 11.0881%;">'.$products["points"].'</td>
                                    </tr>';
                                    $i_products++;
                            }
                        ?>
                        <?php
                            foreach ($orders_pending_detail as $list) {
                                echo '
                                    <tr class="firstLine">
                                        <td style="width: 7.00876%;">'.$i_products.'</td>
                                        <td style="width: 7.38423%;">'.$list['id'].'</td>
                                        <td style="width: 19.0238%;">'.$list['name_delivery'].'</td>
                                        <td style="width: 14.0175%;">1</td>
                                        <td style="width: 21.9584%;">S/ '.$list['cost_delivery'].'</td>
                                        <td style="width: 19.5244%;">S/ '.$list['cost_delivery'].'</td>
                                        <td style="width: 11.0881%;">0</td>
                                    </tr>
                                ';
                            }
                        ?>
                    </tbody>
                </table>
                <div class="col-12 pt-3">&nbsp;</div>
            </div>
        </div>
    </div>
    <footer class="text-muted py-5">
        <div class="container">
            <p class="mb-1">Copyright &copy; 2022 Waterlife Per&uacute;</p>
        </div>
    </footer>
    </body>
</html>

