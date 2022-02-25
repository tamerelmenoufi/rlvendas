<?php
   include("../../../../lib/includes.php");

if($_POST['acao'] === 'coordenadas'){
    echo $query = "update clientes_enderecos set cli_end_latitude ='{$_POST['lat']}', cli_end_longitude = '{$_POST['lng']}'  where codigo = '{$_POST['end_cod']}'";
    mysql_query($query);
    exit();
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

        echo $query = "insert into clientes_enderecos set ".implode(", ", $campos) ;
        mysql_query($query);
        echo $novo_codigo = mysql_insert_id();
        //$_SESSION['ms_cli_codigo'] = $novo_codigo;
        echo $novo_codigo;
    }

    exit();
}

    if($_POST['codigo_cli']){
         $select = "select * from clientes_enderecos where codigo = '{$_POST['codigo_cli']}'";
    }else{
         $select = "select * from clientes_enderecos where codigo = '{$_POST['add_end']}'";
    }

    $result = mysql_query($select);
    $d = mysql_fetch_object($result);

    $query_end="SELECT c.*, b.brs_bairro FROM clientes_enderecos c INNER JOIN bairros b ON c.cli_end_bairro = b.codigo WHERE c.codigo ='$d->codigo'";

    $query_end="SELECT * FROM clientes_enderecos WHERE codigo ='$d->codigo'";
    $result_ = mysql_query($query_end);
    $d_end = mysql_fetch_object($result_);


   // echo $select = "select * from clientes_enderecos where codigo = '{$_POST['add_end']}'";
   //  $result = mysql_query($select);

   //  $d = mysql_fetch_object($result);


   if(is_array($_POST['Dados']['ListaDados'][$_POST['opcao']])){
        $CamposNovos = $_POST['Dados']['ListaDados'][$_POST['opcao']];

        $NomeCampos = array(
            'formatted_address' => 'cli_end_resumo',
            'street_number' => 'cli_end_numero',
            'route' => 'cli_end_rua',
            'political' => 'cli_end_bairro',
            'administrative_area_level_2' => 'cli_end_cidade',
            'administrative_area_level_1' => 'cli_end_estado',
            'country' => 'cli_end_pais',
            'postal_code' => 'cli_end_cep',
            'lat' => 'cli_end_latitude',
            'lng' => 'cli_end_longitude'
        );
        $d = new \stdClass();
        for($i=0;$i<count($CamposNovos);$i++){
            $clss = explode("|",$CamposNovos[$i]);
            if(trim($NomeCampos[$clss[0]]) and trim($clss[1])){
                $d->{$NomeCampos[$clss[0]]} = utf8_decode($clss[1]);
            }

        }

   }



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

.ms_usuario_endereco_mapa<?=$md5?>{
    position:fixed;
    top:-20px;
    left:-100%;
    bottom:60%;
    width:300%;
    border-radius:90%;
    z-index:9;
}
#ms_usuario_endereco_mapa_local<?=$md5?>{
    position:absolute;
    width:100%;
    height:100%;
}

