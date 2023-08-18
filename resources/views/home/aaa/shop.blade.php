@include('../layouts.Assets')

<div class="container" style="margin-top: 100px">
    <div class="row">
        <h4 class="dashboard-title">Tienda Water Life</h4>
        <p></p>
        <div class="col-md-8 col-12">
            <div class="row">
                <?php
                
                    foreach ($products as $p) {
                        echo '<div class="col-md-3 col-12 pt-3 text-center" style="float:left;">';
	                        echo '
                                <div class="row" style="padding-right: 20px">
                                    <div class="col-4 col-md-12" style="margin: auto">';
                                        if($p["image"]!=''){
                                            echo '<img src="https://productsmlmw.lucasolving.com'.$p["image"].'" style="width: 100px;">';
                                        }else{
                                            echo '<img src="https://productsmlmw.lucasolving.com/uploads/products/img_product.png" style="width: 100px;">';
                                        }                           
                            echo '
                            		</div>
                                	<div class="col-5 col-md-12" style="margin: auto; text-align: start;">
                                        <h6 class="pt-3">'.$p["product"].'</h6>
                                        <p><small>'.$p["summary"].'</small></p>
                                        <p><small>Precio Regular: S/'.$p["price"].'<br>'.$p["points"].' pts.</small></p>
                                    </div>
                                    <div class="col-3 col-md-12" style="margin: auto">
		                                <div class="row">
                                            <div class="col-md-12 col-12 mb-3" >
			                                    <input type="text" id="quantity_'.$p["id"].'" class="form-control" placeholder="Cantidad" style="width: 100%;font-size:11px; text-align: center;">
			                                    <input type="hidden" id="id_product_'.$p["id"].'" value="'.$p["id"].'" class="form-control" placeholder="Cantidad">
			                                </div>
			                                <div class="col-md-12 col-12 mb-3">
			                                    <button type="button" id="stockproduct_'.$p["id"].'" class="btn btn-secondary button-active" style="width: 100%;font-size: 11px">AGREGAR</button>
			                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                        echo '<script>
                                $("#stockproduct_'.$p["id"].'").click(function(){
                                    
                                    id = '.$p["id"].'                                    
                                    quantity = $("#quantity_'.$p["id"].'").val();
                                    department = $("#departament option:selected").text();
                                    province = $("#province option:selected").text();
                                    district = $("#district option:selected").text();
                                    address = $("#address").val();
                                    reference = $("#reference").val();
                                    indication = $("#indication").val();

                                    $.ajaxSetup({
                                        headers: {
                                            "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
                                        }
                                    }),
                                    $.ajax({
                                        url: "https://mlmw.lucasolving.com/stockproduct/" + id,
                                        data: {
                                            quantity: quantity,
                                            id_product: id,
                                            department: department,
                                            province: province,
                                            district: district
                                        },
                                        type:"post",
                                        success: function (product) {
                                            /*alert(product);
                                            $.each(product, function(index, value){                                                
                                                alert("may: " + value)
                                            });*/

                                            if(product==1){
                                                alert("La cantidad ingresada es mayor a nuestro stock");
                                            }else{

                                                divitem = $("#divitem" + product["id_product"]).length;

                                                if(divitem > 0){
                                                    //alert("cuanto?: " + divitem);
                                                    $("#divitem" + product["id_product"]).remove()
                                                }

                                                
                                                if(quantity>product[quantity]){
                                                    alert("No contamos con el stock suficiente");
                                                }else{
                                                    $("#show_products").append("<div class=row id=divitem" + product["id_product"] + "><div class=col-4>" + product["product"] + " x" + product["quantity"] + "</div><div class=col-4>" + product["points"] + " pts.</div><div class=col-2>S/" + product["total_import"] + "</div><div class=col-2><a id=remove_divitem" + product["id_product"] + "><i class=bi-dash-circle></i></a></div><input type=hidden name=id_product_cart value=" + product["id_product"] + "><input type=hidden name=quantity_cart value=" + product["quantity"] + "></div>");
                                                    $("#subtotal_cart").html(product["total_amount"].toFixed(2));
                                                    $("#subtotal_cart_in").val(product["total_amount"].toFixed(2));
                                                    $("#discount_amount_cart").html(product["discount_amount"].toFixed(2));
                                                    $("#discount_amount_cart_in").val(product["discount_amount"].toFixed(2));
                                                    $("#discount_applied_cart").html(product["discount_applied"]);
                                                    $("#discount_applied_cart_in").val(product["discount_applied"]); 
                                                    $("#total_cart").html(product["total"].toFixed(2)); 
                                                    $("#total_cart_in").val(product["total"].toFixed(2));
                                                    $("#totalpoints_cart").html(product["total_points"]); 
                                                    $("#totalpoints_cart2").html(product["total_points"]);
                                                    $("#totalpoints_cart2_mob").html(product["total_points"]);
                                                    $("#total_points").val(product["total_points"]);
                                                    $("#session_order").val(product["id_order"]);

                                                    $("#mensaje_metas").html("");
                                                    $("#mensaje_metas").show();
                                                    $("#amp").val(product["total_points"]);
                                                    $("#mensaje_metas").append(product["points_alert_message"]);


                                                    //Recalcular Delivery
                                                    name_delivery = product["name"];
                                                    cost_delivery = product["price"];
                                                    discount_applied = $("#discount_applied_cart_in").val();
                                                    
                                                    if($("#type_member2").is(":checked")){
                                                        total1 = $("#subtotal_cart_in").val();
                                                        total_cart = (parseFloat(total1) + parseFloat(cost_delivery)).toFixed(2);

                                                        $("#total_amount_cart").html("S/ " + total_cart);
                                                        $("#total_amount_cart_mob").html("S/ " + total_cart);
                                                        $("#total_amount_cart_in2").val(total_cart);
                                                    }else{
                                                        total1 = $("#total_cart_in").val();
                                                        total_cart = (parseFloat(total1) + parseFloat(cost_delivery)).toFixed(2);

                                                        $("#total_amount_cart").html("S/ " + total_cart);
                                                        $("#total_amount_cart_mob").html("S/ " + total_cart);
                                                        $("#total_amount_cart_in").val(total_cart);
                                                    }
                                                    
                                                    $("#cost_delivery").html("S/ " + cost_delivery);
                                                    $("#cost_delivery_in").val(cost_delivery);            
                                                    $("#name_delivery_cart").val(name_delivery);              
                                                    $("#department_cart").val(department);
                                                    $("#province_cart").val(province);
                                                    $("#district_cart").val(district);
                                                    $("#address_cart").val(address);
                                                    $("#reference_cart").val(reference);
                                                    $("#indication_cart").val(indication);

                                                    //Type Member
                                                    $("#total_cart_cliente").html(product["total_amount"].toFixed(2));
                                                    $("#totalpoints_cart_cliente").html(product["total_points"]); 
                                                    
                                                }
                                            }
                                        },
                                        statusCode: {                                            
                                            404: function() {
                                                alert("Web not found");
                                            }
                                        },
                                        error:function(x,xs,xt){
                                            
                                            //nos dara el error si es que hay alguno
                                            //window.open(JSON.stringify("x"));
                                            
                                        }
                                    });

                                    
                                    $("#quantity_'.$p["id"].'").val("");
                                });

                            </script>';
                    }
                ?>
            </div>
            <p></p>
            <!--nav class="mb-4 mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
            </nav-->
        </div>
        <div id="carrito" class="col-md-4 col-12 text-center info-socio">
        	<div class="cerrar">
                <p onclick="cerrarCarrito()" id="cerrar">X</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-6">
                    <span class="border border-dark p-2 mb-2 text-center fondo" style="width: 100px !important;float: right;border-radius: 20px;">
                        <?php
                            if(isset($commission['amp_month'])){
                                echo '<b>'.$commission['amp_month'].' pts</b>';
                            } else {
                                echo '<b>0 pts</b>';
                            }
                        ?>
                        Puntaje<br>
                        Del Mes
                    </span>
                </div>
                <!--div class="col-md-4 col-4">
                    <span class="border border-dark p-2 mb-2 text-center fondo" style="width: 100px !important;float: right;border-radius: 20px;">
                        <?php
                            if(isset($commission['amp_history'])){
                                echo '<b>'.$commission['amp_history'].' pts</b>';
                            } else {
                                echo '<b>0 pts</b>';
                            }
                        ?>
                        Puntaje
                        Acumulado
                    </span>
                </div-->
                <div class="col-md-6 col-6">
                    <span class="border border-dark p-2 mb-2 text-center fondo" style="width: 100px !important;float: left;border-radius: 20px;">
                        <?php
                            if(isset($commission['personal_discount'])){
                                echo '<b>'.$commission['personal_discount'].'%</b>';
                            } else {
                                echo '<b>0%</b>';
                            }
                        ?>
                        Descuento
                        Actual
                    </span>
                </div>
            </div>
            <p id="mensaje_metas" class="fst-italic">
                <?php
                    
                    if(isset($commission['points_alert_message'])){
                        echo $commission['points_alert_message'];
                    } 
                ?>
            </p>
            <div class="row">
                <div class="col-md-12 col-12">
		            <form action="https://mlmw.lucasolving.com/shop_tarjeta" id="form_payment_cart" method="POST">
		            
		            @csrf
            		<div class="row pt-2">
		                <div class="col-12">
		                    <span class="border border-dark p-2 mb-2 text-center" style="width: 100% !important;float: left;border-radius: 20px;">
		                        <h4 class="pt-3">¿Quién pagará la compra?</h4>
		                        <div class="mb-3 pt-3">
		                            <div class="form-check form-check-inline">
		                                <input class="form-check-input" type="radio" name="type_member" id="type_member1" value="1">
		                                <label class="form-check-label" for="type_member1">Paga el Socio</label>
		                            </div>
		                            <div class="form-check form-check-inline">
		                                <input class="form-check-input" type="radio" name="type_member" id="type_member2" value="2">
		                                <label class="form-check-label" for="type_member2">Paga el Cliente</label>
		                            </div>
		                        </div>
		                    </span>
		                </div>
		            </div>
		            <div class="row pt-2">
		                <div class="col-12">
		                    <span class="border border-dark p-2 mb-2" style="width: 370px !important;float: left;border-radius: 20px;">                    
		                        <h4 class="pt-3">Resumen de la compra</h4>
		                        <div id="show_products">
		                        <?php
		                            if($orders_detail_products){
		                                foreach ($products as $p) {
		                                }
		                            }
		                        ?>
		                        </div>
		                        <hr>
                        
		                        <?php
		                            if($orders){
		                                echo '<div class="row" id="divitem9"><div class="col-4">Jarra Alcalina x7</div><div class="col-4">210pts.</div><div class="col-2">S/210</div><div class="col-2"><a id="remove_divitem9"><i class="bi-dash-circle"></i></a></div><input type="hidden" name="id_product_cart" value="9"><input type="hidden" name="quantity_cart" value="7"></div>';
		                            } else {
		                                echo '<div class=row id="divitem"><div class=col-4>SUB-TOTAL</div><div class=col-4><span id="totalpoints_cart">0</span> pts.</div><div class=col-2>S/<span id="subtotal_cart">0.00</span></div><div class=col-2></div></div>';
		                                
		                                //Descuento / Comisión
		                                echo '<div class="row text-danger" id="divitem"><div class="col-7" style="text-align:left !important;padding-left:22px;"><span id="commissions_div1">DESCUENTO:</span><span id="commissions_div2">COMISIÓN</span> <span id="discount_applied_cart">0</span>%</div><div class=col-4><span id="commissions_div10">-</span> S/<span id="discount_amount_cart">0.00</span></div></div>';
		                                //End Descuento / Comisión
		
		                                echo '<hr>';
		                                echo '<div id="type_member_div1">';
		                                echo '<div class=row id="divitem"><div class=col-4>TOTAL SOCIO</div><div class=col-4><span id="totalpoints_cart2">0</span> pts.</div><div class=col-2>S/<span id="total_cart">0.00</span></div><div class=col-2></div></div>';
		                                echo '</div>';
		
		                                echo '<div id="type_member_div2">';
		                                echo '<div class=row id="divitem"><div class=col-4>TOTAL CLIENTE</div><div class=col-4><span id="totalpoints_cart_cliente">0</span> pts.</div><div class=col-2>S/<span id="total_cart_cliente">0.00</span></div><div class=col-2></div></div>';
		                                echo '</div>';
		                                
		                            }
		                        ?>
                        
		                        <hr>
		                        <div class="row pt-3">  
		                            <a name="refresh_delivery"></a>                          
		                            <div class="col-2" style="text-align:left">
		                                <!--a href="#" class="link-primary" data-bs-toggle="modal" data-bs-target="#deliveryModal"-->
		                                <a href="#" id="delivery">
		                                Delivery
		                                </a>
		                            </div>
		                            <div class="col-8" style="text-align:right">
		                                <span id="cost_delivery">0.00</span>
		                                <input type="hidden" name="amp" id="amp">
		                                <input type="hidden" name="subtotal" id="subtotal_cart_in">
		                                <input type="hidden" name="discount_applied_cart_in" id="discount_applied_cart_in">
		                                <input type="hidden" name="discount_amount" id="discount_amount_cart_in">
		                                <input type="hidden" name="total_cart_in" id="total_cart_in">
		                                <input type="hidden" name="cost_delivery" id="cost_delivery_in">
		                                <input type="hidden" name="total_amount_cart_in" id="total_amount_cart_in">
		                                <input type="hidden" name="total_points" id="total_points">
		
		                                <input type="hidden" name="name_delivery" id="name_delivery_cart">
		                                <input type="hidden" name="department_cart" id="department_cart">
		                                <input type="hidden" name="province_cart" id="province_cart">
		                                <input type="hidden" name="district_cart" id="district_cart">
		                                <input type="hidden" name="address" id="address_cart">
		                                <input type="hidden" name="reference" id="reference_cart">
		                                <input type="hidden" name="indication" id="indication_cart">
		                                <input type="hidden" name="tkstr" id="tkstr">
		                                <input type="hidden" name="total_amount_cart_in2" id="total_amount_cart_in2">
		
		                                <input type="hidden" name="type_doc" id="type_doc" value="{{auth()->user()->type_doc}}">
		                                <input type="hidden" name="num_doc" id="num_doc" value="{{auth()->user()->num_doc}}">
		                                <input type="hidden" name="email" id="email" value="{{auth()->user()->email}}">
		                                <input type="hidden" name="department_client" id="department_client" value="{{$name_department}}">
		                                <input type="hidden" name="province_client" id="province_client" value="{{$name_province}}">
		                                <input type="hidden" name="district_client" id="district_client" value="{{$name_district}}">
		                                <input type="hidden" name="address_client" id="address_client" value="{{auth()->user()->address}}">
		
		                                <input type="hidden" name="type_of_receipt" id="type_of_receipt_cart">
		                                <input type="hidden" name="type_doc_client" id="type_doc_client_cart">
		                                <input type="hidden" name="num_doc_client" id="num_doc_client_cart">
		                                <input type="hidden" name="name_client" id="name_client_cart">
		                                <input type="hidden" name="firts_name_client" id="firts_name_client_cart">
		                                <input type="hidden" name="last_name_client" id="last_name_client_cart">
		                                <input type="hidden" name="ruc_client" id="ruc_client_cart">
		                                <input type="hidden" name="razon_social_client" id="razon_social_client_cart">
		                                <input type="hidden" name="email_client" id="email_client_cart">
		                                <input type="hidden" name="person_client" id="person_client_cart">
		                                <input type="hidden" name="phone_number_client" id="phone_number_client_cart">                                
		                            </div>
		                        </div>
                                <hr>
                        		<p class="pt-3">TOTAL A PAGAR</p>
		                        <?php
		                            if($orders){
		                                echo '<h2><span id="total_amount_cart">S/ '.$orders['total_amount'].'</span></h2>';
		                            } else {
		                                echo '<h2><span id="total_amount_cart">S/ 0.00</span></h2>';
		                            }
		                        ?>
                                <!--div id="payment_method_div" class="m-5">
                                    <h5>Método de pago:</h5>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="fs-4 col-2">       
                                                <i class="bi bi-credit-card"></i>
                                            </div>
                                            <div class="col-10 pt-2" style="text-align:left;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" value="1" id="payment_method1">
                                                    <label class="form-check-label" for="payment_method">
                                                        Tarjeta de Crédito
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fs-4 col-2">       
                                                <i class="bi bi-cash-coin"></i>
                                            </div>
                                            <div class="col-10 pt-1" style="text-align:left;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" value="2" id="payment_method2">
                                                    <label class="form-check-label" for="payment_method2">
                                                        Transferencia Bancaria
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div-->
		                        <div class="row pt-3 pb-5">
                                    <div class="col-12">
                                        <button type="button" id="payment_cart_admin" class="btn btn-success" style="width:70%">PAGAR ADMIN</button>
                                    </div>
		                            <div class="col-12">
		                                <button type="button" id="payment_cart" class="btn btn-secondary" disabled style="width:70%">PAGAR</button>
		                            </div>
		                            <div class="col-12 pt-3">
		                                <a href="shop_clear" class="btn btn-secondary button-active" style="width:70%">LIMPIAR</a>
		                            </div>
                                    <div class="col-12 pt-3" style="display: none" id="seguirComprando">
                                        <a href="#" onclick="cerrarCarrito()"  class="btn btn-secondary button-active" style="width:70%;">SEGUIR COMPRANDO</a>
                                    </div>
		                        </div>
		                    </span>
		                </div>
		            </div>
				</form>

        	</div>
        </div>
    </div>
    <div class="carrito">
        <div class="shopping-cart d-flex justify-content-center align-items-center">
            <span class="dashboard-title cart-bold">
                <i class="bi bi-cart"></i>
            </span>&nbsp;&nbsp;
            <span id="total_amount_cart_mob">0.00</span>&nbsp;-&nbsp;
            <span id="totalpoints_cart2_mob">0</span>pts

        </div>
        <div class="shopping-cart d-flex justify-content-center align-items-center">
            <button onclick="mostrarCarrito()" class="btn btn-primary button-active" style="width: 80%">Ir al carrito</button>
        </div>
    </div>
