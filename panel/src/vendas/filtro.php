<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['acao'] == 'salvar'){


        $data = $_POST;
        $attr = [];

        unset($data['acao']);

        foreach ($data as $name => $value) {
            eval("\$_SESSION['filtro']['{$name}'] = '{$value}';");
        }

        exit();

    }

?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Filtro de Busca</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="pedido" name="pedido" placeholder="Nome completo" value="<?=$_SESSION['filtro']['pedido']?>">
                    <label for="pedido">Número do Pedido*</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="mesa" class="form-control" id="mesa">
                        <option value="">:: Selecione ::</option>
                        <?php
                        $q = "select * from mesas where deletado != '1' and situacao = '1'";
                        $r = mysqli_query($con, $q);
                        while($m = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$m->codigo?>" <?=(($_SESSION['filtro']['mesa'] == $m->codigo)?'selected':false)?>><?=$m->mesa?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="mesa">Mesa</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Digite o nome" value="<?=$_SESSION['filtro']['cliente']?>">
                    <label for="cliente">Nome do Cliente*</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="atendente" class="form-control" id="atendente">
                        <option value="">:: Selecione ::</option>
                        <?php
                        $q = "select * from atendentes where deletado != '1' and situacao = '1' order by nome";
                        $r = mysqli_query($con, $q);
                        while($a = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$a->codigo?>" <?=(($_SESSION['filtro']['atendente'] == $a->codigo)?'selected':false)?>><?=$a->nome?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="atendente">Atendente</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="situacao" class="form-control" id="situacao">
                        <option value="">:: Selecione ::</option>
                        <option value="producao" <?=(($_SESSION['filtro']['situacao'] == 'producao')?'selected':false)?>>Produção</option>
                        <option value="preparo" <?=(($_SESSION['filtro']['situacao'] == 'preparo')?'selected':false)?>>Preparo</option>
                        <option value="pagar" <?=(($_SESSION['filtro']['situacao'] == 'pagar')?'selected':false)?>>Pagar</option>
                        <option value="pago" <?=(($_SESSION['filtro']['situacao'] == 'pago')?'selected':false)?>>Pago</option>
                    </select>
                    <label for="email">Situação</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="display:flex; justify-content:end">
                    <button type="submit" class="btn btn-success btn-ms">Filtrar</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var campos = $(this).serializeArray();

                campos.push({name: 'acao', value: 'salvar'})

                Carregando();

                $.ajax({
                    url:"src/vendas/filtro.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                    //console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/vendas/index.php",
                                type:"POST",
                                success:function(dados){
                                    $("#paginaHome").html(dados);
                                    let myOffCanvas = document.getElementById('offcanvasDireita');
                                    let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                                    openedCanvas.hide();
                                }
                            });
                        // }
                    },
                    error:function(erro){

                        // $.alert('Ocorreu um erro!' + erro.toString());
                        //dados de teste
                    }
                });

            });

        })
    </script>