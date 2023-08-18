@include('../layouts.Assets')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <div class="col-md-6 col-12">
            <h2 class="dashboard-title">Registra Nuevo Consultor</h2>
            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p>Hay errores!</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="/register" method="POST" accept-charset="UTF-8" class=" needs-validation" novalidate>
                <div class="row pb-3">
                    @csrf
                    <div class="col">
                        <select name="type_doc" id="type_doc" class="form-select" required>
                            <option value="">Tipo de Documento</option>
                            <option value="DNI">DNI</option>
                            <option value="Carnet_Extranjeria">Carnet Extranjería</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="RUC">RUC</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" name="num_doc" class="form-control" placeholder="Nro de Documento" required value="{{ old('num_doc') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" name="firts_name" class="form-control" placeholder="Nombres *" required value="{{ old('firts_name') }}">
                </div>
                <div class="mb-3">
                    <input type="text" name="last_name" class="form-control" placeholder="Apellido Paterno" required value="{{ old('last_name') }}">
                </div>
                <div class="mb-3">
                    <input type="text" name="mother_last_name" class="form-control" placeholder="Apellido Materno" required value="{{ old('mother_last_name') }}">
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <input type="password" id="field" name="password" class="form-control" placeholder="Password" required>
                </div>                
                <div class="mb-3 info-socio-responsive">
                    <div class="progress col">
                        <div  id="fieldstarResponsive" class="progress-bar" ></div>
                    </div>
                </div>
                <div class="col-12 col-md-4 info-socio">
                    <div class="progress col">
                        <div  id="fieldstar" class="progress-bar" ></div>
                    </div>
                </div>
                <div class="mb-3 pt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_email_sponsor" value="1" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        Enviar una copia del pre-registro al patrocinador
                        </label>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <input type="hidden" name="id_sponsor" value="{{auth()->user()->id_sponsor}}">
                    <button type="submit" class="btn btn-primary button-active">REGISTRAR</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 info-socio">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>
<script src="{{ asset('js/profilespassword.js') }}"></script>
<script src="{{ asset('js/profilevalidation.js') }}"></script>

<script>
$(document).ready(function () {
    
    $('#type_doc').on('change', function () {
        var type_doc = this.value;

    });
});
</script>
@include('../layouts.Footer')
