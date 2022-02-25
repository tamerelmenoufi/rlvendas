<?php
   include("../../../../lib/includes.php");

   if($_POST['acao'] === 'excluir'){
        $query = "delete from clientes_enderecos where codigo = '{$_POST['codigo']}'";
        mysql_query($query);
        //exit();
    }

    if($_POST['acao'] === 'salvar'){
    $campos = [];
    for($i=0; $i < count($_POST['campos']); $i++ ){

            $campos[] = $_POST['campos'][$i]." = '".utf8_decode($_POST['valores'][$i])."'";

    }
    if($_POST['end_cod']){
       echo $query = "update clientes_enderecos set ".implode(", ", $campos). " where codigo = '{$_POST['end_cod']}'";
        mysql_query($query);
        echo $_POST['end_cod'];
        //$_SESSION['ms_cli_codigo'] = $_POST['end_cod'];
    }else{

       echo $query = "insert into clientes_enderecos set ".implode(", ", $campos);
        mysql_query($query);
        echo $novo_codigo = mysql_insert_id();
        $_SESSION['ms_cli_codigo'] = $novo_codigo;
    }

    exit();
    }
    if($_POST['acao'] === 'ativar'){
    mysql_query ( "update clientes_enderecos set cli_end_padrao = '0' where cli_codigo = '{$_SESSION['ms_cli_codigo']}' ");

    mysql_query ( "update clientes_enderecos set cli_end_padrao = '1' where codigo = '{$_POST['codigo']}'");

    exit();
    }

    $select = "select * from clientes_enderecos where cli_codigo = '{$_SESSION['ms_cli_codigo']}'";
    $result = mysql_query($select);
    $n = mysql_num_rows($result);

    $query_end="SELECT c.*, b.brs_bairro  FROM clientes_enderecos c INNER JOIN bairros b ON c.cli_end_bairro = b.codigo WHERE cli_end_padrao = 1 and cli_codigo ='{$_SESSION['ms_cli_codigo']}'";
    $query_end="SELECT * FROM clientes_enderecos WHERE cli_end_padrao = 1 and cli_codigo ='{$_SESSION['ms_cli_codigo']}'";
    $result_ = mysql_query($query_end);
    $d_end = mysql_fetch_object($result_);

?>
<style>
.ms_usuario_endereco_titulo_topo{
    position:fixed;
    left:0;
    top:0;
    width:100%;
    height:65px;
    background-color:rgba(255,255,255,0.6);;
    text-align:center;
    color:#777;
    font-size:18px;
    font-weight:bold;
    z-index:10;
    padding:15px;
}

.ms_usuario_endereco_mapa{
    position:fixed;
    top:-20px;
    left:-100%;
    bottom:60%;
    width:300%;
    border-radius:90%;
    z-index:9;
}

.ms_usuario_endereco_titulo{
    position:relative;
    margin-top:60%;
    width:100%;
    text-align:left;
    color:#194B38;
    font-size:22px;
    font-weight:bold;
    padding:15px;
}

.ms_usuario_endereco_titulo_form{
    position:relative;
    width:100%;
    border:solid 0px red;
    margin-bottom:15px;
}
.ms_usuario_endereco_titulo_form_rotulo{
    position:relative;
    margin-bottom:0px;
    margin-left:15px;
    color:#ccc;
    font-size:12px;
}

.ms_usuario_endereco_titulo_form_campo{
    position:relative;
    width:100%;
    padding:0px 10px 10px 10px;
    height:50px;
}

.ms_usuario_endereco_titulo_form_campo input{
    position:relative;
    width:100%;
    height:50px;
    background-color:#F1F3F2;
    background-position:left 15px center;
    background-size:20px;
    background-repeat:no-repeat;
    color:#777;
    border-radius:10px;
    padding-left:45px;
    padding-right:5px;
    font-size:18px;
    border:0;
}
    .ms_usuario_endereco_titulo_form_campo select{
    position:relative;
    width:100%;
    height:50px;
    background-color:#F1F3F2;
    background-position:left 15px center;
    background-size:20px;
    background-repeat:no-repeat;
    color:#777;
    border-radius:10px;
    padding-left:45px;
    padding-right:5px;
    font-size:18px;
    border:0;
}