</div>

<?php
    foreach ($products as $p) {
        echo '<script>
                $(document).on("click", "#remove_divitem'.$p["id"].'" ,function() {
                    alert("aa");
                    id = '.$p["id"].';
                    department = $("#departament option:selected").text();
                    province = $("#province option:selected").text();
                    district = $("#district option:selected").text();
                    address = $("#address").val();
                    reference = $("#reference").val();
                    indication = $("#indication").val();
                    
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
                        }
                    }),
                    $.ajax({
                        url: "https://mlmw.lucasolving.com/orden_remove_product/" + id,
                        data: {
                            quantity: quantity,
                            id_product: id,
                            department: department,
                            province: province,
                            district: district,
                        },
                        type:"post",
                        success: function (product) {
                            /*alert(product);
                            $.each(product, function(index, value){                                                
                                alert("may: " + value)
                            });*/
                            $("#divitem'.$p["id"].'").remove();

                            if(quantity>product[quantity]){
                                alert("No contamos con el stock suficiente");
                            }else{
                                //$("#show_products").append("<div class=row id=divitem" + product["id_product"] + "><div class=col-4>" + product["product"] + " x" + product["quantity"] + "</div><div class=col-4>" + product["points"] + " pts.</div><div class=col-2>S/" + product["total_import"] + "</div><div class=col-2><a id=remove_divitem" + product["id_product"] + "><i class=bi-dash-circle></i></a></div><input type=hidden name=id_product_cart value=" + product["id_product"] + "><input type=hidden name=quantity_cart value=" + product["quantity"] + "></div>");
                                $("#subtotal_cart").html(product["total_amount"].toFixed(2));
                                $("#subtotal_cart_in").val(product["total_amount"].toFixed(2));
                                $("#discount_amount_cart").html(product["discount_amount"].toFixed(2));
                                $("#discount_amount_cart_in").val(product["discount_amount"].toFixed(2));
                                $("#discount_applied_cart").html(product["discount_applied"]);
                                $("#discount_applied_cart_in").val(product["discount_applied"]); 
                                $("#total_cart").html(product["total"].toFixed(2)); 
                                $("#total_cart_in").val(product["total"].toFixed(2));
                                $("#totalpoints_cart").html(product["total_points"]); 
                                $("#totalpoints_cart2").html(product["total_points"]);
                                $("#totalpoints_cart2_mob").html(product["total_points"]);
                                $("#total_points").val(product["total_points"]);
                                //$("#session_order").html(product["id_order"]);

                                $("#mensaje_metas").html("");
                                $("#mensaje_metas").show();
                                $("#amp").val(product["total_points"]);
                                $("#mensaje_metas").append(product["points_alert_message"]);

                                //Recalcular Delivery
                                name_delivery = product["name"];
                                cost_delivery = product["price"];
                                discount_applied = $("#discount_applied_cart_in").val();

                                if($("#type_member2").is(":checked")){
                                    total1 = $("#subtotal_cart_in").val();
                                    total_cart = (parseFloat(total1) + parseFloat(cost_delivery)).toFixed(2);

                                    $("#total_amount_cart").html("S/ " + total_cart);
                                    $("#total_amount_cart_mob").html("S/ " + total_cart);
                                    $("#total_amount_cart_in2").val(total_cart);
                                }else{
                                    total1 = $("#total_cart_in").val();
                                    total_cart = (parseFloat(total1) + parseFloat(cost_delivery)).toFixed(2);

                                    $("#total_amount_cart").html("S/ " + total_cart);
                                    $("#total_amount_cart_mob").html("S/ " + total_cart);
                                    $("#total_amount_cart_in").val(total_cart);
                                }
                                
                                $("#cost_delivery").html("S/ " + cost_delivery);
                                $("#cost_delivery_in").val(cost_delivery);            
                                $("#name_delivery_cart").val(name_delivery);              
                                $("#department_cart").val(department);
                                $("#province_cart").val(province);
                                $("#district_cart").val(district);
                                $("#address_cart").val(address);
                                $("#reference_cart").val(reference);
                                $("#indication_cart").val(indication);

                                //Type Member
                                $("#total_cart_cliente").html(product["total_amount"].toFixed(2));
                                $("#totalpoints_cart_cliente").html(product["total_points"]);
                            }
                        },
                        statusCode: {
                            404: function() {
                                alert("Web not found");
                            }
                        },
                        error:function(x,xs,xt){
                            //nos dara el error si es que hay alguno
                            //window.open(JSON.stringify(x));
                            alert("Web not found");
                            
                        }
                    });
                });
            </script>';
        }
