@include('../layouts.Assets')
<link rel="stylesheet" href="{{ URL ('community/jquery.orgchart2.css')}}">
<link rel="stylesheet" href="{{ URL ('community/style2.css')}}">
  <style type="text/css">
    .orgchart .second-menu-icon {
      transition: opacity .5s;
      opacity: 0;
      right: -5px;
      top: -5px;
      z-index: 2;
      position: absolute;
    }
    .orgchart .second-menu-icon::before { background-color: rgba(68, 157, 68, 0.5); }
    .orgchart .second-menu-icon:hover::before { background-color: #449d44; }
    .orgchart .node:hover .second-menu-icon { opacity: 1; }
    .orgchart .node .second-menu {
      display: none;
      position: absolute;
      top: 0;
      right: -70px;
      border-radius: 15px;
      box-shadow: 0 0 20px 1px #999;
      background-color: #fff;
      z-index: 1;
      padding: 10px;
      text-align: left;
      font-size: 12px;
    }
    .orgchart .node .second-menu .avatar {
      width: 60px;
      height: 60px;
      border-radius: 30px;
      float: left;
      margin: 5px;
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
        <h4 class="dashboard-title">MI RED</h4>
        <p></p>
        <div class="col-12">
            <div class="row">
                <div class="col-md-2 col-5">                   
                    <h5>1er Nivel</h5>
                    <p>
                        Nro Consultores: <b><?php echo $count_level1; ?></b><br>
                        Puntos: <b><?php echo $simulation_commissions; ?></b>
                    </p>
                </div>
                <div class="vr m-0 p-0"></div>
                <div class="col-md-2 col-6 ms-3">
                    <h5>2do Nivel</h5>
                    <p>
                        Nro Consultores: <b>
                        <?php
                            
                            echo $count_level2;
                        ?>
                        </b>
                        <br>
                        Puntos: <b><?php echo $simulation_commissions_2; ?></b>
                    </p>
                </div>
                <div class="vr m-0 p-0 info-socio"></div>
                <div class="col-md-2 ms-3 pt-3">
                    <h5>Simulación Comisiones</h5>
                </div>
                <div class="col-md-5 col-12">                  
                    <table class="table table-bordered text-center table-sm mb-0">
                        <thead class="table-light">
                            <th scope="col">TIPO</th>
                            <th scope="col">1er Nivel</th>
                            <th scope="col">2do Nivel</th>
                            <th scope="col">TOTAL</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Consultor Junior</td>
                                <td>S/ <?php echo number_format($simulation_commissions*0.25,2); ?></td>
                                <td>-</td>
                                <td><b>S/ <?php echo number_format($simulation_commissions*0.25,2); ?></b></td>
                            </tr>
                            <tr>
                                <td>Consultor Senior</td>
                                <td>S/ <?php echo $s1 = number_format($simulation_commissions*0.25,2); ?></td>
                                <td>-</td>
                                <td><b>S/ <?php echo $s1 = number_format($simulation_commissions*0.25,2); ?></b></td>
                            </tr>
                            <tr>
                                <td>Consultor Master</td>
                                <td>S/ <?php echo $m1 = number_format($simulation_commissions*0.25,2); ?></td>
                                <td>S/ <?php echo $m2 = number_format($simulation_commissions_2*0.05,2); ?></td>
                                <td><b>S/ <?php echo number_format($m1+$m2,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12">
            <!-- MI RED CHART -->
            <div id="chart-container"></div>
        </div>
    </div>
</div>
@include('../layouts.Footer')

<script type="text/javascript" src="{{ URL ('community/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ URL ('community/jquery.orgchart.js')}}"></script>
<script type="text/javascript">
    $(function() {

    var datascource = {
      'id': '$my_data->id',
        @if($my_data->status == 'A')
        'name': '<img src="http://26.220.92.239:8003/images/bullet_green.png" width="18" height="18"> {{$my_data->firts_name}} {{$my_data->last_name}}',
        @elseif($my_data->status == 'I')
        'name': '<img src="http://26.220.92.239:8003/images/bullet_red.png" width="18" height="18"> {{$my_data->firts_name}} {{$my_data->last_name}}',
        @endif

        'email': '{{$my_data->email}}',
        'phone': '{{$my_data->phone}}',
        'amp': '{{$commission["amp"]}}',
        'group_volume': '{{$group_volume}}',

        @if($my_data->image!='')
            img: src="",
            'title': '<img src="{{asset('/images/perfil/'.$my_data->image)}}" width="60" height="60"><span>Vol. Personal: {{$commission["amp"]}}</span>',
        @else
            img: src="",
            'title': '<img src="{{asset('/images/icon_user.png')}}" width="60" height="60"><span>Vol. Personal: {{$commission["amp"]}}</span>',
        @endif
      
        
        'children': [
            
            @php 
                $i_1level=0
            @endphp
            @foreach ( $user_1_level as $key =>  $user_1level)
                
                { 'id': '{{$user_1level->id}}', 
                @if($user_1level->status == 'A')
                    'name': '<img src="http://26.220.92.239:8003/images/bullet_green.png" width="18" height="18"> {{$user_1level->firts_name}} {{$user_1level->last_name}}',
                @elseif($user_1level->status == 'I')
                    'name': '<img src="http://26.220.92.239:8003/images/bullet_red.png" width="18" height="18"> {{$user_1level->firts_name}} {{$user_1level->last_name}}',
                @endif
                'email': '{{$user_1level->email}}',
                'phone': '{{$user_1level->phone}}',
                'amp': '{{$commission_1_level[$i_1level]}}',
                'group_volume': '{{$group_volume_1[$i_1level]}}',
                @if($user_1level->image!='')
                    img: src="",
                    'title': '<img src="{{asset('/images/perfil/'.$user_1level->image)}}" width="60" height="60"><span>Vol. Personal: {{$commission_1_level[$i_1level]}}</span>',
                @else
                    img: src="",
                    'title': '<img src="{{asset('/images/icon_user.png')}}" width="60" height="60"><span>Vol. Personal: {{$commission_1_level[$i_1level]}}</span>',
                @endif
                'children': [            
                        @php 
                            $i_2level=0
                            
                        @endphp
                        @if($user_20_level!='')
                            @foreach ( $user_20_level as $key =>  $user_20level)
                                @foreach ( $user_20level as $key2 =>  $user_2level)
                                    @if($user_1level->id == $user_2level->id_sponsor)
                                        { 'id': '{{$user_2level->id}}', 
                                        @if($user_2level->status == 'A')
                                            'name': '<img src="http://26.220.92.239:8003/images/bullet_green.png" width="18" height="18"> {{$user_2level->firts_name}} {{$user_2level->last_name}}',
                                        @elseif($user_2level->status == 'I')
                                            'name': '<img src="http://26.220.92.239:8003/images/bullet_red.png" width="18" height="18"> {{$user_2level->firts_name}} {{$user_2level->last_name}}',
                                        @endif
                                        'email': '{{$user_2level->email}}',
                                        'phone': '{{$user_2level->phone}}',
                                        'amp': '{{$commission_2_level[$i_2level]}}',
                                        'group_volume': '{{$group_volume_2[$i_2level]}}',
                                        @if($user_2level->image!='')
                                            img: src="",
                                            'title': '<img src="{{asset('/images/perfil/'.$user_2level->image)}}" width="60" height="60"><span>Vol. Personal: {{$commission_2_level[$i_2level]}}</span>',
                                        @else
                                            img: src="",
                                            'title': '<img src="{{asset('/images/icon_user.png')}}" width="60" height="60"><span>Vol. Personal: {{$commission_2_level[$i_2level]}}</span>',
                                        @endif
                                        },
                                    @endif
                                @php
                                    ++$i_2level
                                @endphp
                                @endforeach
                            @endforeach
                        @endif
                    ]
                },
            @php
                ++$i_1level
            @endphp
            @endforeach
        ]
    };

    $('#chart-container').orgchart({
      'data' : datascource,
      'visibleLevel': 2,
      'nodeContent': 'title',
      'nodeID': 'id',
      'createNode': function($node, data) {
        var secondMenuIcon = $('<i>', {
          'class': 'oci oci-info-circle second-menu-icon',
          click: function() {
            $(this).siblings('.second-menu').toggle();
          }
        });
        var secondMenu = '<div class="second-menu"><b>' + data.name + '</b><br>Teléfono: ' + data.phone + '<br>E-mail: ' + data.email + '<br>Volumen personal: ' + data.amp + '<br>Volumen grupal: ' + data.group_volume + '</div>';
                        
        $node.append(secondMenuIcon).append(secondMenu);
      }
    });

  });
</script>

