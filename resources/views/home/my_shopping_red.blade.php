@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <div class="col-md-2 col-5"><a href="/my_shopping" class="fs-7">MIS COMPRAS</a>&nbsp;&nbsp;&nbsp;|</div>
        <div class="col-md-9 col-7">
            <p class="fs-7"><strong>Ver compras de mi red</strong></p>
        </div>


        <div class="col-md-12">
            <form action="/register_confirmation_ok" method="POST">
            @csrf
            <div class="row pb-3">
                <div class="col-md-2 col-6">
                    <input type="month" class="form-control" placeholder="Periodo" name="" id="periodo">
                </div>
                <div class="col-md-2 col-6">
                    <input type="date" class="form-control" name="fecha" id="fecha">
                </div>
                <div class="col-md-3 col-12">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <button type="button" onclick="filtrar()" class="btn btn-secondary filter-margin" style="width: 100%">FILTRAR</button>
                        </div>
                        <div class="col-md-6 col-12">
                            <button type="button" onclick="restablecer()" class="btn btn-secondary filter-margin" style="width: 100%">RESTABLECER</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <input type="text" class="form-control" id="id_socio" placeholder="ID Socio">
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <button type="button" class="btn btn-secondary filter-margin" onclick="filtrarSocio()" style="width: 100%">BUSCAR</button>
                        </div>
                        <div class="col-md-6 col-12">
                            <a type="button" href="https://lucasolving.com/onlinebak/excel-generator/my-shopping-red.php?id={{auth()->user()->id}}" class="btn btn-secondary info-socio">DESCARGAR</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>

        <div class="col-md-12 pt-3">
            <table class="table table-bordered text-center" id="myOrders">
                <thead class="table-light">
                    <th scope="col">ID Socio</th>
                    <th scope="col">Nro Pedido</th>
                    <th scope="col">Periodo</th>
                    <th scope="col">Fecha Pedido</th>
                    <th scope="col">Fecha de Pago</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Puntos</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Entrega</th>
                    <th scope="col">Acciones</th>
                </thead>


            </table>
        </div>
    </div>
	<div class="modal fade" id="modal_detail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-body">
	            <h3 class="text-center">Nro de Pedido <br> <div id="nro_pedido_modal"></div> </h3>
	            <p class="text-center">Fecha: <b id="fecha_modal"></b></p>
	            <div class="row mx-3 mb-0" id="show_products">
	            </div>
                <hr>
	            <div class="row mx-3 mb-2">
	                <div class="col-md-9">SUBTOTAL</div>
	                <div class="col-md-3" id="subtotal_amount_modal"></div>
	                <div class="col-md-6 text-danger">DESCUENTO</div>
	                <div class="col-md-3 text-danger" id="discount_applied_modal"></div>
	                <div class="col-md-3 text-danger" id="discount_amount_modal"></div>
	                <div class=col-md-6>TOTAL</div>
	                <div class="col-md-3" id="total_points_modal"></div>
	                <div class=col-md-3 id="total_modal"></div>
	            </div>
	            <div class="row mx-3 mb-3">
	                <div class=col-md-6>Delivery</div><div class=col-md-3>0pts.</div><div class=col-md-3 id="cost_delivery"></div>
	            </div>
                <hr>
	            <div class="row mx-3 mb-2">
	                <div class=col-md-9>TOTAL A PAGAR</div>
	                <div class=col-md-3 id="total_amount_modal"></div>
	            </div>
	            <div class="row mx-3 mb-2">
	                <div class=col-md-9>Método de Pago:</div>
	                <div class=col-md-3>Tarjeta</div>
	            </div>
	            <hr class="mx-3">
	            <div class="row mx-3 mb-2">
	                <div class=col-md-12><b>Dirección:</b> <p id="address_modal"></p></div>
	            </div>
	            <div class="row mx-3 mb-2">
	                <div class=col-md-12><b>Referencia:</b> <p id="reference_modal"></p></div>
	            </div>
	            <div class="row mx-3 mb-2">
	                <div class=col-md-12><b>Indicaciones:</b> <p id="indication_modal"></p</div>
	            </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	                <a id="link_download" class="btn btn-secondary">DESCARGAR PDF</a>
	            </div>
	        </div>
	    </div>
	</div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://unpkg.com/dayjs@1.9.4/locale/es.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/zf/dt-1.13.1/r-2.4.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/zf/dt-1.13.1/r-2.4.0/datatables.min.js"></script>