?>

<!-- Modal Delivery -->
<div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deliveryModalLabel">Delivery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="POST">
      @csrf
      <div class="modal-body">
        <div class="row pb-3">
            <h5>Indícanos los datos de Facturación y Contacto</h5>
            <div class="col">
                <div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type_of_receipt" id="type_of_receipt1" value="B" checked required>
                    <label class="form-check-label" for="type_of_receipt1">Boleta</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type_of_receipt" id="type_of_receipt2" value="F" required>
                    <label class="form-check-label" for="type_of_receipt2">Factura</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="div_boleta">
            <div class="row pb-3">
                <div class="col">
                    <select id="type_doc_client" class="form-select" required>
                        <option value="">Tipo de Documento</option>
                        <option value="DNI">DNI</option>
                        <option value="Carnet_Extranjeria">Carnet Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" id="num_doc_client" class="form-control" placeholder="Nro de Documento">
                </div>
            </div>
            <div class="row pb-3">
                <div class="col">
                    <input type="text" id="name_client" placeholder="Nombre" class="form-control">
                </div>
                <div class="col">
                    <input type="text" id="firts_name_client" placeholder="Apellido Paterno" class="form-control">
                </div>
                <div class="col">
                    <input type="text" id="last_name_client" placeholder="Apellido Materno" class="form-control">
                </div>
            </div>
        </div>
        <div id="div_factura">
            <div class="row pb-3">
                <div class="col">
                    <input type="text" id="ruc_client" placeholder="Número de Ruc" class="form-control">
                </div>
            </div>
            <div class="row pb-3">
                <div class="col">
                    <input type="text" id="razon_social_client" placeholder="Razón Social" class="form-control">
                </div>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col">
                <input type="text" id="email_client" placeholder="Email de facturación" class="form-control">
            </div>
        </div>
        <div class="row pb-3">
            <div class="col">
                <input type="text" id="person_client" placeholder="Persona de contacto para la entrega" class="form-control">
            </div>
            <div class="col">
                <input type="text" id="phone_number_client" placeholder="Teléfono de la persona de contacto" class="form-control">
            </div>
        </div>

        <div class="row pb-3">
            <h5>Indícanos a dónde debemos enviar tus productos</h5>
            <div class="col">
                <select id="departament" name="departament" class="form-select">
                    <option value="">Departamento</option>
                    <?php
                        foreach ($departments as $department) {
                            echo '<option value="'.$department["id_department"].'">'.$department["department"].'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="col" id="province_m">
                <select id="province" name="province" class="form-select">
                    <option value="">Provincia</option>
                </select>
            </div>
            <div class="col">
                <select id="district" name="district" class="form-select">
                    <option value="">Distrito</option>
                </select>
            </div>
        </div>       
        <div class="mb-3">
            <input type="text" placeholder="Dirección" id="address" class="form-control">
        </div>
        <div class="mb-3">
            <input type="text" placeholder="Referencia de cómo llegar" id="reference" class="form-control">
        </div>
        <div class="mb-3">
            <textarea class="form-control" id="indication" placeholder="Escribe alguna indicación que debemos tener en cuenta"  style="height: 100px"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="cart_info" class="btn btn-primary">Enviar</button>
      </div> <!-- data-bs-dismiss="modal" -->
      </form>
    </div>
  </div>
</div>

<input type="hidden" name="session_order" id="session_order">
<script src="https://checkout.culqi.com/js/v4"></script>

@include('../layouts.Footer')
<input type="hidden" id="token_create_order" value="{{ csrf_token() }}" />
<script>
    // Configurar tu API Key y autenticación
    //Culqi.publicKey = 'pk_test_753a58aa78e4f7e2'; //key culqi
    //Culqi.publicKey = 'pk_test_058271dc827f1551'; //key waterlife desarrollo   
    Culqi.publicKey = 'pk_live_15cc2aef3f5107c0'; //key waterlife production
</script>

<script>
$(document).ready(function () {
    
    $("#type_member_div2").hide();
    $("#commissions_div2").hide();
    $("#div_factura").hide();
    
    $("#type_of_receipt1").click(function(){
        $("#div_boleta").show();
        $("#div_factura").hide();
    });
    $("#type_of_receipt2").click(function(){
        $("#div_boleta").hide();
        $("#div_factura").show();
    });    

    $('#departament').on('change', function () {

        var id_departament = this.value;

        $.ajax({
          url: "{{('https://mlmw.lucasolving.com/provincesservices')}}/" + id_departament,
          //data:{ id_departament: id_departament },
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
            url: "{{('https://mlmw.lucasolving.com/districtsservices')}}/" + id_province,
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

    $("#type_member1").click(function(){
        $("#mensaje_metas").show();
        $("#payment_method_div").show();
        $("#type_member_div1").show();
        $("#type_member_div2").hide();
        $("#commissions_div1").show();
        $("#commissions_div10").show();        
        $("#commissions_div2").hide();
        
        total_cart_in = $("#total_cart_in").val();
        cost_delivery_in = $("#cost_delivery_in").val();

        total_cart1 = (parseFloat(total_cart_in) + parseFloat(cost_delivery_in)).toFixed(2);
        $("#total_amount_cart_in").val(total_cart1);
        $("#total_amount_cart").html("S/ " + total_cart1);
        $("#total_amount_cart_mob").html("S/ " + total_cart1);
    });
    $("#type_member2").click(function(){
        $("#mensaje_metas").hide();
        $("#payment_method_div").hide();
        $("#type_member_div1").hide();
        $("#type_member_div2").show();
        $("#commissions_div1").hide();
        $("#commissions_div10").hide();
        $("#commissions_div2").show();

        subtotal_cart_in = $("#subtotal_cart_in").val();
        cost_delivery_in = $("#cost_delivery_in").val();
        
        total_cart2 = (parseFloat(subtotal_cart_in) + parseFloat(cost_delivery_in)).toFixed(2);
        $("#total_amount_cart_in2").val(total_cart2);
        $("#total_amount_cart").html("S/ " + total_cart2);
        $("#total_amount_cart_mob").html("S/ " + total_cart2);
    });

    $("#delivery").click(function(){
        checked_cliente = $('input[name="type_member"]:checked').length;
        
        if(checked_cliente == 0)
        {
            alert("Seleccione quien pagará la compra.");
        }else{
            $('#deliveryModal').modal('show');
        }
    });


    $("#cart_info").click(function(){
        
        type_of_receipt = $('input:radio[name=type_of_receipt]:checked').val();        
        type_doc_client = $("#type_doc_client").val();        
        num_doc_client = $("#num_doc_client").val();
        name_client = $("#name_client").val();
        firts_name_client = $("#firts_name_client").val();
        last_name_client = $("#last_name_client").val();
        ruc_client = $("#ruc_client").val();
        razon_social_client = $("#razon_social_client").val();
        email_client = $("#email_client").val();
        person_client = $("#person_client").val();
        phone_number_client = $("#phone_number_client").val();
        
        department = $("#departament option:selected").text();
        province = $("#province option:selected").text();
        district = $("#district option:selected").text();
        address = $("#address").val();
        reference = $("#reference").val();
        indication = $("#indication").val();

        if(type_of_receipt=='B'){
            if($("#type_doc_client").val()==''){
                alert("Seleccione el Tipo de Documento");
                $("#type_doc_client").focus();
                return true;
            }else if($("#num_doc_client").val()==''){
                alert("Ingrese el Número de Documento");
                $("#num_doc_client").focus();
                return true;
            }else if($("#name_client").val()==''){
                alert("Ingrese el Nombre");
                $("#name_client").focus();
                return true;
            }else if($("#firts_name_client").val()==''){
                alert("Ingrese el Apellido Paterno");
                $("#firts_name_client").focus();
                return true;
            }else if($("#last_name_client").val()==''){
                alert("Ingrese el Apellido Materno");
                $("#last_name_client").focus();
                return true;
            }
        }else if(type_of_receipt=='F'){
            
            if($("#ruc_client").val()==''){
                alert("Ingrese el RUC");
                $("#ruc_client").focus();
                return true;
            }else if($("#razon_social_client").val()==''){
                alert("Ingrese la Razón Social");
                $("#razon_social_client").focus();
                return true;
            }
        }
        if($("#email_client").val()==''){
            alert("Ingrese el Email de Facturación");
            $("#email_client").focus();
            return true;
        }else if($("#person_client").val()==''){
            alert("Ingrese la Persona de contacto para la entrega");
            $("#person_client").focus();
            return true;
        }else if($("#phone_number_client").val()==''){
            alert("Ingrese el Teléfono de la Persona de contacto");
            $("#phone_number_client").focus();
            return true;

        }else if($("#departament").val()==''){
            alert("Seleccione el Departamento");
            $("#departament").focus();
            return true;
        }else if($("#province").val()==''){
            alert("Seleccione la Provincia");
            $("#province").focus();
            return true;
        }else if($("#district").val()==''){
            alert("Seleccione el Distrito");
            $("#district").focus();
            return true;
        }else if($("#address").val()==''){
            alert("Ingrese la Dirección");
            $("#address").focus();
            return true;
        }else if($("#reference").val()==''){
            alert("Ingrese la referencia de cómo llegar");
            $("#reference").focus();
            return true;
        }else{

            $.ajax({
                url: "{{('https://mlmw.lucasolving.com/deliverycost')}}",
                data: {
                    department: department,
                    province: province,
                    district: district,
                    _token: '{{csrf_token()}}'
                },
                type:'post',
                success: function (delivery) {
                    /*alert(delivery);
                    $.each(delivery, function(index, value){
                        alert("may: " + value)
                    });*/                

                    name_delivery = delivery['name'];
                    cost_delivery = delivery['price'];
                    discount_applied = $("#discount_applied_cart_in").val();

                    if($('#type_member2').is(':checked')){
                        total1 = $("#subtotal_cart_in").val();
                        total_cart = (parseFloat(total1) + parseFloat(cost_delivery)).toFixed(2);
                        $("#total_amount_cart_in2").val(total_cart);

                        total2 = $("#total_cart_in").val();
                        total_cart2 = (parseFloat(total2) + parseFloat(cost_delivery)).toFixed(2);
                        $("#total_amount_cart_in").val(total_cart2);
                    }else{
                        total1 = $("#total_cart_in").val();
                        total_cart = (parseFloat(total1) + parseFloat(cost_delivery)).toFixed(2);
                        $("#total_amount_cart_in").val(total_cart);
                                            
                    }

                    $("#total_amount_cart").html("S/ " + total_cart);
                    $("#total_amount_cart_mob").html("S/ " + total_cart);
                    
                    $("#cost_delivery").html("S/ " + cost_delivery);
                    $("#cost_delivery_in").val(cost_delivery);
                    $("#name_delivery_cart").val(name_delivery);
                    $("#department_cart").val(department);
                    $("#province_cart").val(province);
                    $("#district_cart").val(district);
                    $("#address_cart").val(address);
                    $("#reference_cart").val(reference);
                    $("#indication_cart").val(indication);
                    
                    $("#type_of_receipt_cart").val(type_of_receipt);
                    $("#type_doc_client_cart").val(type_doc_client);
                    $("#num_doc_client_cart").val(num_doc_client);
                    $("#name_client_cart").val(name_client);
                    $("#firts_name_client_cart").val(firts_name_client);
                    $("#last_name_client_cart").val(last_name_client);
                    $("#ruc_client_cart").val(ruc_client);
                    $("#razon_social_client_cart").val(razon_social_client);
                    $("#email_client_cart").val(email_client);
                    $("#person_client_cart").val(person_client);
                    $("#phone_number_client_cart").val(phone_number_client);

                    console.log('aqui');
                    checked_cliente = $('input[name="type_member"]:checked').length;
            
                    if(checked_cliente == 0)
                    {
                        alert("Seleccione quien pagará la compra.");
                    }else{
                        $('#payment_cart').prop('disabled', false);
                    }
                },
                statusCode: {
                    404: function() {
                        alert('Web not found');
                    }
                },
                error:function(x,xs,xt){
                    //nos dara el error si es que hay alguno
                    //window.open(JSON.stringify('x'));
                    $("#cost_delivery").html('No hay cobertura al destino ingresado.');
                }
            });

        }

        $('#deliveryModal').modal('toggle');

    });

    $("#payment_cart").click(function(e){

        cost_delivery_val = $("#cost_delivery_in").val();        
        session_order = $("#session_order").val();
        //alert(session_order);

        let orderCulquiId = null;
        
        checked_cliente = $('input[name="type_member"]:checked').length;
        
        if(checked_cliente == 0)
        {
            alert("Seleccione quién pagará la compra.");
        }else{

            if($('#type_member2').is(':checked')){
                total_amount_cart = $("#total_amount_cart_in2").val();
                type_member=false;
            }else{
                type_member=true;
                total_amount_cart = $("#total_amount_cart_in").val();
            }

            total_amount = total_amount_cart.replace(/\./g, '');

            //culquiCheckout(type_member,total_amount,session_order)
            
            token_create_order = $("#token_create_order").val();


            $.ajax({
                url: "{{('https://mlmw.lucasolving.com/create-order')}}",
                data: {
                    total_amount: total_amount,
                    session_order: session_order,
                    _token: token_create_order
                },
                type:'post',
                success: function (order) {
                    /*alert(order);
                    $.each(order, function(index, value){                                                
                        alert("message: " + value)
                    });*/
                    orderCulquiId = order;

                    culquiCheckout(type_member,total_amount,orderCulquiId)                    
                },
                statusCode: {
                    404: function() {
                        alert('Web not found');
                    }
                },
                error:function(x,xs,xt){
                    //nos dara el error si es que hay alguno
                    //window.open(JSON.stringify('x'));
                    console.log(x, xs, xt)
                }
            });
        }
    });



    
    $("#payment_cart_admin").click(function(e){

        cost_delivery_val = $("#cost_delivery_in").val();        
        session_order = $("#session_order").val();
        checked_cliente = $('input[name="type_member"]:checked').length;

        if(checked_cliente == 0)
        {
            alert("Seleccione quién pagará la compra.");
        }else{

            if($('#type_member2').is(':checked')){
                total_amount_cart = $("#total_amount_cart_in2").val();
                type_member=false;
            }else{
                type_member=true;
                total_amount_cart = $("#total_amount_cart_in").val();
            }

            total_amount = total_amount_cart.replace(/\./g, '');
    
            token_create_order = $("#token_create_order").val();
            
            $('#form_payment_cart').attr('action', 'shop_admin_approve');
            $("#form_payment_cart").submit();

        }
    });
    

});
</script>

<script>
    function culquiCheckout(type_member,total_amount,orderCulquiId) {
        Culqi.options({
            lang: "auto",
            installments: false, // Habilitar o deshabilitar el campo de cuotas
            paymentMethods: {
                //tarjeta: type_member,
                tarjeta: true,
                bancaMovil: true,
                agente: true,
                yape: false,
                billetera: false,
                cuotealo: false,
            },
        });

        Culqi.settings({
            title: 'Waterlife',
            currency: 'PEN',  // Este parámetro es requerido para realizar pagos yape
            amount: total_amount,  // Este parámetro es requerido para realizar pagos yape
            order: orderCulquiId // Este parámetro es requerido para realizar pagos con pagoEfectivo, billeteras y Cuotéalo
        });

        Culqi.open();
        e.preventDefault();
    }


</script>


<script>
    Culqi.options({
      style: {
        logo: 'https://mlmw.lucasolving.com/images/logo.png',
        bannerColor: '', // hexadecimal
        buttonBackground: '', // hexadecimal
        menuColor: '', // hexadecimal
        linksColor: '', // hexadecimal
        buttonText: '', // texto que tomará el botón
        buttonTextColor: '', // hexadecimal
        priceColor: '' // hexadecimal
      }
    });

    function culqi() {
        if (Culqi.token) {  // ¡Objeto Token creado exitosamente!
            const token = Culqi.token.id;
            console.log('Se ha creado un Token: ', token);
            //En esta linea de codigo debemos enviar el "Culqi.token.id"
            //hacia tu servidor con Ajax
            //alert("enviar formulario");
            
            $("#tkstr").val(token);
            $("#form_payment_cart").submit();

        } else if (Culqi.order) {  // ¡Objeto Order creado exitosamente!
            const order = Culqi.order;
            //console.log('Se ha creado el objeto Order: ', order);
            
            amp = $("#amp").val();
            subtotal = $("#subtotal_cart_in").val();
            discount_applied_cart_in = $("#discount_applied_cart_in").val();
            discount_amount = $("#discount_amount_cart_in").val();
            total_cart_in = $("#total_cart_in").val();
            cost_delivery = $("#cost_delivery_in").val();
            total_amount_cart_in = $("#total_amount_cart_in").val();
            total_points = $("#total_points").val();
            name_delivery = $("#name_delivery_cart").val();
            department_cart = $("#department_cart").val();
            province_cart = $("#province_cart").val();
            district_cart = $("#district_cart").val();
            address = $("#address_cart").val();
            reference = $("#reference_cart").val();
            indication = $("#indication_cart").val();
            type_doc = $("#type_doc").val();
            num_doc = $("#num_doc").val();
            email = $("#email").val();
            department_client = $("#department_client").val();
            province_client = $("#province_client").val();
            district_client = $("#district_client").val();
            address_client = $("#address_client").val();
            
            type_of_receipt = $("#type_of_receipt_cart").val();
            type_doc_client = $("#type_doc_client_cart").val();
            num_doc_client = $("#num_doc_client_cart").val();
            name_client = $("#name_client_cart").val();
            firts_name_client = $("#firts_name_client_cart").val();
            last_name_client = $("#last_name_client_cart").val();
            ruc_client = $("#ruc_client_cart").val();
            razon_social_client = $("#razon_social_client_cart").val();
            email_client = $("#email_client_cart").val();
            person_client = $("#person_client_cart").val();
            phone_number_client = $("#phone_number_client_cart").val();

            payment_status = 'Pendiente';
            if($('#type_member2').is(':checked')){
                type_member=2;
            }else{
                type_member=1;
            }
            codigo_cip = order["payment_code"];
            
            
            $.ajax({
                url: "{{('https://mlmw.lucasolving.com/update_order')}}",
                data: {
                    total_amount: total_amount,
                    session_order: session_order,
                    amp: amp,
                    subtotal: subtotal,
                    discount_applied_cart_in: discount_applied_cart_in,
                    discount_amount: discount_amount,
                    total_cart_in: total_cart_in,
                    cost_delivery: cost_delivery,
                    total_amount_cart_in: total_amount_cart_in,
                    total_points: total_points,
                    name_delivery: name_delivery,
                    department_cart: department_cart,
                    province_cart: province_cart,
                    district_cart: district_cart,
                    address: address,
                    reference: reference,
                    indication: indication,
                    payment_status: payment_status,
                    type_member: type_member,
                    codigo_cip: codigo_cip,
                    type_doc: type_doc,
                    num_doc: num_doc,
                    email: email,
                    department_client: department_client,
                    province_client: province_client,
                    district_client: district_client,
                    address_client: address_client,
                    type_of_receipt: type_of_receipt,
                    type_doc_client: type_doc_client,
                    num_doc_client: num_doc_client,
                    name_client: name_client,
                    firts_name_client: firts_name_client,
                    last_name_client: last_name_client,
                    ruc_client: ruc_client,
                    razon_social_client: razon_social_client,
                    email_client: email_client,
                    person_client: person_client,
                    phone_number_client: phone_number_client,
                    _token: '{{csrf_token()}}'
                },
                type:'post',
                success: function (codigo_cip) {
                    console.log('Se ha guardado los datos.');
                    setTimeout(function () {
                        window.location.href = "https://mlmw.lucasolving.com/shop_pago_efectivo/"+codigo_cip;
                    }, 10000); //will call the function after 2 secs.
                },
                statusCode: {
                    404: function() {
                        alert('Web not found');
                    }
                },
                error:function(x,xs,xt){
                    //nos dara el error si es que hay alguno
                    //window.open(JSON.stringify('x'));
                                        
                    console.log(x, xs, xt)
                }
            });

        } else {
            // Mostramos JSON de objeto error en consola
            console.log('Error: ',Culqi.error);
        }
    };
</script>

<script>
    function mostrarCarrito(){
        let carrito = document.getElementById("carrito");

        let seguirComprando = document.getElementById("seguirComprando");

        let cerrar = document.getElementById("cerrar");

        seguirComprando.style.display   = 'block'

        cerrar.style.opacity   = '1'

        carrito.style.display   = 'block'
        carrito.style.position  = 'fixed';
        carrito.style.bottom    = '80px';
        carrito.style.top    = '60px';
        carrito.style.overflow    = 'scroll';
        carrito.style.backgroundColor   = 'white';
    }

    function cerrarCarrito(){
        let carrito = document.getElementById("carrito");
        carrito.style.display   = 'none'
    }

</script>

