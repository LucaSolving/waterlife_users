@include('../layouts.Assets')
<link rel="stylesheet" href="C:/Users/User/Desktop/OrgChart-master/demo/css/jquery.orgchart2.css">
  <link rel="stylesheet" href="C:/Users/User/Desktop/OrgChart-master/demo/css/style2.css">
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
<!--script src="https://balkan.app/js/OrgChart.js"></script-->
<div class="container">
    <div class="row">
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h4>MI RED</h4>
        <p></p>
        <div class="col-12">
            <div class="row">
                <div class="col-2">                    
                    <h5>1er Nivel</h5>
                    <p>
                        Nro Consultores: <b><?php echo $count_level1; ?></b><br>
                        Puntos: <b><?php echo $simulation_commissions; ?></b>
                    </p>
                </div>
                <div class="vr m-0 p-0"></div>
                <div class="col-2 ms-3">
                    <h5>2do Nivel</h5>
                    <p>
                        Nro Consultores: <b>
                        <?php
                            
                            $count_level2 = 0;
                            if($user_20_level!=''){
                                foreach ( $user_20_level as $key =>  $user_2level){
                                    
                                    foreach ( $user_2level as $key =>  $user_22level){
                                        $count_level2++;
                                    }
                                }
                                echo $count_level2;
                            }else{
                                echo 0;
                            }
                        ?>
                        </b>
                        <br>
                        Puntos: <b><?php echo $simulation_commissions_2; ?></b>
                    </p>
                </div>
                <div class="vr m-0 p-0"></div>
                <div class="col-2 ms-3 pt-3">
                    <h5>Simulación Comisiones</h5>
                </div>
                <div class="col-5">                    
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
                                <td>S/ <?php echo number_format($simulation_commissions*0.05,2); ?></td>
                                <td>-</td>
                                <td><b>S/ <?php echo number_format($simulation_commissions*0.05,2); ?></b></td>
                            </tr>
                            <tr>
                                <td>Consultor Senior</td>
                                <td>S/ <?php echo $s1 = number_format($simulation_commissions*0.075,2); ?></td>
                                <td>S/ <?php echo $s2 = number_format($simulation_commissions_2*0.025,2); ?></td>
                                <td><b>S/ <?php echo number_format($s1+$s2,2); ?></b></td>
                            </tr>
                            <tr>
                                <td>Consultor Master</td>
                                <td>S/ <?php echo $m1 = number_format($simulation_commissions*0.1,2); ?></td>
                                <td>S/ <?php echo $m2 = number_format($simulation_commissions_2*0.05,2); ?></td>
                                <td><b>S/ <?php echo number_format($m1+$m2,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div id="chart-container"></div>
            <!-- MI RED CHART -->
            <div id="tree"></div>
        </div>
    </div>
</div>
@include('../layouts.Footer')



<script type="text/javascript" src="C:/Users/User/Desktop/OrgChart-master/demo/js/jquery.min.js"></script>
<script type="text/javascript" src="C:/Users/User/Desktop/OrgChart-master/demo/js/jquery.orgchart.js"></script>
<script type="text/javascript">
    $(function() {

    var datascource = {
      'id': '1',
      'name': '<img src="http://26.220.92.239:8003/images/bullet_green.png" width="18" height="18"> Pedro Reyes Salvatierra',
      'title': '<img src="http://26.220.92.239:8003/images/perfil/news-20221005143849.jpg" width="60" height="60"><span>Vol. Personal: 458</span>',
      'children': [
        { 'id': '2', 'name': 'Bo Miao Bo Miao Bo Miao Bo Miao', 'title': 'Vol. Personal: 458' },
        { 'id': '3', 'name': 'Su Miao', 'title': 'department manager',
          'children': [
            { 'id': '4', 'name': 'Tie Hua Tie Hua Tie Hua Tie Hua', 'title': 'senior engineer senior engineer senior engineer' },
            { 'id': '5', 'name': 'Hei Hei', 'title': 'senior engineer',
              'children': [
                { 'id': '6', 'name': 'Pang Pang', 'title': 'engineer' },
                { 'id': '7', 'name': 'Xiang Xiang', 'title': 'UE engineer UE engineer UE engineer' }
              ]
            }
          ]
        },
        { 'id': '8', 'name': 'Yu Jie', 'title': 'department manager department manager department manager' },
        { 'id': '9', 'name': 'Yu Li', 'title': 'department manager' },
        { 'id': '10', 'name': 'Hong Miao', 'title': 'department manager' },
        { 'id': '11', 'name': 'Yu Wei', 'title': 'department manager' },
        { 'id': '12', 'name': 'Chun Miao', 'title': 'department manager' },
        { 'id': '13', 'name': 'Yu Tie', 'title': 'department manager' }
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
        var secondMenu = '<div class="second-menu"><span>Pedro Reyes Salvatierra</span><span>Telefono: 543534<br>E-mail: preyes@mail.com<br>Volumen personal: 0<br>Volumen grupal: 0</span></div>';
        $node.append(secondMenuIcon).append(secondMenu);
      }
    });

  });
