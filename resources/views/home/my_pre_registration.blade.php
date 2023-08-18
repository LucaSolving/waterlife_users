@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h4>MIS PRE-REGISTRO</h4>
        <p></p>
        <div class="col-12 pt-3">
            <table id="myTable" class="table table-bordered text-center table-sm">
                <thead class="table-light">
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Email</th>
                    <th scope="col">Acciones</th>
                </thead>
                <tbody>
                    @foreach ($registrations as $registration )
                        <tr>
                            <td>{{$registration->id}}</td>
                            <td>{{$registration->firts_name}}</td>
                            <td>{{$registration->last_name}}</td>
                            <td>{{$registration->email}}</td>
                            <td><a href="#"><i class="bi bi-envelope-check-fill"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <nav class="">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
            </nav> --}}
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
    $(document).ready( function () {
        $('#myTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            searching: false,
            responsive: true,
        });
    } );
    /* $('.datepicker').datepicker(); */
</script>
@include('../layouts.Footer')
