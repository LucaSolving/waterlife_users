<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{('https://waterlife.com.pe/css/app.css')}}">
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
        <div class="col-md-6 col-sm-12 logo-top">
			<img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
        <div class="col-md-6 col-sm-12">
            @if($data!='')
                
            <h2>Bienvenido {{ $data->firts_name }}</h2>
            <p>Completa tu registro y forma parte de este gran negocio</p>
        
            <form action="/register_confirmation_ok" method="POST" accept-charset="UTF-8" class="row g-3 needs-validation" novalidate>  
                @csrf
                <input type="hidden" name="txt" value="{{ $data->id }}">
                <div class="mb-3">
                    <input class="form-control" type="text" value="Consultor Patrocinador: {{ $name_sponsor }}" aria-label="Disabled input example" disabled readonly>
                </div>       
                <hr>           
                <div class="row pb-3">
                    <div class="col">
                        <input class="form-control" type="text" value="Tipo de Documento: {{ $data->type_doc }}" aria-label="Disabled input example" disabled readonly>
                    </div>
                    <div class="col">
                        <input type="text" name="num_doc" class="form-control" placeholder="{{ $data->num_doc }}" disabled readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" name="firts_name" class="form-control" placeholder="{{ $data->firts_name }}" disabled readonly>
                </div>
                <div class="mb-3">
                    <input type="text" name="last_name" class="form-control" placeholder="{{ $data->last_name }}" disabled readonly>
                </div>
                <div class="mb-3">
                    <input type="text" name="mother_last_name" class="form-control" placeholder="{{ $data->mother_last_name }}" disabled readonly>
                </div>
                <div class="row pb-3">
                    <div class="col">
                        Fecha de Nacimiento *
                        <input class="form-control" type="date" name="birth_date" required>
                    </div>
                    <div class="col">Sexo *
                        <div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="F" required>
                            <label class="form-check-label" for="inlineRadio1">F</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="M" required>
                            <label class="form-check-label" for="inlineRadio2">M</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="{{ $data->email }}" disabled readonly>
                </div>
                <div class="row pb-3">
                    <div class="col">
                        <input type="text" name="phone" class="form-control" placeholder="Número de Celular *" required>
                    </div>
                    <div class="col">
                        <select name="phone_operator" class="form-select" required>
                            <option value="">Operador *</option>
                            <option value="Movistar">Movistar</option>
                            <option value="Claro">Claro</option>
                            <option value="Entel">Entel</option>
                            <option value="Bitel">Bitel</option>
                        </select>
                    </div>
                </div>
                <div class="row pb-3">
                    <div class="col">
                        <select id="department" name="department" class="form-select" required>
                            <option value="">Departamento</option>
                            <?php
                                foreach ($departments as $department) {
                                    echo '<option value="'.$department["id_department"].'">'.$department["department"].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <select id="province" name="province" class="form-select" required>
                            <option value="">Provincia</option>
                        </select>
                    </div>
                    <div class="col">
                        <select id="district" name="district" class="form-select" required>
                            <option value="">Distrito</option>
                        </select>
                    </div>
                </div>               
                <div class="mb-3">
                    <input type="text" name="address" class="form-control" placeholder="Dirección *" required>
                </div>                
                <div class="mb-3">
                    <input type="text" name="address_reference" class="form-control" placeholder="Referencia de cómo llegar">
                </div>
                <hr>
                <!--p>Registra tu información bancaría donde depositaremos tus comisiones:</p>               
                <div class="row pb-3">
                    <div class="col">
                        <select name="bank" class="form-select">
                            <option value="">Banco</option>
                            <option value="Scotiabank">Scotiabank</option>
                            <option value="BBVA">BBVA</option>
                            <option value="Interbank">Interbank</option>
                            <option value="BanBif">BanBif</option>
                        </select>
                    </div>
                    <div class="col">
                        <select name="type_account" class="form-select">
                            <option value="">Tipo de Cuenta</option>
                            <option value="Ahorros">Ahorros</option>
                            <option value="Corriente">Corriente</option>
                        </select>
                    </div>
                </div>            
                <div class="mb-3">
                    <input type="text" name="nro_account" class="form-control" placeholder="Nro de Cuenta">
                </div>                
                <div class="mb-3">
                    <input type="text" name="cci_account" class="form-control" placeholder="Nro de Cuenta (CCI)">
                </div>
                <hr>
                <p>Medio de pago de Afiliación:</p>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="method_payment" value="1" id="method_payment1" required>
                        <label class="form-check-label" for="method_payment1">
                            Tarjeta de Crédito
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="method_payment" value="2" id="method_payment2" required>
                        <label class="form-check-label" for="method_payment2">
                            Transferencia Bancaria
                        </label>
                    </div>
                </div-->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">CONTINUAR</button>                    
                </div>  
            </form>
            @else
                Esta invitación ha la red de Waterlife ya ha sido usado.
            @endif
        </div>
    </div>
</div>
<script src="{{ asset('js/profilevalidation.js') }}"></script>
@include('../layouts.Footer')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('#department').on('change', function () {

        var id_departament = this.value;
        
        $.ajax({
          url: "{{url('provincesservices')}}/" + id_departament,
          type:'get',
          success: function (result) {
            $("#province").html('');
            $("#district").html('');
            $("#province").append('<option value="">Provincia</option>');
            $("#district").append('<option value="">Distrito</option>');
            $.each(result, function (key, value) {
                $("#province").append('<option value="' + value.id_province + '">' + value.province + '</option>');
            });
          },
          statusCode: {
             404: function() {
                alert('Web not found');
             }
          },
          error:function(x,xs,xt){
              //nos dara el error si es que hay alguno
              //window.open(JSON.stringify('x'));
          }
       });
    });

    $('#province').on('change', function () {

        var id_province = this.value;

        $.ajax({
            url: "{{url('districtsservices')}}/" + id_province,
            type:'get',
            success: function (result2) {
                $("#district").html('');
                $("#district").append('<option value="">Distrito</option>');
                $.each(result2, function (key2, value2) {
                    $("#district").append('<option value="' + value2.id_district + '">' + value2.district + '</option>');
                });
            },
            statusCode: {
                404: function() {
                    alert('Web not found');
                }
            },
            error:function(x,xs,xt){
                //nos dara el error si es que hay alguno
                //window.open(JSON.stringify('x'));
            }
        });
    });

    
});
</script>