.ms_usuario_endereco_titulo_form_campo svg{
    position:absolute;
    left:20px;
    top:15px;
    color:#777;
    font-size:20px;
    z-index:1;
    }
.ms_usuario_compras_100_click{
    position:relative;
    width:100%;
    height:120px;
    margin-bottom:10px;
}

 .ms_usuario_compras_100{
    position:relative;
    width:100%;
    height:120px;
    margin-bottom:10px;
}
    .ms_usuario_compras_100_item{
    position:absolute;
    left:10px;
    right:10px;
    height:100%;
    background-color:#F1F3F2;
    padding-top:10px;
    padding-left:20px;
    padding-right:10px;
    border-radius:25px;
    color:#777777;
    cursor:pointer;
    }

    .ms_usuario_compras_100_item_click{
    border: solid 2px #4CBB5E;
    background-color: #F1F3F2;
    border-radius: 25px;
}

    .end_principal{
    position: relative;
    padding-left: 15px;
    padding-bottom: 15px;
}
.ms_usuario_end_novo_end{
      position:relative;
        width:100%;
        height: 101px;

}
.ms_usuario_end_novo_end_item{
position: absolute;
    left: 10px;
    right: 9px;
    height: 43%;
    background-color: #4CBB5E;
    padding-top: 10px;
    /* padding-left: 78px; */
    /* padding-right: 10px; */
    border-radius: 25px;
    color: #777777;
    cursor: pointer;
    text-align: center;
}
.ms_usuario_add_editar{
    position: absolute;
    right: 0;
    z-index: 1;
    bottom: 60%;
    width: 47px;
    height: 47px;
    padding: 20px;
    margin-left: 292px;
    margin-top: 20px;
    /* background-color: red;*/
}
.cli_end_apelido{
width: 191px;
    /* height: auto; */
    line-height: 28px;
    /* text-align: center; */
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    margin-bottom: -2px;
    align-items: flex-start;
    align-self: initial;
    color: #194B38;
}
.cli_end_rua{
    width: 250px;
    height: auto;
    /* line-height: 14px; */
    /*text-align: center;*/
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    /* margin-bottom: -2px; */
    margin-top: 12px;
}
.cli_end_ref{
    width: 227px;
    height: auto;
    line-height: 17px;
    /*text-align: center;*/
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    /* margin-bottom: -2px; */
    margin-top: -8px;
    font-size: 12px;
}
.ativa_carrinho{
    position:fixed;
    right:15px;
    top:15px;
    z-index:11;
    color:#eee;
    border:solid 1px #ccc;
    padding:7px;
    background-color:#fff;
    border-radius:10px;
}

</style>

<div tela_lista class="container">

<div class="ms_usuario_endereco_mapa"></div>

<div class="ativa_carrinho">
    <i class="fas fa-cart-arrow-down fa-2x"></i>
</div>

<h3 class="ms_usuario_endereco_titulo">Lista dos Endereços </h3>

<!-- <div class="ms_usuario_end_selecionado">
    <div class="ms_usuario_end_selecionado_item">
        <h4 style="color: #194B38;">Casa</h4>
        <p>
           Rua espirito santo, 30 - lirio do vale

        </p>

         <i style="font-size: 12px;">
          Portao branco , muro rosa

        </i>
    </div>
</div>
 -->

<div class="ms_usuario_end_novo_end" ativar="<?=(($n)?false:'1')?>" add_end="<?=$d->codigo?>" add_cli="<?=$_SESSION['ms_cli_codigo']?>" >
<div class="ms_usuario_end_novo_end_item">
<p style="color:white">
 Adcionar novo endereço
