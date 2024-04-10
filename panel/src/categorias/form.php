<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        if ($data['icone-base']) {

            $md52 = md5($md5.$data['icone-name']);

            if(!is_dir("../volume")) mkdir("../volume");
            if(!is_dir("../volume/categorias")) mkdir("../volume/categorias");

            list($x, $icon) = explode(';base64,', $data['icone-base']);
            $icon = base64_decode($icon);
            $pos = strripos($data['icone-name'], '.');
            $ext = substr($data['icone-name'], $pos, strlen($data['icone-name']));
    
            $atual = $data['icone-atual'];
    
            unset($data['icone-base']);
            unset($data['icone-type']);
            unset($data['icone-name']);
            unset($data['icone-atual']);
    
            if (file_put_contents("../volume/categorias/{$md52}{$ext}", $icon)) {
                $attr[] = "icone = '{$md52}{$ext}'";
                if ($atual) {
                    unlink("../volume/categorias/{$atual}");
                }
            }
    
        }

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update categorias set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into categorias set {$attr}";
            sisLog($query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'status' => true,
            'codigo' => $cod
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from categorias where codigo = '{$_POST['cod']}'";
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
<h4 class="Titulo<?=$md5?>">Cadastro das Categorias</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Identificação da Mesa" value="<?=$d->categoria?>">
                    <label for="categoria">Categoria*</label>
                </div>

                <label for="file_<?= $md5 ?>">Incluir / Editar - Imagem da Categoria *</label>
                <?php
                if(is_file("../volume/categorias/{$d->icone}")){
                ?>
                <center><img src="src/volume/categorias/<?=$d->icone?>" class="mb-3 img-fluid" /></center>
                <?php
                }
                ?>
                <div class="encode_icone"></div>
                <div class="input-group mb-3">
                    <input 
                        type="file" 
                        class="form-control" 
                        id="file_<?= $md5 ?>" 
                        target="encode_icone"
                        accept="image/*"
                        w="270"
                        h="240"
                    >
                    <label class="input-group-text" for="file_<?= $md5 ?>">Selecionar</label>
                    <input
                        type="hidden"
                        id="encode_icone"
                        nome=""
                        tipo=""
                        value=""
                        atual="<?= $d->icone; ?>"
                    />
                </div>

                <div class="accordion mb-3" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#itens_medidas" aria-expanded="false" aria-controls="itens_medidas">
                            Unidades de Medidas
                        </button>
                        </h2>
                        <div id="itens_medidas" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                <?php
                                    $q2 = "select * from categoria_medidas where deletado != '1' order by ordem asc";
                                    $r2 = mysqli_query($con, $q2);
                                    while($d2 = mysqli_fetch_object($r2)){
                                ?>
                                    <li class="d-flex justify-content-start list-group-item list-group-item-action" >
                                        <input class="form-check-input me-1 opcao_medidas" codigo="<?=$d2->codigo?>" type="checkbox" <?=((in_array($d2->codigo,$medidas))?'checked':false)?> value="<?=$d2->codigo?>"  id="acao<?=$d2->codigo?>">
                                            <label class="form-check-label w-100" for="acao<?=$d2->codigo?>">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-break"><?=$d2->medida?></span>
                                                </div>
                                            </label> 
                                    </li>
                                <?php

                                    }

                                ?>
                                </ul>
                            </div>
                        </div>
                    </div> 
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

                //Medidas
                var medidas = [];

                $(".opcao_medidas").each(function (index, item) {
                    //console.log($(item).val());
                    if ($(item).is(':checked')) {
                        medidas.push($(item).val());
                    }
                });

                campos.push({name: 'medidas', value: medidas.join(',')});

                campos.push({name: 'acao', value: 'salvar'})

                icone_name = $("#encode_icone").attr("nome");
                icone_type = $("#encode_icone").attr("tipo");
                icone_base = $("#encode_icone").val();
                icone_atual = $("#encode_icone").attr("atual");

                if(icone_name && icone_type && icone_base){

                    campos.push({name: 'icone-name', value: icone_name})
                    campos.push({name: 'icone-type', value: icone_type})
                    campos.push({name: 'icone-base', value: icone_base})
                    campos.push({name: 'icone-atual', value: icone_atual})

                }

                Carregando();

                $.ajax({
                    url:"src/categorias/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        // console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/categorias/index.php",
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