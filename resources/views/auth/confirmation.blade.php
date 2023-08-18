@include('../layouts.Assets')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 col-sm-12 logo-top">
			<img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
        <div class="col-md-6 col-sm-12">
            @if (session('message'))
                <div class="alert alert-{{ session('class') }} d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div>
                    </div>
                    {{ session('message') }}
                </div>
                <h2>Registra Nuevo Consultor</h2>
                <p>¡Vuelva a intentarlo!<br>
                Ya se ha registrado este correo: <b><?php echo $email ?></b> en nuestra base de datos.
                </p>
            @else
                <h2>Registra Nuevo Consultor</h2>
                <p>¡Muy bien!<br>
                Tu red sigue creciendo, ahora tendrás más y mejores beneficios.<br><br>
                Hemos enviado un correo a <?php echo $email ?>  con indicaciones para que termine su afiliación.<br>
                Hazle seguimiento y brinda tu ayuda en lo que necesite.
                </p>
            @endif
            
            <form action="" method="POST">  
                <div class="d-grid gap-2">
                    <a href="/dashboard" type="submit" class="btn btn-primary">FINALIZAR</a>
                </div>
            </form>
        </div>
    </div>
</div>
@include('../layouts.Footer')