</p>
</div>
</div>

<!--
<div class="end_principal">
    <h3 style="font-size: 15px;">Endereço atual</h3>

</div>
-->
<?php
while($d = mysql_fetch_object($result)){
?>
<div>
<div
    card<?=$d->codigo?>
    class="ms_usuario_compras_100 <?=(($d->cli_end_padrao == '1')?'ms_usuario_compras_100_item_click':false)?>"

    dots
    acao<?=$md5?>
    local="src/usuarios/lista_end_opc.php"
    descricao="<?=utf8_encode($d->cli_end_rua)?>"
    codigo="<?=$d->codigo?>"


>

        <div
            class="ms_usuario_add_editar"
        >
            <label>
                <i class="fas fa-ellipsis-v"></i>
            </label>
        </div>

    <div class="ms_usuario_compras_100_item">

<?php
if( $d->cli_end_apelido){
?>
      <h4 class="cli_end_apelido"><?=utf8_encode($d->cli_end_apelido)?></h4>
<?php
}
else{
?>
<h4 style="color: #194B38;"></h4>

<?php
}
?>
    <p card_rua class="cli_end_rua"><?=utf8_encode($d->cli_end_rua)?></p>

    <!-- <b><?=$d->codigo?></b>; -->
    <!-- <b><?=utf8_encode($d_end->cli_end_numero)?></b> -->

     <p card_referencia class="cli_end_ref">
    <?=utf8_encode($d->cli_end_ponto_referencia)?>

  <!--           <button
                acao opc="ativar"
                codigo = "<?=$d->codigo?>"
                local= "src/usuarios/lista_end.php">Ativar
        </button>
            <button
                    acao
                    opc="editar"
                    codigo="<?=$d->codigo?>"
                    local="src/usuarios/enderecos.php"

            >Editar</button> -->
        </p>
    </div>
</div>
</div>
<?php
}
?>


<!-- <div class="ms_usuario_compras_100_click">
    <div class="ms_usuario_compras_100_item_click">
        <h4 style="color: #194B38;">Casa</h4>
        <p>
           Rua espirito santo, 30 - lirio do vale

        </p>

         <p style="margin-top: 5px; font-size: 12px;">
          Portao branco , muro rosa

        </p>
    </div>
</div>
 -->
