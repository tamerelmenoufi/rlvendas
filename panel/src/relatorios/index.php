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
                        <th>#</th>
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
    $i = 1;
    while($d = mysqli_fetch_object($result)){

        ///Origem das vendas
        $origem[$d->app]['nome'] = $d->app; 
        $origem[$d->app]['vendas'] = ($origem[$d->app]['vendas'] + $d->valor);
        $origem[$d->app]['quantidade']++;

        ///Dados do garcom
        $garcom[$d->atendente]['nome'] = $d->nome_atendente; 
        $garcom[$d->atendente]['vendas'] = ($garcom[$d->atendente]['vendas'] + $d->valor);

        //taxas
        $taxas['acrescimo'] = ($taxas['acrescimo'] + $d->acrescimo);
        $taxas['desconto'] = ($taxas['desconto'] + $d->desconto);
        $taxas['entrega'] = ($taxas['entrega'] + (($d->app == 'delivery')?$d->taxa:0));
        $taxas['cupom'] = ($taxas['cupom'] + $d->cupom_valor);

        $q = "select forma_pagamento, sum(valor) as valor from vendas_pagamento where venda = '{$d->codigo}' and deletado != '1' group by forma_pagamento";
        $r = mysqli_query($con, $q);
        $pagamentos = [];
        while($p = mysqli_fetch_object($r)){
            $pagamentos[] = $p->forma_pagamento." ({$p->valor})";
            
            //Pagamentos
            $pagamento[$p->forma_pagamento]['valor'] = ($pagamento[$p->forma_pagamento]['valor'] + $p->valor);
            $pagamento[$p->forma_pagamento]['quantidade']++;
        }
        if($pagamentos) $pagamentos = implode('<br>',$pagamentos);
?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$d->codigo?></td>
                        <td><?=$d->app?></td>
                        <td><?=$d->nome_mesa?></td>
                        <td><?=$d->nome_cliente?></td>
                        <td><?=$d->nome_atendente?></td>
                        <td>R$ <?=number_format($d->valor,2,',','.')?></td>
                        <td>R$ <?=number_format($d->acrescimo,2,',','.')?></td>
                        <td>R$ <?=number_format($d->desconto,2,',','.')?></td>
                        <td>R$ <?=number_format((($d->app == 'delivery')?$d->taxa:0),2,',','.')?></td>
                        <td>R$ <?=number_format($d->cupom_valor,2,',','.')?></td>
                        <td><?=$pagamentos?></td>
                        <td><?=$d->caixa?></td>
                        <td><?=$d->nf_numero?></td>
                    </tr>                    
<?php
    $i++;
    }
?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-0">
    <div class="col-md-4">
        <div class="m-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ORIGEM</th>
                        <th>VALOR</th>
                        <th>VENDAS</th>
                        <th>TICKT MÉDIO</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($origem as $i => $val){
                ?>
                <tr>
                    <td><?=$val['nome']?></td>
                    <td><?=$val['vendas']?></td>
                    <td><?=$val['quantidade']?></td>
                    <td><?=($val['vendas']/$val['quantidade'])?></td>
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row g-0">
    <div class="col-md-4">
        <div class="m-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>FORMA DE PAGAMENTO</th>
                        <th>QUANTIDADE</th>
                        <th>VALOR</th>
                        <th>TICKT MÉDIO</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($pagamento as $i => $val){
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$val['quantidade']?></td>
                    <td><?=$val['valor']?></td>
                    <td><?=$val['valor']/$val['quantidade']?></td>
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-0">
    <div class="col-md-4">
        <div class="m-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>TIPO DE TAXAS</th>
                        <th>VALOR</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($taxas as $i => $val){
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$val?></td>
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
                Carregando()
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