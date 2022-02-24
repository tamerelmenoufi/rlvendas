<?php
    include("../../lib/includes.php");

    if($_POST['cliente']){
        $telefone = '('.substr($_POST['cliente'],0,2).') '.substr($_POST['cliente'],2,1).' '.substr($_POST['cliente'],3,4).'-'.substr($_POST['cliente'],7,4);
        $query = "select * from clientes where telefone = '{$telefone}'";
        $result = mysqli_query($con, $query);
        $c = mysqli_fetch_object($result);
        if($c->codigo){
            $_SESSION['ConfCliente'] = $c->codigo;
            echo json_encode([
                'status' => 'sucesso',
                'cliente' => $c->codigo,
            ]);

        }else{
            mysqli_query($con, "insert into clientes set telefone = '{$telefone}'");
            $codigo = mysqli_insert_id($con);
            echo json_encode([
                'status' => 'sucesso',
                'cliente' => $codigo,
            ]);
        }
        exit();
    }

?>
<style>
    #OpcMesa{
        text-align:center;
        font-size:40px;
        font-weight:bold;
    }
</style>
<div class="col-md-12">
    <div class="row">
        <div class="col">
            <center><h2>INFORME SEU TELEFONE/WHATSAPP</h2></center>
            <div class="form-control form-control-lg" id="OpcCliente"></div>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col">
        <?php
        for($i=1;$i<=9;$i++){
        ?>
            <div style="width:<?=(100/11)?>%; float:left; padding-right:5px;">
                <button type="button" class="btn btn-outline-dark btn-lg btn-block tecla"><?=$i?></button>
            </div>
        <?php
        }
        ?>
            <div style="width:<?=(100/11)?>%; float:left; padding-right:5px;">
                <button type="button" class="btn btn-outline-dark btn-lg btn-block tecla">0</button>
            </div>
            <div style="width:<?=(100/11)?>%; float:left;">
                <button type="button" class="btn btn-dark btn-lg btn-block apaga">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </div>

    </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <div class="col">
            <button class="btn btn-success btn-block btn-lg" AcessarCliente>ACESSAR</button>
        </div>
        <div class="col">
            <button class="btn btn-info btn-block btn-lg" LimparCliente>LIMPAR</button>
        </div>
        <div class="col">
            <button class="btn btn-danger btn-block btn-lg" CancelarCliente>CANCELAR</button>
        </div>
    </div>
</div>
<script>
    $(function(){

        //$("#OpcCliente").masck("(99) 9 9999-9999");

        $(".tecla").click(function(){
            tecla = $(this).text();
            cliente = $("#OpcCliente").text();
            $("#OpcCliente").text(cliente+tecla);
        });

        $(".apaga").click(function(){
            cliente = $("#OpcCliente").text();
            cliente = cliente.substring(0, cliente.length - 1);
            $("#OpcCliente").text(cliente);
        });

        $("button[LimparCliente]").click(function(){
            $("#OpcCliente").text('');
        });

        $("button[CancelarCliente]").click(function(){
            JanelaDefineCliente.close();
            $.ajax({
                url:"home/index.php",
                success:function(dados){
                    $("#body").html(dados);
                }
            });
        });

        $("button[AcessarCliente]").click(function(){
            cliente = $("#OpcCliente").text();

            $.ajax({
                url:"home/definir_cliente.php",
                type:"POST",
                data:{
                    cliente,
                },
                success:function(dados){
                    let retorno = JSON.parse(dados);
                    if(retorno.status == 'sucesso'){
                        window.localStorage.setItem('ConfCliente', retorno.cliente);
                        JanelaDefineCliente.close();
                        $.ajax({
                            url:"home/index.php",
                            success:function(dados){
                                $("#body").html(dados);
                            }
                        });
                    }
                },
                error:function(){

                }
            });


        });

    })
</script>