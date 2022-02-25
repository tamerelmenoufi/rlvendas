<?php
    include("../../lib/includes/includes.php");

    if($_POST['acao']){
    $campos = [];
    for($i=0; count($_POST['campos']); $i++ ){

            $campos[] = $_POST['campos'][$i]." = '".utf8_decode($_POST['valores'][$i])."'";

    }
    if($_POST['cliente']){
        $query = "update cli_clientes set ".implode(", ", $campos). " where codigo = '{$_POST['clicnte']}'";
        mysql_query($query);
        echo $_POST['cliente'];
        $_SESSION['ms_cli_codigo'] = $_POST['cliente'];
    }else{
        $query = "insert into cli_clientes set ".implode(", ", $campos);
        mysql_query($query);
        echo $novo_codigo = mysql_insert_id();
        $_SESSION['ms_cli_codigo'] = $novo_codigo;
    }



    exit();
    }

    $query = "select * from cli_clientes where codigo = '{$_SESSION['ms_cli_codigo']}'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);

?>
<style>
    .ms_usuario_perfil_titulo_topo{
        position:fixed;
        left:0;
        top:0;
        width:100%;
        height:65px;
        background:#fff;
        text-align:center;
        color:#777;
        font-size:18px;
        font-weight:bold;
        z-index:10;
        padding:15px;
    }

    .ms_usuario_perfil_titulo{
        position:relative;
        width:100%;
        text-align:left;
        color:#194B38;
        font-size:22px;
        font-weight:bold;
        padding:15px;
    }

    .ms_usuario_perfil_titulo_form{
        position:relative;
        width:100%;
        border:solid 0px red;
        margin-bottom:15px;
    }
    .ms_usuario_perfil_titulo_form_rotulo{
        position:relative;
        margin-bottom:0px;
        margin-left:15px;
        color:#ccc;
        font-size:12px;
    }

    .ms_usuario_perfil_titulo_form_campo{
        position:relative;
        width:100%;
        padding:0px 10px 10px 10px;
        height:50px;
    }

    .ms_usuario_perfil_titulo_form_campo input{
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

    .ms_usuario_perfil_titulo_form_campo svg{
        position:absolute;
        left:20px;
        top:15px;
        color:#777;
        font-size:20px;
        z-index:1;
    }


</style>

<div class="ms_usuario_perfil_titulo_topo">Perfil</div>
<?php
$Campos = array(
                'cli_nome' => array('Nome Completo','user'),
                'cli_data_nascimento' => array('Data de Nascimento','calendar-day'),
                'cli_sexo' => array('Sexo','restroom'),
                'cli_celular' => array('Celular','mobile-alt'),
                'cli_email' => array('E-mail','at'),
                /*'cli_estado' => array('Estado','map-pin'),
                'cli_cidade' => array('Cidade','city'),
                'cli_zona' => array('Zona','compass'),
                'cli_cep' => array('CEP','street-view'),
                'cli_bairro' => array('Bairro','map-marked-alt'),
                'cli_rua' => array('Rua','road'),
                'cli_complemento' => array('Complemento','street-view'),
                'cli_ponto_referencia' => array('Ponto de Reerência','map-marker-alt'),
                'cli_latitude' => array('Latitude','user-solid'),
                'cli_longitude' => array('Longitude','user-solid'),
                'cli_situacao' => array('Situação','user-solid'),*/

        );
?>

<h3 class="ms_usuario_perfil_titulo">Cadastro</h3>
<?php
//foreach($Campos as $campo => $vetor){
?>
<div class="ms_usuario_perfil_titulo_form">
    <div class="ms_usuario_perfil_titulo_form_rotulo"><?=$vetor[0]?></div>
    <div class="ms_usuario_perfil_titulo_form_campo">
        <input cli type="text" id="<?=$campo?>" value="<?=$d->$campo?>" />
        <span><i class="fas fa-<?=$vetor[1]?>"></i></span>
    </div>
</div>
<?php
//}
?>
<div class="w3-padding">
    <button cli_salvar cliente="<?=$d->codigo?>" class="btn btn-success btn-block">SALVAR</button>
</div>
<script>
    $(function(){

        $("button[cli_salvar]").click(function(){
            campos = [];
            valores = [];
            cliente = $(this).attr("clliente");
            $("input[cli]").each(function(){
                campos.push($(this).attr("id"));
                valores.push($(this).val());

                $.ajax({
                    url:"",
                    type:'POST',
                    data:{
                        campos,
                        valores,
                        cliente,
                        acao:'1'
                    },
                    success:function(retorno){
                        window.localStorage.setItem('ms_cli_codigo', retorno);
                    }
                });

            });


        });

    })
</script>