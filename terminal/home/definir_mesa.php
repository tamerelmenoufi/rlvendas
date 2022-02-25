<?php
    include("../../lib/includes.php");
    $mesas = [];
    $query = "select * from mesas where situacao = '1' and deletado != '1'";
    $result = mysqli_query($con, $query);
    while($m = mysqli_fetch_object($result)){
        $mesas[] = $m->mesa;
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
            <center><h2>DIGITE O CÓDIGO DE SEU PEDIDO</h2></center>
            <div class="form-control form-control-lg" id="OpcMesa"></div>
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
            <button class="btn btn-success btn-block btn-lg" AcessarMesa>ACESSAR</button>
        </div>
        <div class="col">
            <button class="btn btn-info btn-block btn-lg" LimparrMesa>LIMPAR</button>
        </div>
        <div class="col">
            <button class="btn btn-danger btn-block btn-lg" CancelarMesa>CANCELAR</button>
        </div>
    </div>
</div>
<script>
    $(function(){
        $(".tecla").click(function(){
            tecla = $(this).text();
            mesa = $("#OpcMesa").text();
            $("#OpcMesa").text(mesa+tecla);
        });

        $(".apaga").click(function(){
            mesa = $("#OpcMesa").text();
            mesa = mesa.substring(0, mesa.length - 1);
            $("#OpcMesa").text(mesa);
        });

        $("button[LimparrMesa]").click(function(){
            $("#OpcMesa").text('');
        });

        $("button[CancelarMesa]").click(function(){
            JanelaDefineMesa.close();
        });

        $("button[AcessarMesa]").click(function(){
            mesa = $("#OpcMesa").text();
            m = ['<?=@implode("','",$mesas)?>'];
            if(mesa && $.inArray( mesa, m ) != -1){
                window.localStorage.setItem('ConfMesa', mesa);
                JanelaDefineMesa.close();
                
                $.ajax({
                    url:"home/index.php?mesa="+mesa,
                    success:function(dados){
                        $("#body").html(dados);
                    }
                });
            }else{
                $.alert('CÓDIGO <b>'+mesa+'</b> BLOQUEADO, EM USO OU NÃO REGISTRADO NO SISTEMA!');
            }
        });

    })
</script>