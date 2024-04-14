<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    $ConfCategoria = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        if ($data['icon-base']) {

            $md52 = md5($md5.$data['icon-name']);

            if(!is_dir("../volume")) mkdir("../volume");
            if(!is_dir("../volume/produtos")) mkdir("../volume/produtos");

            list($x, $icon) = explode(';base64,', $data['icon-base']);
            $icon = base64_decode($icon);
            $pos = strripos($data['icon-name'], '.');
            $ext = substr($data['icon-name'], $pos, strlen($data['icon-name']));
 
            $atual = $data['icon-atual'];
 
            unset($data['icon-base']);
            unset($data['icon-type']);
            unset($data['icon-name']);
            unset($data['icon-atual']);
    
            if (file_put_contents("../volume/produtos/{$md52}{$ext}", $icon)) {
                $attr[] = "icon = '{$md52}{$ext}'";
                if ($atual) {
                    unlink("../volume/produtos/{$atual}");
                }
            }
    
        }

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update produtos set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into produtos set {$attr}";
            sisLog($query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'status' => true,
            'codigo' => $query
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from produtos where codigo = '{$_POST['cod']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);

    $medidas = explode(",", $d->medidas);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Cadastro de Produto</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="produto" name="produto" placeholder="Nome do Produto" value="<?=$d->produto?>">
                    <label for="produto">Produto*</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea name="descricao" id="descricao" class="form-control" placeholder="Nome do Produto" style="height:150px;"><?=$d->descricao?></textarea>
                    <label for="descricao">Descrição*</label>
                </div>



                <div class="card mb-3">
                    <div class="card-header">
                        Valores
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">


                            <div class="form-group">
                        <?php

                            if($ConfCategoria->medidas){
                                $query1 = "SELECT * FROM categoria_medidas "
                                    . "WHERE deletado != '1' AND codigo IN({$ConfCategoria->medidas}) "
                                    . "ORDER BY ordem, medida";
                                $result1 = mysqli_query($con, $query1);

                                $detalhes = json_decode($d->detalhes, true);

                                while ($dados = mysqli_fetch_object($result1)):
                                    ?>
                                    <div class="row cor mb-2">
                                        <div class="col-5">
                                            <?= $dados->medida; ?>
                                        </div>
                                        <div class="col-5">

                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input
                                                    valores
                                                    opc="<?= $dados->codigo ?>"
                                                    value="<?= $detalhes[$dados->codigo]['valor']; ?>"
                                                    type="number"
                                                    class="form-control"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <input
                                                chave
                                                opc="<?= $dados->codigo ?>"
                                                value="<?= (($detalhes[$dados->codigo]['quantidade']) ?: '0') ?>"
                                                type="checkbox" <?= (($detalhes[$dados->codigo]['quantidade']) ? 'checked' : false) ?>
                                                data-toggle="toggle"
                                            >
                                        </div>
                                    </div>
                            <?php 
                                    endwhile;
                            }
                            ?>
                            </div>
                         </li>
                    </ul>
                </div>

                <label for="file_<?= $md5 ?>">Incluir / Editar - Imagem da Categoria *</label>
                <?php
                if(is_file("../volume/produtos/{$d->icon}")){
                ?>
                <center><img src="src/volume/produtos/<?=$d->icon?>" class="mb-3 img-fluid" /></center>
                <?php
                }
                ?>
                <div class="encode_icon"></div>
                <div class="input-group mb-3">
                    <input 
                        type="file" 
                        class="form-control" 
                        id="file_<?= $md5 ?>" 
                        target="encode_icon"
                        accept="image/*"
                        w="270"
                        h="240"
                    >
                    <label class="input-group-text" for="file_<?= $md5 ?>">Selecionar</label>
                    <input
                        type="hidden"
                        id="encode_icon"
                        nome=""
                        tipo=""
                        value=""
                        atual="<?= $d->icon; ?>"
                    />
                </div>

                <div class="form-floating mb-3">
                    <select name="situacao" class="form-control" id="situacao">
                        <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
                        <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                    </select>
                    <label for="email">Situação</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="display:flex; justify-content:end">
                    <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                    <input type="hidden" id="codigo" value="<?=$_POST['cod']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("input[chave]").bootstrapToggle();

            $('input[chave]').change(function () {
                opc = $(this).attr("opc");
                if ($(this).prop('checked') === true) {
                    $(this).val(opc);
                } else {
                    $(this).val('0');
                }
            })

            if (window.File && window.FileList && window.FileReader) {

                $('input[type="file"]').change(function () {
                    var mW = $(this).attr("w")
                    var mH = $(this).attr("h")
                    var tgt = $(this).attr("target")
                    if ($(this).val()) {
                        var files = $(this).prop("files");
                        for (var i = 0; i < files.length; i++) {
                            (function (file) {
                                var fileReader = new FileReader();
                                fileReader.onload = function (f) {

                                    var image = new Image();
                                    image.src = fileReader.result;
                                    image.onload = function() {

                                        var Base64 = f.target.result;
                                        var type = file.type;
                                        var name = file.name;
                                        var w = image.width;
                                        var h = image.height;

                                        // if(mW != w || mH != h){

                                        //     $.alert(`Erro de compatibilidade da dimensão da imagem.<br>Favor seguir o padrão de medidas:<br><b>${mW}px Largura X ${mH}px Altura</b>`)
                                        //     $(`#${tgt}`).val('');
                                        //     $(`#${tgt}`).attr("nome", '');
                                        //     $(`#${tgt}`).attr("tipo", '');
                                        //     $(`#${tgt}`).attr("w", '');
                                        //     $(`#${tgt}`).attr("h", '');                                        
                                        //     return false;

                                        // }else{

                                            $(`#${tgt}`).val(Base64);
                                            $(`#${tgt}`).attr("nome", name);
                                            $(`#${tgt}`).attr("tipo", type);
                                            $(`#${tgt}`).attr("w", w);
                                            $(`#${tgt}`).attr("h", h);

                                            $(`.${tgt} center`).remove();
                                            $(`.${tgt}`).prepend(`<center><img src="${Base64}" class="mb-3 img-fluid" /></center>`);

                                        // }

                                    };

                                };
                                fileReader.readAsDataURL(file);
                            })(files[i]);
                        }
                    }
                });
            } else {
                alert('Nao suporta HTML5');
            }

            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }


                campos.push({name: 'acao', value: 'salvar'})

                icon_name = $("#encode_icon").attr("nome");
                icon_type = $("#encode_icon").attr("tipo");
                icon_base = $("#encode_icon").val();
                icon_atual = $("#encode_icon").attr("atual");

                if(icon_name && icon_type && icon_base){

                    campos.push({name: 'icon-name', value: icon_name})
                    campos.push({name: 'icon-type', value: icon_type})
                    campos.push({name: 'icon-base', value: icon_base})
                    campos.push({name: 'icon-atual', value: icon_atual})

                }

                detalhes = [];
                dds = [];

                $("input[valores]").each(function () {
                    opc = $(this).attr('opc');
                    stu = $('input[chave][opc="' + opc + '"]').val();
                    //dds[opc] = [$(this).val(), stu];
                    dds[opc] = {
                        "valor": $(this).val(),
                        "quantidade": stu,
                    };

                    /*dds.push({
                        "valor": $(this).val(),
                        "quantidade": stu,
                    });*/
                });

                detalhes = JSON.stringify(Object.assign({}, dds));

                campos.push({name: 'detalhes', value: detalhes});

                Carregando();

                $.ajax({
                    url:"src/produtos/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/produtos/index.php",
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