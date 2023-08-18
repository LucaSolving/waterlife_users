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
        <div class="col-12">
            <div class="row">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <th scope="col">Nro</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Nro Pedido</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        foreach ($orderlist as $list) {

                            echo '<tr>
                                    <td>'.$i.'</td>
                                    <td>'.date('d/m/Y', strtotime($list["order_date"])).'</td>
                                    <td>'.$list["id"].'</td>
                                    <td>S/ '.$list["total_amount"].'</td>
                                    <td>';

                                    if($list["payment_status"]=='Pendiente'){
                                        echo 'Pendiente de Pago';
                                    }else{
                                        echo 'En revisión';
                                    }
                                    
                                echo '</td>
                                    <td>';

                                    if($list["payment_status"]=='Pendiente'){
                                        echo '<a href="#" class="link-primary" data-bs-toggle="modal" data-bs-target="#exampleModal'.$list["id"].'">Registrar pago</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="/register_voucher_del/'.$list["id"].'" class="link-primary">Eliminar Orden</a>';
                                    }
                                    
                                echo '
                                    </td>
                                </tr>';
                            $i++;

                            echo '<!-- Modal Registrar Pago -->
                            <div class="modal fade" id="exampleModal'.$list["id"].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5>Registra tu Pago</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form action="/register_voucher_ok" method="POST">'; ?>
                                  @csrf
                            <?php
                                echo '<div class="modal-body" class="row g-3 needs-validation" novalidate>        
                                    <p>Registra tu voucher de pago para el pedido: <b>'.$list["id"].'</b></p>        
                                    <div class="row pb-3">
                                        <div class="col">
                                            <select name="bank" class="form-select" required>
                                                <option value="">Banco</option>
                                                <option value="Scotiabank">Scotiabank</option>
                                                <option value="BBVA">BBVA</option>
                                                <option value="Interbank">Interbank</option>
                                                <option value="BanBif">BanBif</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="text" name="operation_number" class="form-control" placeholder="Nro de Operación" required>
                                        </div>
                                    </div>    
                                  </div>
                                  <div class="modal-footer">
                                    <input type="hidden" name="id" value="'.$list["id"].'" class="form-control">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">REGISTRAR PAGO</button>
                                  </div>
                                  </form>
                                </div>
                              </div>
                            </div>';
                        }
                    ?>
                </tbody>
            </table>
            <nav class="">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
            </nav>
            </div>
        </div>
        <!--div class="col-4 text-center" style="padding-left: 50px;">
            Espacio libre
        </div-->
    </div>
</div>
<script src="{{ asset('js/profilevalidation.js') }}"></script>
@include('../layouts.Footer')