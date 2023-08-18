@include('../layouts.Assets')
<style>
    .progress {
        margin-top: 91px;
    }
    .progress-bar {
        background-color: #0d6efd00;
    }
    .circular--square {
        border-radius: 50%;
    }
    .circular--portrait {
        background-position: center;
        width: 207px;
        height: 199px;
        margin-left: 77px;
    }
    .circular--portrait img {
        width: 100%;
        height: auto;
    }

    @media (max-width: 540px) {
        .progress {
            margin-top: 10px;
        }
    }
</style>

<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h4 class="dashboard-title">Edita tu perfil</h4>
        <p></p>
        <div class="col-md-8 col-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-{{ session('class') }} d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div>
                    </div>
                    {{ session('message') }}
                </div>
            @endif
            <div class="row pb-3">
                <div class="col-6">Tipo de Documento: <b>{{$users->type_doc}}</b></div>
                <div class="col-6">Nro de Documento: <b>{{$users->num_doc}}</b></div>
            </div>
            <hr class="info-socio">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8">
                    <form action="{{ url('/profile/updatepassword/'.$users->id)}}"  accept-charset="UTF-8" method="POST"  class="row g-3 needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <input type="password"  name="password_now" class="form-control" placeholder="Contraseña actual" required>
                        </div>
                        <div class="mb-3">
                            <input type="password"  id="field" name="password" class="form-control" placeholder="Nueva contraseña" required >
                        </div>
                        <div class="mb-3">
                            <input type="password" id="field" name="password_confirmation" class="form-control" placeholder="Repetir nueva contraseña" required>
                        </div>
                        <div class="mb-3 info-socio-responsive">
                            <div class="progress col">
                                <div  id="fieldstarResponsive" class="progress-bar" ></div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary button-active">CAMBIAR CONTRASEÑA</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-4 info-socio">
                    <div class="progress col">
                        <div  id="fieldstar" class="progress-bar" ></div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-8 col-12">
                    <form action="{{ url('/profile/edit/'.$users->id)}}"  accept-charset="UTF-8" method="POST" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <input  type="text" name="firts_name" class="form-control" placeholder="Nombres" value="{{ $users->firts_name }}" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="last_name" class="form-control" placeholder="Apellido Paterno" value="{{ $users->last_name }}" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="mother_last_name" class="form-control" placeholder="Apellido Materno" value="{{ $users->mother_last_name }}" required>
                        </div>
                        <div class="row pb-3">
                            <div class="col">
                                Fecha de Nacimiento
                                <input class="form-control" type="date" name="birth_date" value="{{ $users->birth_date }}" required>
                            </div>
                            <div class="col">Sexo
                                <div>
                                <label class="form-check-label" for="inlineRadio1">F</label>
                                    @if($users->gender == "F")
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="{{ $users->gender }}" checked>
                                    @else
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="F" required>
                                    @endif
                                <label class="form-check-label" for="inlineRadio2">M</label>
                                    @if($users->gender == "M")
                                        <input  class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="{{ $users->gender }}" checked>
                                    @else
                                        <input  class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="M" required>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="E-Mail" value="{{ $users->email }}" required>
                        </div>
                        <div class="row pb-3">
                            <div class="col">
                                <input type="text" name="phone" class="form-control" placeholder="Número de Celular" value="{{ $users->phone }}" required>
                            </div>
                            <div class="col">
                                <select name="phone_operator" class="form-select" required>
                                    @if($users->phone_operator == true)
                                        <option value="Movistar"{{ $users->phone_operator == 'Movistar' ? 'selected' : ''}}>Movistar</option>
                                        <option value="Claro"{{ $users->phone_operator == 'Claro' ? 'selected' : ''}}>Claro</option>
                                        <option value="Entel"{{ $users->phone_operator == 'Entel'? 'selected' : ''}}>Entel</option>
                                        <option value="Bitel"{{ $users->phone_operator == 'Bitel' ? 'selected' : ''}}>Bitel</option>
                                    @else
                                        <option value="">Operador</option>
                                        <option value="Movistar">Movistar</option>
                                        <option value="Claro">Claro</option>
                                        <option value="Entel">Entel</option>
                                        <option value="Bitel">Bitel</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row pb-3">
                            <div class="col">
                                <select id="department" name="department" class="form-select" required>
                                    <option value="">Departamento</option>
                                    <?php
                                    if($users->department == true){
                                        foreach ($departments as $department) {
                                            echo '<option value="'.$department["id_department"].'" ';
                                                if($users->department == $department["id_department"]){
                                                    echo "selected";
                                                }
                                                echo '>'.$department["department"].'</option>';
                                        }
                                    }else{
                                        foreach ($departments as $department) {
                                            echo '<option value="'.$department["id_department"].'">'.$department["department"].'</option>';
                                        }
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
                            <input type="text" name="address" class="form-control" placeholder="Dirección *" value="{{ $users->address }}" required>
                        </div>
                        <div class="mb-3">
                            @if($users->address_reference == true)
                                <input type="text" name="address_reference" class="form-control" placeholder="Referencia de cómo llegar" value="{{ $users->address_reference }}" >
                            @else
                                <input type="text" name="address_reference" class="form-control" placeholder="Referencia de cómo llegar" value="">
                            @endif
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">ACTUALIZAR DATOS PERSONALES</button>
                        </div>
                    </form>
                    <hr>
                    <!--form action="{{ url('/profile/edit/bank/'.$users->id)}}"  accept-charset="UTF-8" method="POST">
                        @csrf
                        <div class="row pb-3">
                            <div class="col">
                                <select name="bank" class="form-select">
                                    @if($users->bank == true)
                                        <option value="Scotiabank"{{ $users->bank == 'Scotiabank' ? 'selected' : ''}}>Scotiabank</option>
                                        <option value="BBVA"{{ $users->bank == 'BBVA' ? 'selected' : ''}}>BBVA</option>
                                        <option value="Interbank"{{ $users->bank == 'Interbank' ? 'selected' : ''}}>Interbank</option>
                                        <option value="BanBif"{{ $users->bank == 'BanBif' ? 'selected' : ''}}>BanBif</option>
                                    @else
                                        <option value="">Banco</option>
                                        <option value="Scotiabank">Scotiabank</option>
                                        <option value="BBVA">BBVA</option>
                                        <option value="Interbank">Interbank</option>
                                        <option value="BanBif">BanBif</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col">
                                <select name="type_account" class="form-select">
                                    @if($users->type_account == true)
                                        <option value="{{ $users->type_account }}">{{ $users->type_account }}</option>
                                        <option value="Ahorros">Ahorros</option>
                                        <option value="Corriente">Corriente</option>
                                    @else
                                        <option value="">Tipo de Cuenta</option>
                                        <option value="Ahorros">Ahorros</option>
                                        <option value="Corriente">Corriente</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="nro_account" class="form-control" placeholder="Nro de Cuenta" value="{{ $users->nro_account }}">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="cci_account" class="form-control" placeholder="Nro de Cuenta (CCI)" value="{{ $users->cci_account }}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">ACTUALIZAR DATOS BANCARIOS</button>
                        </div>
                    </form-->
                </div>
                <div class="col-sm-4 col-12">
                    <p>Cambia tu imagen de Perfil</p>
                    <form action="{{ url('profile/updateImagen/'.$users->id)}}"  method="POST" class="row g-3 needs-validation" novalidate  accept-charset="UTF-8" enctype="multipart/form-data">
                    @csrf
                        <div class="mb-3">
                            @if($users->image)
                            <div class="col-6">
                                <div class="form-group">
                                    <img src="{{ asset('/images/perfil/'.$users->image) }}"
                                        class="mt-5 mb-5 circular--square circular--portrait" style="max-width:300px; margin-top: 5px;"/>
                                </div>
                            </div>
                            @endif
                            <input type="file" name="image" class="form-control" required>
                            @if ($errors->has('image'))
                            <div class="invalid-feedback" style="display:block">
                                {{ $errors->first('image') }}
                            </div>
                            @endif
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">ACTUALIZAR IMAGEN</button>
                        </div>
                    </form>
                </div>
              </div>
          </div>
    </div>
</div>

<script src="{{ asset('js/profilespassword.js') }}"></script>
<script src="{{ asset('js/profilevalidation.js') }}"></script>
<!--script src="https://waterlife.com.pe/js/profilespassword.js"></script>
<script src="https://waterlife.com.pe/js/profilevalidation.js"></script-->
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
