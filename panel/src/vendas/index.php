<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_GET['filtro']){
        $_SESSION['busca_tipo'] = $_GET['filtro'];
    }

    if($_POST['acao'] == 'busca'){
        $_SESSION['data_inicial'] = $_POST['data_inicial'];
        $_SESSION['data_final'] = $_POST['data_final'];
        $_SESSION['busca_tipo'] = $_POST['busca_tipo'];
    }

    if($_POST['acao'] == 'limpar_filtro'){
        $_SESSION['filtro'] = [];
    }

    if ($_POST['acao'] == 'pagar') {
        $mesa = mysqli_fetch_object(mysqli_query($con, "select mesa from vendas where codigo = '{$_POST['cod']}'"));
        if(mysqli_query($con, "update vendas set situacao = 'pago' where codigo = '{$_POST['cod']}'")){
            mysqli_query($con, "UPDATE mesas set blq = '0' WHERE codigo = '{$mesa->mesa}'");
        }
        exit();
    }

    $where = false;
    if($_SESSION['data_inicial'] > 0){
        $where .= " and data_pedido between '{$_SESSION['data_inicial']} 00:00:00' and '".(($_SESSION['data_final'])?:$_SESSION['data_inicial'])." 23:59:59' ";
    }
    

    $tipo = [
        'garcom'    => " and a.app = 'garcom' ",
        'cliente'   => " and a.caixa != '0' and a.app = 'mesa' and a.situacao = 'pago'",
        'viagem'    => " and a.app = 'mesa'", // and a.mesa >= 200
        'delivery'  => " and a.app = 'delivery' and a.caixa != '0' and a.situacao = 'pago'",
    ];

    function rotulo($i, $v){
        global $con;
        $r = $v;
        if($i == 'mesa'){
            list($r) = mysqli_fetch_row(mysqli_query($con,"select mesa from mesas where codigo = '{$v}'"));
        }
        if($i == 'atendente'){
            list($r) = mysqli_fetch_row(mysqli_query($con,"select nome from atendentes where codigo = '{$v}'"));
        }
        return $r;
    }

    if($_SESSION['filtro']){
        $filtro = [];
        $filtros = [];
        foreach($_SESSION['filtro'] as $i => $v){
            if($v){
                $v1 = rotulo($i, $v);
                $filtro[] = "{$i}: {$v1}";
                $q = [
                    'pedido' => " a.codigo = '{$v}'",
                    'mesa' => " a.mesa = '{$v}'",
                    'cliente' => " c.nome like '%{$v}%'",
                    'atendente' => " a.atendente = '{$v}'",
                    'situacao' => " a.situacao = '{$v}'",
                ];
                $filtros[] = $q[$i];
            }
            }
        $filtro = implode(", ", $filtro);
        $filtros = implode(" and ", $filtros);
    }
    
?>
<style>
    .l-100{
        width:100px;
    }
</style>
<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <div class="input-group">
                <span class="input-group-text">Tipo</span>
                <select id="busca_tipo" class="form-select">
                    <option value="garcom" <?=(($_SESSION['busca_tipo'] == 'garcom')?'selected':false)?>>Atendimento pelo Garçom</option>
                    <option value="cliente" <?=(($_SESSION['busca_tipo'] == 'cliente')?'selected':false)?>>Pedido pelo Cliente (na mesa)</option>
                    <option value="viagem" <?=(($_SESSION['busca_tipo'] == 'viagem')?'selected':false)?>>Pedido para viagem</option>
                    <option value="delivery" <?=(($_SESSION['busca_tipo'] == 'delivery')?'selected':false)?>>Pedido pelo Delivery</option>
                </select>
                <span class="input-group-text">Em</span>
                <input id="data_inicial" value="<?=$_SESSION['data_inicial']?>" type="date" class="form-control" >
                <span class="input-group-text">até</span>
                <input id="data_final" value="<?=$_SESSION['data_final']?>" type="date" class="form-control" >
                <button buscar class="btn btn-outline-secondary" type="button" id="button-addon1">Listar</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <div class="input-group">
                <span class="input-group-text">Filtro</span>
                <div class="form-control"><?=$filtro?></div>
                <button 
                    filtrar 
                    class="btn btn-outline-secondary" 
                    type="button" 
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                ><i class="fa-solid fa-magnifying-glass"></i></button>
                <button limpar_filtro class="btn btn-outline-danger" type="button"><i class="fa-solid fa-eraser"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <div class="row">