</script>

<!--HTML-->
<style>
#tree {
  width:100%;
  height:100%;
}
</style>
<!--script>
    //JavaScript   
window.onload = function () {
    
    OrgChart.templates.myTemplate = Object.assign({}, OrgChart.templates.ana);
    OrgChart.templates.myTemplate.size = [250, 140];
    OrgChart.templates.myTemplate.node = '<a href="#" class="link-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><rect x="0" y="0" width="250" height="140" fill="#ffffff" stroke-width="1" stroke="#aeaeae" rx="5" ry="5"></rect></a>';
    OrgChart.templates.myTemplate.field_0 = '<text data-width="100" data-text-overflow="multiline" style="font-size: 20px;font-weight: bold;" fill="#2D2D2D" x="150" y="30" text-anchor="middle">{val}</text>';
    OrgChart.templates.myTemplate.field_1 = '<text data-width="110" data-text-overflow="multiline"  style="font-size: 14px;" fill="#2D2D2D" x="150" y="105" text-anchor="middle">{val}</text>';
    OrgChart.templates.myTemplate.field_2 = '<text data-width="110" data-text-overflow="multiline"  style="font-size: 14px;" fill="#2D2D2D" x="150" y="105" text-anchor="middle">{val}</text>';
    OrgChart.templates.myTemplate.img_0 = '<a href="#" class="link-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><clipPath id="{randId}"><circle cx="50" cy="47" r="30"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="20" y="17"  width="60" height="60"></image></a>';
    OrgChart.templates.myTemplate.field_3 = '<clipPath id="{randId}"><circle cx="35" cy="117" r="50"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="35" y="87"  width="26" height="26"></image>';
    OrgChart.templates.myTemplate.field_4 = '<text class="d-none" data-width="110" data-text-overflow="multiline" style="font-size: 14px;" fill="#2D2D2D" x="150" y="125" text-anchor="middle">{val}</text>';
    OrgChart.templates.myTemplate.field_5 = '<text class="d-none" data-width="110" data-text-overflow="multiline" style="font-size: 14px;" fill="#2D2D2D" x="150" y="145" text-anchor="middle">{val}</text>';
    OrgChart.templates.myTemplate.field_6 = '<text class="d-none" data-width="110" data-text-overflow="multiline" style="font-size: 14px;" fill="#2D2D2D" x="150" y="165" text-anchor="middle">{val}</text>';
    OrgChart.templates.myTemplate.field_7 = '<text class="d-none" data-width="110" data-text-overflow="multiline" style="font-size: 14px;" fill="#2D2D2D" x="150" y="185" text-anchor="middle">{val}</text>';

    OrgChart.templates.myTemplate.plus = '<circle cx="15" cy="15" r="15" fill="#57B6F1" stroke="#ffffff" stroke-width="1"></circle>'
        + '<line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#ffffff"></line>'
        + '<line x1="15" y1="4" x2="15" y2="26" stroke-width="1" stroke="#ffffff"></line>';
    OrgChart.templates.myTemplate.minus = '<circle cx="15" cy="15" r="15" fill="#37D8BF" stroke="#ffffff" stroke-width="1"></circle>'
        + '<line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#ffffff"></line>';


    var editForm = function () {
        this.nodeId = null;
    };

    editForm.prototype.init = function (obj) {
        var that = this;
        this.obj = obj;
        this.editForm = document.getElementById("editForm");
        this.imgInput = document.getElementById("img");
        this.nameInput = document.getElementById("name");
        this.titleInput = document.getElementById("title");
        this.ampInput = document.getElementById("amp");
        this.group_volumeInput = document.getElementById("group_volume");
        this.cancelButton = document.getElementById("close");

        this.cancelButton.addEventListener("click", function () {
            that.hide();
        });
    };


    editForm.prototype.hide = function (showldUpdateTheNode) {
        this.editForm.style.display = "none";
        this.editForm.style.opacity = 0;

    };

    var chart = new OrgChart(document.getElementById('tree'), {
        collapse: {
            level: 2
        },
        //enableDragDrop: true,
        mouseScrool: OrgChart.none,
        toolbar: {
            zoom: true,
        },
        enableSearch: false,
        template: "myTemplate",
        nodeBinding: {
            field_0: "name",
            field_1: 'title',
            field_2: 'title2',
            field_3: 'title3',
            field_4: 'phone',
            field_5: 'email',
            field_6: 'amp',
            field_7: 'group_volume',
            img_0: "img"
        },
        editUI: new editForm()
    });

    chart.onNodeClick((args) => {
        let node = chart.get(args.node.id);
        console.log(node);

        let title = document.getElementById("modal-title");
        let phone = document.getElementById("modal-phone");
        let email = document.getElementById("modal-email");
        let amp = document.getElementById("modal-amp");
        let group_volume = document.getElementById("modal-group_volume");

        title.textContent = node.name;
        phone.textContent = node.phone;
        email.textContent = node.email;
        amp.textContent = node.amp;
        group_volume.textContent = node.group_volume;
    });

    chart.load([
        {
            id:  "{{$my_data->id}}",
            name: "{{$my_data->firts_name}} {{$my_data->last_name}}" ,
            title: "Vol. Personal: {{$commission['amp']}}",
            //title2: "Vol. Grupal: {{$group_volume}}",
            @if($my_data->status == 'A')
                title3: "/images/bullet_green.png",
            @elseif($my_data->status == 'I')
                title3: "/images/bullet_red.png",
            @endif
            //img: src="{{ asset('/images/perfil/'.$my_data->image) }}",
            @if($my_data->image!='')
                img: src="{{asset('/images/perfil/'.$my_data->image)}}",
            @else
                img: src="{{asset('/images/icon_user.png')}}",
            @endif
            phone: "{{$my_data->phone}}",
            email: "{{$my_data->email}}",
            amp: "0",
            group_volume: "0",
        },
        @foreach ( $user_1_level as $key =>  $user_1level)
        {
            
            id: "{{$user_1level->id}}",
            pid: "{{$user_1level->id_sponsor}}",
            name: "{{$user_1level->firts_name}} {{$user_1level->last_name}}",
            @if($commission_1_level != '')
                title: "Vol. Personal: 0",
            @else
                title: "Vol. Personal: 0",
            @endif
            //title2: "Vol. Grupal: 0",
            @if($user_1level->status == 'A')
                title3: "/images/bullet_green.png",
            @elseif($user_1level->status == 'I')
                title3: "/images/bullet_red.png",
            @endif
            @if($user_1level->image!='')
                img: src="{{asset('/images/perfil/'.$user_1level->image)}}",
            @else
                img: src="{{asset('/images/icon_user.png')}}",
            @endif
            phone: "{{$user_1level->phone}}",
            email: "{{$user_1level->email}}",
            amp: "0",
            group_volume: "0",
        },
        @endforeach
        
        @if($user_20_level != '')
            @foreach ( $user_20_level as $key =>  $user_2level)

                @foreach ( $user_2level as $key2 =>  $user_22level)
                {
                    
                    id: "{{$user_22level->id}}",
                    pid: "{{$user_22level->id_sponsor}}",
                    name: "{{$user_22level->firts_name}} {{$user_22level->last_name}}",
                    title: "Vol. Personal: {{$commission_2_level[$key][$key2]}}",
                    //title2: "Vol. Grupal: 0",
                    @if($user_22level->status == 'A')
                        title3: "/images/bullet_green.png",
                    @elseif($user_22level->status == 'I')
                        title3: "/images/bullet_red.png",
                    @endif
                    @if($user_22level->image!='')
                        img: src="{{asset('/images/perfil/'.$user_22level->image)}}",
                    @else
                        img: src="{{asset('/images/icon_user.png')}}",
                    @endif
                    phone: "{{$user_22level->phone}}",
                    email: "{{$user_22level->email}}",
                    amp: "0",
                    group_volume: "0",
                },
                @endforeach
            @endforeach
        @endif
    ]);

    
};
</script-->


