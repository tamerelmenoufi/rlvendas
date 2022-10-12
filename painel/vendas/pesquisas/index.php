<?php
    include("../../../lib/includes.php");
?>

    <div class="row">
        <div class="col-md-4">
            <label for="mesa">Mesa</label>
            <select id="filtrarMesa" class="form-control">
                <option value="">Todas as Mesa</option>
                <?php
                $q = "select * from mesas where situacao = '1' and deletado != '1' order by mesa";
                $r = mysqli_query($con, $q);
                while($m = mysqli_fetch_object($r)){
                ?>
                <option value="<?=$m->codigo?>" <?=(($_POST['mesa'] == $m->codigo)?'selected':false)?>><?=$m->mesa?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="data">Data do Pedido</label>
            <input id="filtrarData" value='<?=$_POST['data']?>' type="date" class="form-control" />
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="filtrarVendas" class="btn btn-success">Buscar</button>
        </div>
    </div>
    <?php
        if($_POST){
    ?>
    <div class="row">
        <div class="col">


        <div class="table-responsive">

            <table id="datatable" class="table display nowrap" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Valor</th>
                    <th>Mesa</th>
                    <th>Período</th>
                    <th>Situação</th>
                    <th class="mw-20">Lista</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $where = false;
                if($_POST['data']){
                    $where .= " and v.data_finalizacao = '{$_POST['data']}' ";
                }
                if($_POST['mesa']){
                    $where .= " and v.mesa = '{$_POST['mesa']}' ";
                }

                $query = "SELECT v.*, c.telefone, m.mesa AS mesa_descricao, c.nome AS cliente_nome FROM vendas v "
                    . "INNER JOIN clientes c ON c.codigo = v.cliente "
                    . "INNER JOIN mesas m ON m.codigo = v.mesa "
                    . "LEFT JOIN atendentes a ON a.codigo = v.atendente "
                    . "WHERE v.situacao='pago' {$where} order by v.codigo desc limit 100";

                // echo $query;

                $result = mysqli_query($con, $query);


                while ($d = mysqli_fetch_object($result)):
                    ?>
                    <tr id="linha-<?= $d->codigo; ?>">
                        <td><?= $d->cliente_nome ?: $d->telefone; ?></td>
                        <td>
                            <?= number_format($d->total, 2, ',', '.'); ?>
                        </td>
                        <td><?= $d->mesa_descricao; ?></td>
                        <td>
                            De: <?= formata_datahora($d->data_pedido, DATA_HM); ?><br>
                            Até: <?= formata_datahora($d->data_finalizacao, DATA_HM); ?>
                        </td>
                        <td><?= getSituacaoOptions($d->situacao, $d->codigo) ?></td>
                        <td>
                            <button lista="<?= $d->codigo ?>" class="lista btn btn-primary">
                                <i class="fa-solid fa-rectangle-list"></i>
                            </button>

                            <div class="btn-group" role="group">

                                <button id="btnGroupDrop1" class="btn btn-success dropdown-toggle btn-group-sm"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-print"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a print2="<?= $d->codigo ?>" local="terminal1" class="dropdown-item"
                                    href="#">Caixa</a>
                                    <a print2="<?= $d->codigo ?>" local="terminal2" class="dropdown-item" href="#">Terminais</a>
                                </div>
                            </div>


                        </td>

                    </tr>
                <?php endwhile; ?>
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
        $(".filtrarVendas").click(function(){
            mesa = $(".filtrarMesa").val();
            data = $(".filtrarData").val();
            if(!mesa && !data) return false;
            $.ajax({
                url:"vendas/pesquisas/index.php",
                type:"POST",
                data:{
                    mesa,
                    data
                },
                success:function(dados){
                    $('#palco').html(dados);
                }
            });
        });




        $("#datatable").DataTable({
            responsive: true
        });


        $("#vendas").on("click", ".lista", function () {

            cod = $(this).attr("lista");

            $.dialog({
                content: "url:vendas/detalhes.php?cod=" + cod,
                title: false,
                columnClass: 'col-md-8 col-xs-12'
            });
        });

        $("#vendas").on("click", "button[print]", function () {

            cod = $(this).attr("print");
            $.ajax({
                url: "vendas/print.php",
                type: "POST",
                data: {
                    cod,
                },
                success: function (dados) {
                    // $.alert(dados);

                    // acao = '<iframe src="http://localhost/print/print.php?pdf='+dados+'" border="0" width="0" height="0" style="opacity:0"></iframe>';
                    window.open('http://html2img.mohatron.com/pdf/' + dados);
                }
            });

        });

        $("#vendas").on("click", "button[print2]", function (e) {
            e.preventDefault();

            terminal = window.localStorage.getItem('AppTerminal');
            cod = $(this).attr("print2");

            $.ajax({
                url: "vendas/print-2.php",
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

        $("#vendas").on("click", "a[print2]", function (e) {
            e.preventDefault();

            terminal = $(this).attr("local");
            cod = $(this).attr("print2");

            $.ajax({
                url: "vendas/print-2.php",
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


    });


</script>