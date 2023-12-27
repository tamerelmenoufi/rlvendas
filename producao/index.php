<?php
    include("../lib/includes.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUÇÃO</title>
    <?php include("../lib/header.php"); ?>
    <style>
        td{
            padding:2px !important;
        }
    </style>
</head>
<body id="page-top">

<div id="body"></div>

<?php include("../lib/footer.php"); ?>

<script>
    $(function () {

        logado = window.localStorage.getItem('Logado');

        if(!logado){
            $.dialog({
                content:"url:login.php",
                title:"Chave de acesso",
                columnClass:"col-md-4",
                type:"blue"
            })

            return false;
        }

        <?php
            foreach($_GET as $ind => $val){
                $opc = $ind;
            }
        ?>

        $.ajax({
            url: "<?=$opc?>/index.php?<?=substr($md5, 0, 12)?>",
            success: function (dados) {
                $("#body").html(dados);
            },
            error: function () {
                $.alert('Ocorreu um erro!');
            }
        });

        //Configurações globais

        //Jconfirm
        jconfirm.defaults = {
            theme: "modern",
            type: "blue",
            typeAnimated: true,
            smoothContent: true,
            draggable: false,
            animation: 'bottom',
            closeAnimation: 'top',
            animateFromElement: false,
            animationBounce: 1.5
        }



        $(document).on("click", "input[status]", function(){
            obj = $(this);
            var opc;
            var cod = obj.attr("cod");
            if(obj.prop("checked") === true){
                opc = 'i';
                msg = 'Confirma o início do preparo do produto?';
                tipo = 'green';
                returno = false;
            }else{
                opc = 'p';
                msg = 'Deseja remover da produto?';
                tipo = 'red';
                returno = true;
            }

            $.ajax({
                url:"<?=$opc?>/index.php?<?=substr($md5, 0, 12)?>",
                type:"POST",
                data:{
                    cod,
                    opc
                },
                success:function(dados){

                },
                error:function(){
                    alert('erro');
                }
            });


            // $.confirm({
            //     content:msg,
            //     title:false,
            //     type:tipo,
            //     buttons:{
            //         'SIM':function(){
            //             $.ajax({
            //                 url:"pizzas/index.php",
            //                 type:"POST",
            //                 data:{
            //                     cod,
            //                     opc
            //                 },
            //                 success:function(dados){

            //                 },
            //                 error:function(){
            //                     alert('erro');
            //                 }
            //             });
            //         },
            //         'NÃO':function(){
            //             obj.prop("checked", returno);
            //         }
            //     }
            // });


        });

        //Teste sem confirmação adaotado
        $(document).on("click", "button[concluir]", function(){

            obj = $(this);
            elimina = obj.parent("td").parent("tr");
            var cod = obj.attr("cod");
            var opc = 'c';
            var msg = 'Confirma a conclusão do produto?';
            var tipo = 'blue';

            $.confirm({
                content:msg,
                title:false,
                type:tipo,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"<?=$opc?>/index.php?<?=substr($md5, 0, 12)?>",
                            type:"POST",
                            data:{
                                cod,
                                opc
                            },
                            success:function(dados){
                                elimina.remove();
                            },
                            error:function(){
                                alert('erro');
                            }
                        });
                    },
                    'NÃO':function(){

                    }
                }
            });


        });


        $(document).on('click', "div[pedido]", function(){
            pedido = $(this).attr("pedido");
            obj = $(this);
            obj.addClass('bg-warning');
            obj.removeClass('bg-light');



        });

        renovacao = setInterval(function () {
            $.ajax({
                url: "<?=$opc?>/index.php?<?=substr($md5, 0, 12)?>",
                success: function (dados) {
                    $("#body").html(dados);
                    console.log('Entrou em contato')
                },
                error: function () {
                    $.alert('Ocorreu um erro!<br>Favor recarregar a página<br><br><button recarregar class="btn btn-primary btn-lg">Recarregar</button>');
                }
            });
        }, 5000);
        console.log(logado)

        if(!logado){
            clearInterval(renovacao);
            console.log('Sair')
        }

        $(document).on('click',"button[recarregar]", function(){
            window.location.href='./?<?=$opc?>';
        })

    });

</script>
</body>
</html>