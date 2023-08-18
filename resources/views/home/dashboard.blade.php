@include('../layouts.Assets')
<div class="container-fluid">
    <div class="row mrt-70">
        <img src="{{ URL ('images/banner1.png')}}" class="img-fluid dashboard_img">
    </div>
    <div class="row pt-3">
        <div class="col-sm-3 col-4">
            <?php
                if($user_level=='Master'){
                    echo '<img class="img-consultor" src="/images/img_consultor_master.png">';
                }else if($user_level=='Senior'){
                    echo '<img class="img-consultor" src="/images/img_consultor_senior.png">';
                }else if($user_level=='Junior'){
                    echo '<img class="img-consultor" src="/images/img_consultor_junior.png">';
                }else {
                    echo '<img class="img-consultor" src="/images/img_consultor_waterlife.png">';
                }
            ?>
            <!--img class="img-consultor" src="{{ URL ('images/img_consultor_waterlife.png')}}"-->
        </div>
        <div class="col-md-9 col-8" style="margin: auto">
            <div class="row">
                <div class="row">
                    <div class="d-flex">     
                        <p class="me-3">Status:</p>
                        <div>
                            @if(auth()->user()->status == 'A')
                                <button class="btn btn-success" type="button" disabled>ACTIVO</button>
                            @else
                                <button class="btn btn-danger" type="button" disabled>INACTIVO</button>
                            @endif
                        </div>
                    </div>                    
                    <h6 class="pt-3">Tus compras tienen un <?php echo '<b>'.$commission['personal_discount'].'%</b>'; ?> de descuento.</h6>
                </div>
            </div>
            <div class="row info-socio">
                <div class="col-4">
                    <h6 class="dashboard-title">INFORMACIÓN DEL PERIODO</h6>
                    <p>
                        Volumen personal: <?php echo $commission['amp']; ?> pts.<br>
                        Volumen grupal: <?php echo $group_volume; ?> pts.<br>
                        Nº Consultores directos activos: <?php echo $count_level1; ?>
                    </p>
                </div>
                <div class="col-4"> 
                    <h6 class="dashboard-title">INFORMACIÓN SEMESTRAL</h6>
                    <b>Semestre 1</b>
                    <p>
                        Volumen personal: <?php echo $commission_1S; ?> pts.<br>
                        Volumen grupal: <?php echo $group_volume_1S+$commission_1S; ?> pts.<br>
                    </p>
                    <b>Semestre 2</b>
                    <p>
                        Volumen personal: <?php echo $commission_2S; ?> pts.<br>
                        Volumen grupal: <?php echo $group_volume_2S+$commission_2S; ?> pts.<br>
                    </p>
                </div>
                <div class="col-4"> 
                    <h6 class="dashboard-title">INFORMACIÓN ANUAL</h6>
                    <p>
                        Volumen personal: <?php echo $commission_1S+$commission_2S; ?> pts.<br>
                        Volumen grupal: <?php echo $group_volume_1S+$group_volume_2S+$commission_1S+$commission_2S; ?> pts.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row info-socio-responsive">
        <hr>
        <div class="col">
            <h6 class="dashboard-title">INFORMACIÓN DEL PERIODO</h6>
            <p>
                Volumen personal: <?php echo $commission['amp']; ?> pts.<br>
                Volumen grupal: <?php echo $group_volume; ?> pts.<br>
                Nº Consultores directos activos: <?php echo $count_level1; ?>
            </p>
        </div>
        <div class="col">
            <h6 class="dashboard-title">INFORMACIÓN SEMESTRAL</h6>
            <div class="row">
                <div class="col-6">
                    <b>Semestre 1</b>
                    <p>
                        Volumen personal: <?php echo $commission_1S; ?> pts.<br>
                        Volumen grupal: <?php echo $group_volume_1S; ?> pts.<br>
                    </p>
                </div>
                <div class="col-6">
                    <b>Semestre 2</b>
                    <p>
                        Volumen personal: <?php echo $commission_2S; ?> pts.<br>
                        Volumen grupal: <?php echo $group_volume_2S; ?> pts.<br>
                    </p>
                </div>
            </div>
        </div>
        <div class="col">
            <h6 class="dashboard-title">INFORMACIÓN ANUAL</h6>
            <p>
                Volumen personal: <?php echo $commission_1S+$commission_2S; ?> pts.<br>
                Volumen grupal: <?php echo $group_volume_1S+$group_volume_2S; ?> pts.<br>
            </p>
        </div>
    </div>
    <div class="row">               
        <?php
            if($track_full!='inactive'){
            echo '<hr><div class="col-12 col-md-4">
                    <h6>PROGRAMA DE CONSTANCIA</h6>
                    <p>Saca el máximo provecho de tu membresía y gana el Bono de Constancia.</p>
                    <p>Recuerda que este bono se da por única vez a tu ingreso a la Red WaterLife
                </div>';
                if($track_1=='active'){
        ?>
        <div class="col-6 col-md-4">
            <h6>TRACK</h6>
            <table class="table text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Periodo</th>
                    <th scope="col">Volumen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mes 1</td>
                    <td>
                        <?php
                            if($months_1!=0){
                                if($months_1['amp']>=300){
                                    echo '<span class="text-success">'.$months_1['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_1['amp'].'</span>';
                                }
                            }else{
                                echo $months_1;
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Mes 2</td>
                    <td>
                        <?php
                            if($months_2!=0){
                                if($months_2['amp']>=600){
                                    echo '<span class="text-success">'.$months_2['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_2['amp'].'</span>';
                                }
                            }else{
                                echo $months_2;
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Mes 3</td>
                    <td>
                        <?php
                            if($months_3!=0){
                                if($months_3['amp']>=900){
                                    echo '<span class="text-success">'.$months_3['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_3['amp'].'</span>';
                                }
                            }else{
                                echo $months_3;
                            }
                        ?>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
        <?php
                }

                if($track_2=='active'){
        ?>
        <div class="col-6 col-md-4">
            <h6>TRACK</h6>
            <table class="table text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Periodo</th>
                    <th scope="col">Volumen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mes 1</td>
                    <td>
                        <?php
                            if($months_1!=0){
                                if($months_1['amp']>=75){
                                    echo '<span class="text-success">'.$months_1['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_1['amp'].'</span>';
                                }
                            }else{
                                echo $months_1;
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Mes 2</td>
                    <td>
                        <?php
                            if($months_2!=0){
                                if($months_2['amp']>=300){
                                    echo '<span class="text-success">'.$months_2['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_2['amp'].'</span>';
                                }
                            }else{
                                echo $months_2;
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Mes 3</td>
                    <td>
                        <?php
                            if($months_3!=0){
                                if($months_3['amp']>=600){
                                    echo '<span class="text-success">'.$months_3['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_3['amp'].'</span>';
                                }
                            }else{
                                echo $months_3;
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Mes 4</td>
                    <td>
                        <?php
                            if($months_4!=0){
                                if($months_4['amp']>=900){
                                    echo '<span class="text-success">'.$months_4['amp'].'</span>';
                                }else{
                                    echo '<span class="text-danger">'.$months_4['amp'].'</span>';
                                }
                            }else{
                                echo $months_4;
                            }
                        ?>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
        <?php
                } 
            }
        ?>
    </div>
    <hr>
    <div class="row">
        <div class="col-12 mb-4 mt-3">
            <h5 class="dashboard-title">Requisitos para Rangos</h5>
            <p>Mide y controla los requisitos para que alcances los siguientes Rangos en la Red WaterLife</p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
            	<div class="row">
                	<div class="col-4 col-md-12 text-center"><img src="{{ URL ('images/img_consultor_junior_small.png')}}"></div>
	                <div class="col-8 col-md-12">
	                    <div class="row pt-3">
	                    	<div class="col-7">Puntos personal:</div>
	                    	<?php
		                        if($commission['amp']>=75){
		                            echo '<div class="col-5 text-success">'.$commission['amp'].' de 75</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$commission['amp'].' de 75</div>';
		                        }
		                    ?>                    
		                    <div class="col-7">Consultores directos:</div>
		                    <?php
		                        if($count_level1>=1){
		                            echo '<div class="col-5 text-success">'.$count_level1.' de 1</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$count_level1.' de 1</div>';
		                        }
		                    ?>
		                    <div class="col-7">Puntos grupal:</div>
		                    <?php
		                        if($group_volume>=300){
		                            echo '<div class="col-5 text-success">'.$group_volume.' de 300</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$group_volume.' de 300</div>';
		                        }
		                    ?>
                        </div>
	                </div>
                </div>
            </div>
            <div class="vr m-0 p-0 info-socio"></div>
            <div class="col-12 col-md-4">
            	<div class="row">
	                <div class="col-4 col-md-12 text-center"><img src="{{ URL ('images/img_consultor_senior_small.png')}}"></div>
	                <div class="col-8 col-md-12">
	                	<div class="row pt-3">
		                    <div class="col-7">Puntos personal:</div>
		                    <?php
		                        if($commission['amp']>=75){
		                            echo '<div class="col-5 text-success">'.$commission['amp'].' de 75</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$commission['amp'].' de 75</div>';
		                        }
		                    ?>
		                    <div class="col-7">Consultores directos:</div>
		                    <?php
		                        if($count_level1>=3){
		                            echo '<div class="col-5 text-success">'.$count_level1.' de 3</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$count_level1.' de 3</div>';
		                        }
		                    ?>
		                    <div class="col-7">Puntos grupal:</div>
		                    <?php
		                        if($group_volume>=600){
		                            echo '<div class="col-5 text-success">'.$group_volume.' de 600</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$group_volume.' de 600</div>';
		                        }
		                    ?>
		                </div>
                    </div>
                </div>
            </div>
            <div class="vr m-0 p-0 info-socio"></div>
            <div class="col-12 col-md-3">
            	<div class="row">
	                <div class="col-4 col-md-12 text-center"><img src="{{ URL ('images/img_consultor_master_small.png')}}"></div>
	                <div class="col-8 col-md-12">
	                	<div class="row pt-3">
		                    <div class="col-7">Puntos personal:</div>
		                    <?php
		                        if($commission['amp']>=50){
		                            echo '<div class="col-5 text-success">'.$commission['amp'].' de 75</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$commission['amp'].' de 75</div>';
		                        }
		                    ?>
		                    <div class="col-7">Consultores directos:</div>
		                    <?php
		                        if($count_level1>=10){
		                            echo '<div class="col-5 text-success">'.$count_level1.' de 10</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$count_level1.' de 10</div>';
		                        }
		                    ?>
		                    <div class="col-7">Puntos grupal:</div>
		                    <?php
		                        if($group_volume>=1200){
		                            echo '<div class="col-5 text-success">'.$group_volume.' de 1200</div>';
		                        }else{
		                            echo '<div class="col-5 text-danger">'.$group_volume.' de 1200</div>';
		                        }
		                    ?>
		                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('../layouts.Footer')
