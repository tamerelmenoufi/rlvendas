<?php include("../lib/includes.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUÇÃO</title>
    <?php include("../lib/header.php"); ?>
</head>
<body id="page-top">

<div id="body"></div>

<?php include("../lib/footer.php"); ?>

<script>
    $(function () {

        <?php
            foreach($_GET as $ind => $val){
                $opc = $ind;
            }
        ?>

        $.ajax({
            url: "<?=$opc?>/index.php",
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
                url:"<?=$opc?>/index.php",
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
                            url:"<?=$opc?>/index.php",
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




    });













    // Socket Variable declaration
    var mySocket;
    const socketMessageListener = (event) => {

        const dados = JSON.parse(event.data);
        if(dados.type === 'chat'){
            //output.append('Outro: ' + dados.text, document.createElement('br'));
            console.log('Entrou na função');
            console.log(dados.text);
            $.ajax({
                url:"<?=$opc?>/add.php",
                type:"POST",
                data:{
                    cod:dados.text,
                },
                success:function(dados){
                    console.log(dados);
                    $("table[painel]").append(dados);
                },
                error:function(){
                    console.log('algo deu errado!');
                }
            });

            // nova_linha = '<tr>'+
            //                 '<td>'+
            //                 '    <div class="form-group form-check">'+
            //                 '        <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">'+
            //                 '    </div>'+
            //                 '</td>'+
            //                 '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">XXX <?=$d->mesa?></label></td>'+
            //                 '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><b><?=$d->quantidade?></b></label></td>'+
            //                 '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">'+
            //                 '    <?=$pedido->categoria->descricao?>'+
            //                 '    - <?=$pedido->medida->descricao?> (<?=$sabores?>)'+
            //                 '    <p class="card-text" style="color:red;">'+
            //                 '    <?= $d->produto_descricao?></p>'+
            //                 '</label></td>'+
            //                 '<td><button concluir cod="<?=$d->codigo?>" class="btn btn-primary btn-sm">Concluir</button></td>'+
            //                 '</tr>';

        }


    };

    // Open
    const socketOpenListener = (event) => {
        console.log('Connected');

        $.ajax({
            url: "<?=$opc?>/index.php",
            success: function (dados) {
                $("#body").html(dados);
            },
            error: function () {
                $.alert('Ocorreu um erro!');
            }
        });


    };

    // Closed
    const socketCloseListener = (event) => {
    if (mySocket) {
        console.error('Disconnected.');
    }
    mySocket = new WebSocket('wss://websocket.yobom.com.br');
    mySocket.addEventListener('open', socketOpenListener);
    mySocket.addEventListener('message', socketMessageListener);
    mySocket.addEventListener('close', socketCloseListener);
    };
    socketCloseListener();




</script>
</body>
</html>