<!-- Modal Recovery Password -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">        
        <div class="mb-3">
            <label for="">Telefono:</label>
            <span id="modal-phone"></span>
        </div>
        <div class="mb-3">
            <label for="">E-mail:</label>
            <span id="modal-email"></span>
        </div>
        <div class="mb-3">
            <label for="">Volumen personal:</label>
            <span id="modal-amp"></span>
        </div>
        <div class="mb-3">
            <label for="">Volumen grupal:</label>
            <span id="modal-group_volume"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<div id="editForm" style="position: absolute;top: 50%;left: 50%;margin-top: -50px;margin-left: -50px; display: none; 
opacity: 0;text-align:center; border: 1px solid #aeaeae;width:300px;background-color:#fff;z-index:10000;border-radius: 15px; 
filter: drop-shadow(5px 5px 5px #909090);">
    <table style="margin: 10px; width: 280px;">
        <tr>
            <td colspan="2" style="text-align: right;">
                <span id="close" style="font-weight: 300;font-family: Arial, sans-serif; cursor: pointer;">x</span>
            </td>
        </tr>

        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            <img id="img" src="" style="width: 70px; border-radius: 35px;" />
                        </td>
                        <td style="text-align: left; padding-left: 10px;">
                            <div id="name" style="font-size: 24px;font-weight: bold;"></div>
                            <div id="title"></div>
                            <div id="amp"></div>
                            <div id="group_volume"></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

