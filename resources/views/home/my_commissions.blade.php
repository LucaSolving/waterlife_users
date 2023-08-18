@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h4>MIS COMISIONES</h4>
        <p></p>
        <div class="col-12 pt-3">
            <table id="myOrders" class="table table-bordered text-center table-sm">
                <thead class="table-light">
                    <th scope="col">ID</th>
                    <th scope="col">Periodo</th>
                    <th scope="col">Fecha de Proceso</th>
                    <th scope="col">Rango</th>
                    <th scope="col">Pts. Grupal<br>1er Nivel</th>
                    <th scope="col">Pts. Grupal<br>2do Nivel</th>
                    <th scope="col">Comisión Red<br>1er Nivel</th>
                    <th scope="col">Comisión Red<br>2do Nivel</th>
                    <th scope="col">Comisión Ventas</th>
                    <th scope="col">TOTAL COMISIONES</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </thead>
            </table>
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
            url: "{{url('/data_my_commissions')}}",
            method: "GET",
            contentType: "aplication/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(data) {
                //alert(data);
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
                {"data" : "id"},
                {"data" : "period"},
                {"data" : "updated_at"},
                {"data" : "range"},
                {"data" : "profit_1_level"},
                {"data" : "profit_2_level"},
                {"data" : "commissions_1_level"},
                {"data" : "commissions_2_level"},
                {"data" : "sales_commissions"},
                {"data" : "total_commissions"},
                {"data" : "status"},
                {
                    "data": null,
                    "bSortable": false,
                    "mRender": function(data, type, value) {
                        return `<a href="/my_commissions_detail/${value['id']}" class="link-primary"><i class="bi bi-eye-fill"></i></a>`;
                    }
                },
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            searching: false,
        });
    }

</script>
@include('../layouts.Footer')
