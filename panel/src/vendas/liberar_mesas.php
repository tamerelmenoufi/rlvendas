<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_GET['liberar_mesas']){
        $_SESSION['busca_tipo'] = 'garcom';
        $_SESSION['data_inicial'] = false;
        $_SESSION['data_final'] = false;
        $_SESSION['filtro'] = false;
        $_SESSION['filtro']['situacao'] = 'pagar';
        
    }

    
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

    if($_SESSION['filtro']['situacao'] == 'pagar') $where .= " and a.mesa != '' ";
    

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
            <h1>Liberar Mersas</h1>
            <div class="row">
<?php
    $query = "select * from mesas where situacao = '1' and deletado != '1' and CONVERT(mesa, UNSIGNED INTEGER) < 200 order by CONVERT(mesa, UNSIGNED INTEGER) asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

?>
                <div class="col-2">
                <div class="alert alert-secondary" role="alert">
                    <h1 class="w-100 text-center"><?=str_pad($d->mesa, 3, "0", STR_PAD_LEFT)?></h1>
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