.ms_usuario_endereco_titulo{
    position:relative;
    margin-top:55%;
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
    height:100%;
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


</style>

<div class="ms_usuario_endereco_titulo_topo">Locais de Entrega</div>

<div class="ms_usuario_endereco_mapa<?=$md5?>"></div>

<h3 class="ms_usuario_endereco_titulo">Endereços </h3>
<?php /*print_r($CamposNovos)*/ ?>
<div class="ms_usuario_endereco_titulo_form">
      <form class="form-cadastro-empresa">

    <div class="form-group">
        <div class="ms_usuario_endereco_titulo_form_rotulo">
            <label>Rua<i class="text-danger">*</i></label>
        </div>
        <div class="ms_usuario_endereco_titulo_form_campo">
        <input cli type="text" id="cli_end_rua" value="<?=utf8_encode($d->cli_end_rua)?>" />
        <span><i class="fas fa-road"></i></span>
        </div>
    </div>


    <div class="ms_usuario_endereco_titulo_form_rotulo">
        <label>Bairro<i class="text-danger">*</i></label>
    </div>
    <div class="ms_usuario_endereco_titulo_form_campo">
<!--
    <select
    cli
    type="text"
    id="cli_end_bairro"
    name="cli_end_bairro"
    class="form-control">
    <option value=""></option>
    <?php
    $queryBairro = "SELECT * FROM `bairros` WHERE brs_cidade = 243";
    $resultBairro = mysql_query($queryBairro);

    while ($cli_bairro = mysql_fetch_object($resultBairro))
    {
    ?>
        <option value="<?= $cli_bairro->codigo; ?>" bairro_cod="<?= $cli_bairro->codigo; ?>" <?=(($d->cli_end_bairro == $cli_bairro->codigo)?'selected':false)?> >
        <?=  utf8_encode($cli_bairro->brs_bairro);?>
        </option>
    <?php
    }
    ?>

    </select>
-->
    <input cli type="text" name="cli_end_bairro" id="cli_end_bairro" value="<?=utf8_encode($d->cli_end_bairro)?>" />

    <span><i class="fas fa-map-pin"></i></span>
    </div>

<div class="container">

    <div class="row" style="  margin-right: -29px;margin-left: -30px;">
        <div class="col align-self-start">
            <div class="ms_usuario_endereco_titulo_form_rotulo">
                <label>Número da casa<i class="text-danger">*</i></label>
            </div>
            <div class="ms_usuario_endereco_titulo_form_campo">
            <input cli type="text" id="cli_end_numero" value="<?=$d->cli_end_numero?>" />
            <span><i class="fas fa-road"></i></span>
            </div>

        </div>

        <div class="col align-self-end">
            <div class="ms_usuario_endereco_titulo_form_rotulo">
                <label for="cli_end_cep">Cep<i class="text-danger">*</i></label>
            </div>
            <div class="ms_usuario_endereco_titulo_form_campo">
                <input cli type="text" id="cli_end_cep" value="<?=(($d->cli_end_cep)?:'Manaus')?>" />
                <span><i class="fas fa-street-view"></i></span>
            </div>

        </div>
    </div>
</div>

    <div class="form-group">
        <div class="ms_usuario_endereco_titulo_form_rotulo">
        <label for="cli_end_estado">Estado<i class="text-danger">*</i></label>
        </div>

        <div class="ms_usuario_endereco_titulo_form_campo">
    <?php
        $queryEstado = "SELECT * FROM estados where codigo = 3 ORDER BY est_estado";
        $resultEstado = mysql_query($queryEstado);
        $cli_Estado = mysql_fetch_object($resultEstado)
    ?>
    <input cli type="text" name="cli_end_estado" id="cli_end_estado" value="<?=utf8_encode((($d->cli_end_estado)?:'Amazonas'))?>" />
<!--   <select
    cli
    type="text"
    id="cli_end_estado"
    name="cli_end_estado"
    class="form-control">
    <option value=""></option>
    <?php
    $queryEstado = "SELECT * FROM estados ORDER BY est_estado";
    $resultEstado = mysql_query($queryEstado);

    //while ($cli_Estado = mysql_fetch_object($resultEstado))
    { ?>
        <option value="<?= $cli_Estado->codigo; ?>" <?=(($d->cli_end_estado == $cli_Estado->codigo)?'selected':false)?> >
            <?=  utf8_encode($cli_Estado->est_estado); ?>
        </option>
    <?php } ?>

    </select> -->
    <span><i class="fas fa-map-pin"></i></span>
    </div>

</div>


<div class="form-group">
    <div class="ms_usuario_endereco_titulo_form_rotulo">
        <label for="cli_end_cidade">Cidade<i class="text-danger">*</i></label>
    </div>
    <div class="ms_usuario_endereco_titulo_form_campo">
<?php
 $queryCidade = "SELECT * FROM `cidades` WHERE codigo ='$d->cli_end_cidade'";
    $resultCidade = mysql_query($queryCidade);
    $d_cidade = mysql_fetch_object($resultCidade);
?>
 <input cli type="text" name="cli_end_cidade" id="cli_end_cidade" value="<?=utf8_encode($d->cli_end_cidade)?>" />

    <span><i class="fas fa-city"></i></span>
    </div>
</div>


<div class="form-group">
     <div class="ms_usuario_endereco_titulo_form_rotulo">Complemento</div>
     <div class="ms_usuario_endereco_titulo_form_campo">
    <input cli type="text" id="cli_end_complemento" value="<?=addslashes(utf8_encode($d->cli_end_complemento))?>" />
    <span><i class="fas fa-thumbtack"></i></span>
    </div>
</div>

<div class="form-group">
     <div class="ms_usuario_endereco_titulo_form_rotulo">Ponto de referencia</div>
     <div class="ms_usuario_endereco_titulo_form_campo">
    <input cli type="text" id="cli_end_ponto_referencia" value="<?=utf8_encode($d->cli_end_ponto_referencia)?>" />
    <span><i class="fas fa-map-marker-alt"></i></span>
    </div>
</div>

<!-- <div class="form-group">

</div>
 -->


<div class="form-group">
     <div class="ms_usuario_endereco_titulo_form_rotulo">Salvar como:</div>

    <div class="ms_usuario_endereco_titulo_form_campo">
    <input cli type="text" id="cli_end_apelido" value="<?=utf8_encode($d->cli_end_apelido)?>" />
    <span><i class="fas fa-home"></i></span>
    </div>
</div>


</form>
</div>


<div class="w3-padding">
    <button end_salvar end_cod="<?=$d->codigo?>" class="btn btn-success btn-block" >SALVAR</button>
    <input cli type="hidden" id="cli_codigo" value="<?=$_SESSION['ms_cli_codigo']?>">
    <input cli type="hidden" id="cli_end_latitude" value="<?=$d->cli_end_latitude?>">
    <input cli type="hidden" id="cli_end_longitude" value="<?=$d->cli_end_longitude?>">
    <input cli type="hidden" id="cli_end_padrao" value="<?=$_POST['ativar']?>">

</div>

<script>

    $(function(){
        Carregando('none');

        atualizacao_endereco<?=$md5?> = (opc, dados) => {
            $.ajax({
                url:"src/usuarios/endereco_mapa_edit.php",
                type:"POST",
                data:{
                    opc,
                    dados,
                    end_cod:'<?=$d->codigo?>',
                },
                success:function(dados){
                    $(".ms_usuario_endereco_mapa<?=$md5?>").html(dados);
                    //console.log(dados);
                }
            });
        }

        <?php
            if($d->cli_end_latitude and $d->cli_end_longitude){
        ?>
        atualizacao_endereco<?=$md5?>('coord','<?="{$d->cli_end_latitude}|{$d->cli_end_longitude}"?>');
        <?php
            }else if($d->cli_end_resumo){
        ?>
        atualizacao_endereco<?=$md5?>('end','<?="{$d->cli_end_resumo}"?>');
        <?php
            }else{
        ?>
        atualizacao_endereco<?=$md5?>();
        <?php
            }

        ?>


//*

//*/



    // $("#cli_end_estado").change(function () {
    //         var codigo = $(this).val();

    //         if (!codigo) {
    //             $("#cli_end_cidade").html(`<option value=""></option>`);
    //         } else {
    //             $.ajax({
    //                 url: "src/usuarios/cli_select.php",
    //                 type: "GET",
    //                 data: {codigo, select: "cidades"},
    //                 success: function (data) {
    //                     $("#cli_end_cidade").html(data);
    //                 }
    //             });
    //         }
    //     $("#cli_end_bairro").html(`<option value=""></option>`);
    //     });


        // $("#cli_end_cidade").change(function () {
        //     var codigo = $(this).val();

        //     if (!codigo) {
        //         $("#cli_end_bairro").html(`<option value=""></option>`);
        //     } else {
        //         $.ajax({
        //             url: "src/usuarios/cli_select.php",
        //             type: "GET",
        //             data: {codigo, select: "bairro"},
        //             success: function (data) {
        //                 $("#cli_end_bairro").html(data);
        //             }
        //         });
        //     }
        // });


        $("#cli_end_cep").mask("99999-999");

        /*
        $.validator.messages.required = "Campo Obrigátorio";

        $(".form-cadastro-endereco").validate({
            errorClass: 'text-danger',
            validClass: 'text-success',
            errorElement: 'span',
            rules: {
                cli_end_cep: {required: true},
                cli_end_estado: {required: true},
                cli_end_cidade: {required: true},
                cli_end_bairro: {required: true},
                //confirmar_senha: {required: true},
                //emp_senha: {required: true},
            },
        });
        //*/

        $("button[end_salvar]").off('click').click(function(){
            campos = [];
            valores = [];
            end_cod = $(this).attr("end_cod");
            $("input[cli], select[cli], button[cli]").each(function(){
                campos.push($(this).attr("id"));
                valores.push($(this).val());
            });
            //console.log(valores);
            //*
            $.ajax({
                    url:"src/usuarios/enderecos.php",
                    type:'POST',
                    data:{
                        campos,
                        valores,
                        end_cod,
                        acao:'salvar'
                    },
                    success:function(dados){
                        $.ajax({
                            url:"componentes/ms_popup_100.php",
                            type:'POST',
                            data:{
                                local:'src/usuarios/lista_end.php'
                            },
                            success:function(dados){
                                PageClose();
                                PageClose();
                                if(!end_cod){PageClose();}
                                $(".ms_corpo").append(dados);
                                AppComponentes('home');
                            }
                        });

                }
            });
            //*/

        });

    })
</script>