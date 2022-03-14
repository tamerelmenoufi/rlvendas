<?php
    include("../../lib/includes.php");


    if($_POST['opc']){

        $query = "update vendas_produtos set situacao = '{$_POST['opc']}' where codigo = '{$_POST['cod']}'";
        mysqli_query($con, $query);
        exit();
    }

    $tipos = ['pizzas','sanduiches'];
?>
<style>

    .pizzas{
        position:fixed;
        width:50%;
        left:0px;
        top:0px;
        bottom:0px;
        overflow:auto;
    }
    .sanduiches{
        position:fixed;
        width:50%;
        right:0px;
        top:0px;
        bottom:0px;
        overflow:auto;
    }

    /* ===== Scrollbar CSS ===== */
    /* Firefox */
    * {
        scrollbar-width: auto;
        scrollbar-color: #ccc #ffffff;
    }

    /* Chrome, Edge, and Safari */
    *::-webkit-scrollbar {
        width: 4px;
    }

    *::-webkit-scrollbar-track {
        background: #ffffff;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 2px;
        border: 0;
    }



</style>

        <?php
        foreach($tipos as $ind => $opc){
        ?>
        <div class="<?=$opc?>">
            <h4 style="position:fixed; top:0; height:40px; z-index:10; width:100%; padding-left:15px; padding-top:5px; background-color:#fff">Dados da cozenha (Produção de <?=$opc?>)</h4>
        <table <?=$opc?> class="table table-striped table-hover" style="margin-top:40px;">
        <?php
            $query = "select a.*, b.mesa as mesa from vendas_produtos a left join mesas b on a.mesa = b.codigo /*where a.situacao = 'p'*/ order by a.data asc";
            $result = mysqli_query($con, $query);

            while($d = mysqli_fetch_object($result)){

                $pedido = json_decode($d->produto_json);
                $sabores = false;
                $ListaPedido = [];
                for($i=0; $i < count($pedido->produtos); $i++){
                    $ListaPedido[] = $pedido->produtos[$i]->descricao;
                }
                if($ListaPedido) $sabores = implode(', ', $ListaPedido);

        ?>
        <!-- <div class="card bg-light mb-3">
            <div class="card-body">
                <h5 class="card-title" style="paddig:0; margin:0; font-size:14px; font-weight:bold;">
                    <span style="font-size:20px;"><?=$d->quantidade?></span> <?=$pedido->categoria->descricao?>
                    - <?=$pedido->medida->descricao?> (<?=$sabores?>)
                </h5>
                <p class="card-text" style="padding:0; margin:0; text-align:right">
                    R$ <?= number_format($d->valor_unitario, 2, ',', '.') ?>
                </p>
                <p class="card-text" style="padding-left:15px; margin:0; font-size:14px; color:red;">
                    <?= $d->produto_descricao?>
                </p>
            </div>
        </div> -->
        <tr>

            <td>
                <div class="form-group form-check">
                    <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">
                </div>
            </td>
            <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><?=$d->mesa?></label></td>
            <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><b><?=$d->quantidade?></b></label></td>
            <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">
                <?=$pedido->categoria->descricao?>
                - <?=$pedido->medida->descricao?> (<?=$sabores?>)
                <p class="card-text" style="color:red;">
                <?= $d->produto_descricao?></p>
            </label></td>
            <td><button concluir cod="<?=$d->codigo?>" class="btn btn-primary btn-sm">Concluir</button></td>
        </tr>


        <?php
            }
        ?>
        </table>

            <output></output>

        </div>
        <?php
        }
        ?>

<script>
    $(function(){
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
            $.confirm({
                content:msg,
                title:false,
                type:tipo,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"pizzas/index.php",
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
                    },
                    'NÃO':function(){
                        obj.prop("checked", returno);
                    }
                }
            });


        });
    })


    // var ws;

    // WebSocket = function(){

    //     ws = new WebSocket("wss://websocket.yobom.com.br");
    //     //const input = document.querySelector('input');
    //     output = document.querySelector('output');

    //     ws.addEventListener('open', console.log);
    //     ws.addEventListener('message', console.log);
    //     ws.addEventListener('close', function(){
    //         setTimeout(function() { WebSocket(); }, 1000);
    //     });

    //     ws.addEventListener('message', message => {
    //         const dados = JSON.parse(message.data);
    //         if(dados.type === 'chat'){
    //             output.append('Outro: ' + dados.text, document.createElement('br'));
    //             nova_linha = '<tr>'+
    //                             '<td>'+
    //                             '    <div class="form-group form-check">'+
    //                             '        <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">'+
    //                             '    </div>'+
    //                             '</td>'+
    //                             '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">XXX <?=$d->mesa?></label></td>'+
    //                             '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><b><?=$d->quantidade?></b></label></td>'+
    //                             '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">'+
    //                             '    <?=$pedido->categoria->descricao?>'+
    //                             '    - <?=$pedido->medida->descricao?> (<?=$sabores?>)'+
    //                             '    <p class="card-text" style="color:red;">'+
    //                             '    <?= $d->produto_descricao?></p>'+
    //                             '</label></td>'+
    //                             '<td><button concluir cod="<?=$d->codigo?>" class="btn btn-primary btn-sm">Concluir</button></td>'+
    //                             '</tr>';
    //             $("table[pizzas]").append(nova_linha);
    //         }
    //     })

    //     // input.addEventListener('keypress', e => {
    //     //     if(e.code === 'Enter'){
    //     //         const valor = input.value;
    //     //         output.append('Eu: ' + valor, document.createElement('br'));
    //     //         ws.send(valor);

    //     //         input.value = '';
    //     //     }
    //     // });
    // }

    // WebSocket();




    // Socket Variable declaration
var mySocket;
const socketMessageListener = (event) => {

    const dados = JSON.parse(event.data);
    if(dados.type === 'chat'){
        output.append('Outro: ' + dados.text, document.createElement('br'));
        nova_linha = '<tr>'+
                        '<td>'+
                        '    <div class="form-group form-check">'+
                        '        <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">'+
                        '    </div>'+
                        '</td>'+
                        '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">XXX <?=$d->mesa?></label></td>'+
                        '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><b><?=$d->quantidade?></b></label></td>'+
                        '<td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">'+
                        '    <?=$pedido->categoria->descricao?>'+
                        '    - <?=$pedido->medida->descricao?> (<?=$sabores?>)'+
                        '    <p class="card-text" style="color:red;">'+
                        '    <?= $d->produto_descricao?></p>'+
                        '</label></td>'+
                        '<td><button concluir cod="<?=$d->codigo?>" class="btn btn-primary btn-sm">Concluir</button></td>'+
                        '</tr>';
        $("table[pizzas]").append(nova_linha);
    }


};

// Open
const socketOpenListener = (event) => {
   console.log('Connected');

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