<?php
    $query = "select 
                    a.*,
                    m.mesa,
                    c.nome as cliente,
                    b.nome as atendente
                from 
                    vendas a
                    left join mesas m on a.mesa = m.codigo 
                    left join clientes c on a.cliente = c.codigo 
                    left join atendentes b on a.atendente = b.codigo 
                where 
                    a.deletado != '1' 
                    {$where} ".(($filtros)?" and {$filtros}":false)."
                    {$tipo[$_SESSION['busca_tipo']]}
                order by 
                    a.codigo desc".
                ((!$_SESSION['data_inicial'])?" limit 50 ":false);
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

?>
                <div class="col-md-3 mb-3">
                    <div class="card">
                    <div class="card-header">
                        Pedido #<?=$d->codigo?>
                    </div>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>Data</span>
                                <span><?=dataBr($d->data_pedido)?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Mesa</span>
                                <span><?=$d->mesa?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Cliente</span>
                                <span><?=$d->cliente?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Atendente</span>
                                <span><?=$d->atendente?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Valor</span>
                                <div class="d-flex justify-content-between l-100"><span>R$</span><span><?=number_format($d->valor,2,',','.')?></span></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Taxa</span>
                                <div class="d-flex justify-content-between l-100"><span>R$</span><span><?=number_format($d->taxa,2,',','.')?></span></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Desconto</span>
                                <div class="d-flex justify-content-between l-100"><span>R$</span><span><?=number_format($d->desconto,2,',','.')?></span></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Cupom</span>
                                <div class="d-flex justify-content-between l-100"><span>R$</span><span><?=number_format($d->cupom_valor,2,',','.')?></span></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total</span>
                                <div class="d-flex justify-content-between l-100"><span>R$</span><span><?=number_format($d->total,2,',','.')?></span></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Situação</span>
                                <span><?=$d->situacao?></span>
                            </div>

                            <div class="d-flex justify-content-end">
                                
                                <button pedido="<?= $d->codigo ?>" class="lista btn btn-success btn-sm me-2">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                                <?php
                                if($d->situacao == 'pagar'){
                                ?>
                                <button pagar="<?= $d->codigo ?>" class="lista btn btn-primary btn-sm me-2">
                                    <i class="fa-solid fa-rectangle-list"></i>
                                </button>
                                <?php
                                }
                                ?>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a print2="<?= $d->codigo ?>" local="terminal1" class="dropdown-item">Caixa</a></li>
                                        <li><a print2="<?= $d->codigo ?>" local="terminal2" class="dropdown-item">Terminal</a></li>
                                    </ul>
                                </div>

                            </div>
                        </li>


                    </ul>
                    </div>
                </div>
<?php
    }
?>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){
        Carregando('none');

        $("button[buscar]").click(function(){

            busca_tipo = $("#busca_tipo").val();
            data_inicial = $("#data_inicial").val();
            data_final = $("#data_final").val();
            Carregando();
            $.ajax({
                url:"src/vendas/index.php",
                type:"POST",
                data:{
                    busca_tipo,
                    data_inicial,
                    data_final,
                    acao:"busca"
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })

        })

        $("button[limpar_filtro]").click(function(){

            Carregando();
            $.ajax({
                url:"src/vendas/index.php",
                type:"POST",
                data:{
                    acao:"limpar_filtro"
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })

        })


        $("button[filtrar]").click(function(){

            Carregando();
            $.ajax({
                url:"src/vendas/filtro.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        })

        $("a[print2]").click(function() {

            terminal = $(this).attr("local");
            cod = $(this).attr("print2");

            $.ajax({
                url: "src/vendas/print-2.php",
                type: "POST",
                data: {
                    cod,
                    terminal
                },
                success: function (dados) {
                    //alert('x');
                }
            });

        });

        $("button[pedido]").click(function () {

            cod = $(this).attr("pedido");

            $.dialog({
                content: "url:src/vendas/detalhes.php?cod=" + cod,
                title: false,
                columnClass: 'col-md-8 col-xs-12'
            });
        });

        $("button[pagar]").click(function () {
            obj = $(this);
            cod = obj.attr("pagar");
            $.confirm({
                title:"Confirmação de pagamento",
                content:"Confirma o pagamento da venda e aliberação da mesa?",
                buttons:{
                    sim:{
                        text:"SIM",
                        btnClass:"btn btn-danger btn-sm",
                        action:function(){
                            $.ajax({
                                url: "src/vendas/index.php",
                                type: "POST",
                                data: {
                                    cod,
                                    acao:'pagar'
                                },
                                success: function (dados) {
                                    //alert('x');
                                    // $.alert('Venda atualizada com situação <b>Pago</b>.');
                                    obj.remove();
                                }
                            });
                        }
                    },
                    nao:{
                        text:"NÃO",
                        btnClass:"btn btn-success btn-sm",
                        action:function(){
                            
                        }
                    }
                }
            })

            
        });
    })
</script>