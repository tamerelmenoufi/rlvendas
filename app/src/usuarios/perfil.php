<?php
    include("../../../../lib/includes.php");

    if($_POST['acao']){
    $campos = [];

    $CamposDatas = ['cli_data_nascimento'];

    for($i=0; $i < count($_POST['campos']); $i++ ){
        if(in_array($_POST['campos'][$i],$CamposDatas)){
            $campos[] = $_POST['campos'][$i]." = '".dataMysql($_POST['valores'][$i])."'";
        }else{
            $campos[] = $_POST['campos'][$i]." = '".utf8_decode($_POST['valores'][$i])."'";
        }


    }
    if($_POST['cliente']){
        $query = "update clientes set ".implode(", ", $campos). " where codigo = '{$_POST['cliente']}'";
        mysql_query($query);
        echo $_POST['cliente'];
        $_SESSION['ms_cli_codigo'] = $_POST['cliente'];
    }else{

        $query = "insert into clientes set ".implode(", ", $campos);
        mysql_query($query);
        echo $novo_codigo = mysql_insert_id();
        $_SESSION['ms_cli_codigo'] = $novo_codigo;
    }

    exit();
    }

    $select = "select * from clientes where codigo = '{$_SESSION['ms_cli_codigo']}'";
    $result = mysql_query($select);

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
        text-align:center;
        color:#194B38;
        font-size:22px;
        font-weight:bold;
        padding:2px;
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
        background-color:#EBF4F1;
        background-position:left 15px center;
        background-size:20px;
        background-repeat:no-repeat;
        color:#777;
        border-radius:10px;
        padding-left:45px;
        padding-right:5px;
        font-size:16px;
        border:0;
    }
    .ms_usuario_perfil_titulo_form_campo select{
        position:relative;
        width:100%;
        height:50px;
        background-color:#ebf4f1;
        background-position:left 15px center;
        background-size:20px;
        background-repeat:no-repeat;
        color:#777;
        border-radius:10px;
        padding-left:90px;
        padding-right:15;
        font-size:16px;
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
   .ms_usuario_perfil_mensagem{

        color:#777;
        font-size:14px;
        text-align:center;
        font-style:italic;
    }

    select {
        width: 140px;
        height: 35px;
        padding: 4px;
        border-radius: 4px;

        background: #eee;
        border: none;
        outline: none;
        display: inline-block;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        cursor: pointer;
      }
      label {
        position: relative;
      }
      label:after {
        content: '<>';
        font: 11px "Consolas", monospace;
        color: #666;
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        transform: rotate(90deg);
        right: 8px;
        top: 2px;
        padding: 0 0 2px;
        border-bottom: 1px solid #ddd;
        position: absolute;
        pointer-events: none;
      }
      label:before {
        content: '';
        right: 6px;
        top: 0px;
        width: 20px;
        height: 20px;
        background: #eee;
        position: absolute;
        pointer-events: none;
        display: block;
      }

</style>

<div id="cadastro_perfil">

<div class="ms_usuario_perfil_titulo_topo">Perfil</div>
<?php
$Campos = array(
                'cli_nome' => array('Nome Completo','user', 'text'),
                'cli_data_nascimento' => array('Data de Nascimento','calendar-day', 'date'),
                'cli_sexo' => array('Sexo','restroom', 'text'),
                'cli_celular' => array('Celular','mobile-alt', 'number'),
                'cli_email' => array('E-mail','at', 'text'),
                'cli_estado' => array('Estado','map-pin', 'text'),
                'cli_cidade' => array('Cidade','city', 'text'),
                'cli_zona' => array('Zona','compass', 'text'),
                'cli_cep' => array('CEP','street-view', 'text'),
                // 'cli_bairro' => array('Bairro','map-marked-alt'),
                // 'cli_rua' => array('Rua','road'),
                // 'cli_complemento' => array('Complemento','street-view'),
                // 'cli_ponto_referencia' => array('Ponto de Reerência','map-marker-alt'),s
                /*'cli_latitude' => array('Latitude','user-solid'),
                'cli_longitude' => array('Longitude','user-solid'),
                'cli_situacao' => array('Situação','user-solid'),*/

        );
?>

<h3 class="ms_usuario_perfil_titulo">Seu Cadastro</h3>
<p class="ms_usuario_perfil_mensagem">"Preencha seus dados corretamente!"<p/>

<div class="ms_usuario_perfil_titulo_form">
<div class="ms_usuario_perfil_fundo">

    <div class="ms_usuario_perfil_titulo_form_rotulo">Nome Completo</div>
    <div class="ms_usuario_perfil_titulo_form_campo">
        <input cli type="text" id="cli_nome" value="<?=$d->cli_nome?>" />
        <span ><i  class="fas fa-user"></i></span>
    </div>

    <div class="ms_usuario_perfil_titulo_form_rotulo">Data de Nascimento</div>
    <div class="ms_usuario_perfil_titulo_form_campo">
        <input  cli type="text" id="cli_data_nascimento" value="<?=dataBr($d->cli_data_nascimento)?>" />
        <span><i class="fas fa-calendar-day"></i></span>
    </div>

    <div class="ms_usuario_perfil_titulo_form_rotulo">Sexo</div>
     <div class="ms_usuario_perfil_titulo_form_campo">
    <select cli  type="text" id="cli_sexo">
    <option value="f" <?=(($d->cli_sexo =='f')?'selected':false)?>>Femenino</option>
    <option value="m" <?=(($d->cli_sexo =='m')?'selected':false)?>>Masculino</option>
    </select>
    <span><i class="fas fa-restroom"></i></span>
    </div>


    <div class="ms_usuario_perfil_titulo_form_rotulo">Celular</div>
     <div class="ms_usuario_perfil_titulo_form_campo">
    <input type="text" readonly value="<?=$d->cli_celular?>" />
    <span><i class="fas fa-mobile-alt"></i></span>
    </div>


    <div class="ms_usuario_perfil_titulo_form_rotulo">E-mail</div>
     <div class="ms_usuario_perfil_titulo_form_campo">
    <input cli type="text" id="cli_email" value="<?=$d->cli_email?>" />
    <span><i class="fas fa-at"></i></span>
    </div>

</div>
</div>

<div class="w3-padding">
    <button cli_salvar cliente="<?=$d->codigo?>" class="btn btn-success btn-block">SALVAR</button>
</div>
</div>


<script>
    $(function(){

    $("#cli_celular").mask("(99) 99999-9999"); // Mascara de Celular
    $("#cli_data_nascimento").mask("99/99/9999"); // Mascara de data


        $("#cli_estado").change(function () {
        var codigo = $(this).val();

        if (!codigo) {
            $("#cli_cidade").html(`<option value=""></option>`);
        } else {
            $.ajax({
                url: "src/usuarios/cli_select.php",
                type: "GET",
                data: {codigo, select: "cli_cidades"},
                success: function (data) {
                    //console.log(data);
                    $("#cli_cidade").html(data);
                }
            });
        }
        });

        $("button[cli_salvar]").off('click').click(function(){
            fechar = $(this).parent("div").parent("div").parent("div").attr("class");
            //alert(fechar);
            campos = [];
            valores = [];
            cliente = $(this).attr("cliente");
            $("input[cli], select[cli]").each(function(){
                campos.push($(this).attr("id"));
                valores.push($(this).val());
            });
            console.log(valores);
            $.ajax({
                    url:"src/usuarios/perfil.php",
                    type:'POST',
                    data:{
                        campos,
                        valores,
                        cliente,
                        acao:'1'
                    },
                    success:function(retorno){
                        window.localStorage.setItem('ms_cli_codigo', retorno);
                          //$("ms_popup_100<?=$md5?>").PageClose();
                          PageClose();
                    }
            });

            //*/

        });

    })
</script>