<script>
    let myOrders = [];

    dayjs.locale("es");

    $(document).ready (function() {
        getMyOrders();
    });

    function getMyOrders() {
        $.ajax({
            url: "{{url('/data_my_shopping_red')}}",
            method: "GET",
            contentType: "aplication/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(data) {
                
                /*alert(data);
                $.each(data, function(index, value){                                                
                    alert("may: " + value)
                });*/
                myOrders = data;
                console.log(myOrders);
                $("#myOrders").dataTable().fnDestroy();
                tableMyOrders(myOrders)
            }
        });
    }

    function tableMyOrders(myOrders){
        var tableProducts = $('#myOrders').dataTable( {
            data : myOrders,
            "bSort" : false,
            responsive: true,
            columns: [
                {"data" : "partner_id"},
                {"data" : "id"},
                {"data" : "periodo"},
                {"data" : "order_date"},
                {"data" : "payment_date"},
                {"data" : "total_amount"},
                {"data" : "total_points"},
                {"data" : "payment_status"},
                {"data" : "delivery_status"},
                {
                    "data": null,
                    "bSortable": false,
                    "mRender": function(data, type, value) {
                        //return `<a href="#" class="link-primary" onclick="openModal(${value['id']},'${value['fecha']}',${value['cost_delivery']},${value['total_amount']},${value['discount_applied']},${value['total']},${value['total_points']},${value['address']},${value['reference']},${value['indication']})" ><i class="bi bi-eye-fill"></i></a>`;
                        return `<a href="#" class="link-primary" onclick="openModal(${value['id']},'${value['order_date']}',${value['cost_delivery']},${value['subtotal']},${value['total_amount']},${value['discount_applied']},${value['discount_amount']},${value['total']},${value['total_points']},'${value['address']}','${value['reference']}','${value['indication']}')" ><i class="bi bi-eye-fill"></i></a>`;
                    }
                },
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            searching: false,
        });
    }

    //function openModal(id, fecha, cost_delivery, total_amount,discount_applied,total,total_points, address,reference,indication)
    function openModal(id, fecha, cost_delivery, subtotal, total_amount,discount_applied,discount_amount,total,total_points, address,reference,indication)
    {

        $("#nro_pedido_modal").empty()
        $("#fecha_modal").empty()
        $("#cost_delivery").empty()
        $("#subtotal_amount_modal").empty()
        $("#total_amount_modal").empty()
        $("#discount_applied_modal").empty()
        $("#discount_amount_modal").empty()
        $("#total_modal").empty()
        $("#total_points_modal").empty()
        $("#address_modal").empty()
        $("#reference_modal").empty()
        $("#indication_modal").empty()
        $('#modal_detail').modal('show');
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
            }
        }),
        $.ajax({
            url: "http://26.220.92.239:8003/my_shopping_detail/" + id,
            type:"get",
            beforeSend: function(){
                $("#show_products").html("<div id='loading'>Loading...</div>");
            },
            success: function (product) {
                console.log(product)
                $.each(product, function(index, detail){
                    $("#show_products").append("<div class=col-md-6> " + detail["product"] + " x" + detail["quantity"] + "</div><div class=col-md-3>" + detail["points"] + "pts.</div><div class=col-md-3>S/ " + detail["total_import"] + "</div>");
                    $("#loading").empty()
                });
                $("#nro_pedido_modal").append("<p>"+id+"</p>")
                $("#fecha_modal").append(fecha)
                $("#cost_delivery").append('S/ '+cost_delivery.toFixed(2))
                $("#subtotal_amount_modal").append('S/ '+subtotal.toFixed(2))
                $("#total_amount_modal").append('S/ '+total_amount.toFixed(2))
                $("#discount_applied_modal").append(discount_applied+'%')
                $("#discount_amount_modal").append('-S/ '+discount_amount.toFixed(2))
                $("#total_modal").append('S/ '+total.toFixed(2))
                $("#total_points_modal").append(total_points+'pts.')
                $("#address_modal").append(address)
                $("#reference_modal").append(reference)
                $("#indication_modal").append(indication)
                $("#link_download").attr("href", "/order_detail_print/"+id)
            },
            statusCode: {
                404: function() {
                    alert("Web not found");
                }
            },
            error:function(x,xs,xt){
                //nos dara el error si es que hay alguno
                //window.open(JSON.stringify("x"));
            }

            //https://upload.wikimedia.org/wikipedia/commons/b/b9/Youtube_loading_symbol_1_(wobbly).gif
        });
    }

    function filtrar(){
        let periodo = document.getElementById('periodo').value;
        let fecha = document.getElementById('fecha').value;


        let orderFilter = [];

        if (periodo) {
            orderFilter = myOrders.filter(item => {
               let newPeriodo = dayjs(periodo).format('MMMM[ de ]YYYY')
                return item.periodo.includes(newPeriodo)
            })
        }

        if (fecha) {
            orderFilter = myOrders.filter(item => {
                console.log(fecha);
                return item.fecha.includes(fecha)
            })
        }

        $("#myOrders").dataTable().fnDestroy()
        tableMyOrders(orderFilter)
    }

    function filtrarSocio(){
        //alert('hola');
        let id_socio = document.getElementById('id_socio').value;
        console.log(id_socio)
        let orderFilter = [];

        if (id_socio) {

            orderFilter = myOrders.filter(item => {
                return item.partner_id.toString().includes(id_socio.toString());
            })
        }

        if(!id_socio){
            $("#myOrders").dataTable().fnDestroy()
            getMyOrders()
            return;
        }

        $("#myOrders").dataTable().fnDestroy()
        tableMyOrders(orderFilter)
    }

    function restablecer() {
        let periodo = document.getElementById('periodo').value = '';
        let fecha = document.getElementById('fecha').value = '';
        $("#myOrders").dataTable().fnDestroy()
        getMyOrders()
    }
</script>

<script>

</script>

@include('../layouts.Footer')
