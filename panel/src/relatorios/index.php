<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");


    if($_POST['acao'] == 'busca'){
        $_SESSION['vendas_data_inicial'] = $_POST['data_inicial'];
        $_SESSION['vendas_data_final'] = $_POST['data_final'];
    }

    if($_SESSION['vendas_data_inicial'] and $_SESSION['vendas_data_final']){

        $where = " and a.data_finalizacao between '{$_SESSION['vendas_data_inicial']} 00:00:00' and '{$_SESSION['vendas_data_final']} 23:59:59' ";

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
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>VENDA</th>
                        <th>TIPO</th>
                        <th>MESA</th>
                        <th>CLIENTE</th>
                        <th>ATENDENTE</th>
                        <th>VALOR</th>
                        <th>TAXA</th>
                        <th>DESCONTO</th>
                        <th>ENTREGA</th>
                        <th>CUPOM</th>
                        <th>PAGAMENTO</th>
                        <th>CAIXA</th>
                        <th>NOTA</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $query = "select 
                    a.*,
                    b.nome as nome_cliente,
                    c.nome as nome_atendente,
                    LPAD(d.mesa,3,'0') as nome_mesa
                from 
                    vendas a
                    left join clientes b on a.cliente = b.codigo
                    left join atendentes c on a.atendente = c.codigo
                    left join mesas d on a.mesa = d.codigo
                where a.situacao = 'pago' and a.deletado != '1' {$where}";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        $q = "select *, DISTINCT(forma_pagamento) as forma_pagamento from vendas_pagamento where venda = '{$d->codigo}' and deletado != '1'";
        $r = mysqli_query($con, $q);
        $pagamentos = [];
        while($p = mysqli_fetch_object($r)){
            $pagamentos[] = $p->forma_pagamento;
        }
        if($pagamentos) $pagamentos = implode('<br>',$pagamentos);
?>
                    <tr>
                        <td><?=$d->codigo?></td>
                        <td><?=$d->app?></td>
                        <td><?=$d->nome_mesa?></td>
                        <td><?=$d->nome_cliente?></td>
                        <td><?=$d->nome_atendente?></td>
                        <td><?=$d->valor?></td>
                        <td><?=$d->acrescimo?></td>
                        <td><?=$d->desconto?></td>
                        <td><?=$d->taxa?></td>
                        <td><?=$d->cupom_valor?></td>
                        <td><?=$pagamentos?></td>
                        <td><?=$d->caixa?></td>
                        <td><?=$d->nf_numero?></td>
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