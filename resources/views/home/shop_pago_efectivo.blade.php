@include('../layouts.Assets')
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h4>Tienda Water Life</h4>
        <p></p>
        <div class="col-8">
            <div class="alert alert-success" role="alert">
                <p>Se ha generado tu código CIP correctamente.</p>
            </div>
            <p><b>¡Muy bien!</b><br>Código CIP: {{$codigo_cip}}</p>
            
            <form action="/dashboard" method="GET">
                @csrf
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">FINALIZAR</button>
                </div>
            </form>            
        </div>
        <div class="col-4">
            <img src="https://waterlifeperu.com/wp-content/uploads/portada-png-1.png" width="100%">
        </div>
    </div>
</div>


@include('../layouts.Footer')