</div>
<script>

    $(function(){
        Carregando('none');

//     map = new GMaps({
//     div: '#ms_usuario_endereco_mapa_local<?=$md5?>',
//     zoom: 16,
//     lat: -12.043333,
//     lng: -77.028333,

//     click: function(e) {
//         alert('click');
//     },
//     dragend: function(e) {
//         alert('dragend');
//     }
//     });
//     map.addMarker({
//   lat: -12.043333,
//   lng: -77.028333,
//   title: 'Minha localização',
//   draggable:true,
//    dragend: function(event) {
//     var lat = event.latLng.lat();
//     var lng = event.latLng.lng();
//         alert('dragend' +lat+'-'+ lng);
//     },
//   click: function(e) {
//     alert('You clicked in this marker');
//   }
// });

/*
    map = new GMaps({
    div: '#ms_usuario_endereco_mapa_local<?=$md5?>',
    zoom: 16,
    lat: -12.043333,
    lng: -77.028333,

    click: function(e) {
        alert('Endereco nao editavel');
    },
    });

GMaps.geocode({
    address: '<?=$d_end->cli_end_rua?>,<?=$d_end->cli_end_numero?> - <?=utf8_encode($d_end->brs_bairro)?>, manaus - AM, BRASIL',
   // postalCode: '69038110',
    //location:
 //    componentRestrictions: {
 //    country: 'BR',
 //    postalCode: '69038110',
 // },
      callback: function(results, status) {
        if (status == 'OK') {
            //console.log(results);

        var latlng = results[0].geometry.location;
        map.setCenter(latlng.lat(),latlng.lng()); // Função para centralizar
        map.setZoom(18); // Função para ajuste de zoom
              map.addMarker({ // Função para adicionar o marcador
                    draggable: false,
                    lat: latlng.lat(),
                    lng: latlng.lng()
              });
        }
        else {
              alert('Endereço não encontrado');
        }
      }
});
//*/
// GMaps.geolocate({
//   success: function(position) {
//     mapObj.setCenter(position.coords.latitude, position.coords.longitude);
//   },
//   error: function(error) {
//     alert('Geolocation failed: ' + error.message);
//   },
//   not_supported: function() {
//     alert("Your browser does not support geolocation");
//   },
//   always: function() {
//     alert("Always");
//   }
// });



    $.ajax({
        url:"src/usuarios/endereco_mapa.php",
        type:"POST",
        data:{
            cod_end:'<?=$d_end->codigo?>',
        },
        success:function(dados){
            $(".ms_usuario_endereco_mapa").html(dados);
        }
    });


    $("button[acao]").click(function(){

        opcao = $(this).attr('opc');
        codigo = $(this).attr('codigo');
        local = $(this).attr('local');
    $.confirm({
        content:"Mudar de endereço para entrega?",
        title:true,
        buttons:{
            'NÃO':function(){
            },
            'SIM':function(){
                $(".ms_usuario_compras_100").removeClass('ms_usuario_compras_100_item_click');
                $("div[card"+codigo+"]").addClass('ms_usuario_compras_100_item_click');
                $.ajax({
                    url:local,
                    type:"POST",
                    data: {
                        codigo,
                        acao:opcao
                    },
                    success:function(dados){


                    },
                    error:function(){

                    }
                });
            }
        }
    });
});


    $("div[dots]").off('click').on('click',function(){

    local = $(this).attr('local');
    codigo = $(this).attr('codigo');
    descricao = $(this).attr('descricao');
    Carregando('none');
    $.ajax({
        url:"componentes/ms_popup.php",
        type:"POST",
        data:{
            local,
            codigo,
            descricao,
        },
        success:function(dados){
            $(".ms_corpo").append(dados);
            //console.log(codigo);
        }
    });
});

    $(".ms_usuario_end_novo_end").off('click').click(function(){
    //alert("ok");
    add_end = $(this).attr("add_end");
    add_cli = $(this).attr("add_cli");
    ativar = $(this).attr("ativar");
    local="src/usuarios/cadastro/endereco.php";
     $.ajax({
        url:"componentes/ms_popup_100.php",
        type:"POST",
        data:{
            add_end,
            add_cli,
            local,
            ativar,
        },
        success:function(dados){
            //$("div[tela_lista]").html(dados);
               // console.log(dados);
                $(".ms_corpo").append(dados);

        }
    });

    });

        $("button[end_salvar]").off('click').click(function(){
            campos = [];
            valores = [];
            end_cod = $(this).attr("end_cod");
            $("input[cli], select[cli]").each(function(){
                campos.push($(this).attr("id"));
                valores.push($(this).val());
            });
            //console.log(valores);
            $.ajax({
                    url:"src/usuarios/enderecos.php",
                    type:'POST',
                    data:{
                        campos,
                        valores,
                        end_cod,
                        acao:'salvar'
                    },
                    success:function(retorno){
                        // window.localStorage.setItem('ms_cli_codigo', retorno);
                         //console.log(retorno);
                $.ajax({
                    url:"src/usuarios/enderecos.php",
                    type:'POST',
                    success:function(retorno){

                        // window.localStorage.setItem('ms_cli_codigo', retorno);
                         //console.log(retorno);

                    }
            });
                    }
            });
            //*/

        });


        $(".ativa_carrinho").off('click').on('click',function(){

            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"src/usuarios/carrinho.php",
                },
                success:function(dados){
                    //$(".ms_corpo").append("<div barra_busca_topo>"+dados+"</div>");
                    $(".ms_corpo").append(dados);
                }
            });

        })


    })
</script>