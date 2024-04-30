<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");


    if($_POST['acao'] == 'busca'){
        $_SESSION['vendas_data_inicial'] = $_POST['data_inicial'];
        $_SESSION['vendas_data_final'] = $_POST['data_final'];
    }

    if($_SESSION['vendas_data_inicial'] and $_SESSION['vendas_data_final']){

        $where = " and data_finalizacao between '{$_SESSION['vendas_data_inicial']}' and '{$_SESSION['vendas_data_final']}' ";

    }


?>

<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <h4>Relatório de Vendas</h4>
            <div class="input-group">
                <span class="input-group-text">Em</span>
                <input id="data_inicial" value="<?=$_SESSION['vendas_data_inicial']?>" type="date" class="form-control" >
                <span class="input-group-text">até</span>
                <input id="data_final" value="<?=$_SESSION['vendas_data_final']?>" type="date" class="form-control" >
                <button buscar class="btn btn-outline-secondary" type="button" id="button-addon1">Listar</button>
            </div>
        </div>
    </div>
</div>
<?php
    if($_SESSION['vendas_data_inicial'] and $_SESSION['vendas_data_final']){
?>
<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <table>
                <thead>
                    <tr>
                        <th>VENDA</th>
                        <th>TIPO</th>
                        <th>PAGAMENTO</th>
                        <th>VALOR</th>
                        <th>TAXA</th>
                        <th>ENTREGA</th>
                        <th>DESCONTO</th>
                        <th>CUPOM</th>
                        <th>ATENDENTE</th>
                        <th>MESA</th>
                        <th>CLIENTE</th>
                        <th>NOTA</th>
                        <th>CAIXA</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $query = "select * from vendas where 1 {$where}";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
                    <tr>
                        <td><?=$d->codigo?></td>
                        <td><?=$d->tipo?></td>
                        <td><?=$d->pagamentos?></td>
                        <td><?=$d->valor?></td>
                        <td><?=$d->acrescimo?></td>
                        <td><?=$d->taxa?></td>
                        <td><?=$d->desconto?></td>
                        <td><?=$d->cupom_valor?></td>
                        <td><?=$d->atendente?></td>
                        <td><?=$d->mesa?></td>
                        <td><?=$d->cliente?></td>
                        <td><?=$d->nf_numero?></td>
                        <td><?=$d->caixa?></td>
                    </tr>                    
<?php
    }
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
    }
?>
<script>
    $(function(){
        Carregando('none')


        $("button[buscar]").click(function(){
            data_inicial = $("#data_inicial").val()
            data_final = $("#data_final").val()
            if(data_inicial && data_final){

                $.ajax({
                    url:"src/relatorios/index.php",
                    data:{
                        data_inicial,
                        data_final,
                        acao:'busca'
                    },
                    type:"POST",
                    success:function(dados){
                        $("#paginaHome").html(dados);
                    }
                });

            }else{

                $.alert({
                    title:"Erro Busca",
                    content:"Informe o intervalo de datas para a busca",
                    type:"red"
                })
                return false;

            }
        })
    })
</script>