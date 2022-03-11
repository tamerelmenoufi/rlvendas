<?php

include("../../lib/includes.php");
include "./conf.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    if ($data['file-base']) {

        list($x, $icon) = explode(';base64,', $data['file-base']);
        $icon = base64_decode($icon);
        $pos = strripos($data['file-name'], '.');
        $ext = substr($data['file-name'], $pos, strlen($data['file-name']));

        $atual = $data['file-atual'];

        unset($data['file-base']);
        unset($data['file-type']);
        unset($data['file-name']);
        unset($data['file-atual']);

        if (file_put_contents("icon/{$md5}{$ext}", $icon)) {
            $attr[] = "icon = '{$md5}{$ext}'";
            if ($atual) {
                unlink("icon/{$atual}");
            }
        }

    }

    unset($data['codigo']);

    foreach ($data as $name => $value) {
        $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
    }

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE categorias SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO categorias SET {$attr}";
    }

    // file_put_contents("query.txt",$query);

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('categorias', $codigo, $query);

        echo json_encode([
            'status' => true,
            'msg' => 'Dados salvo com sucesso',
            'codigo' => $codigo,
        ]);

    } else {

        echo json_encode([
            'status' => false,
            'msg' => 'Erro ao salvar',
            'codigo' => $codigo,
            'mysql_error' => mysqli_error($con),
        ]);

    }

    exit;
}

$codigo = $_GET['codigo'];

if ($codigo) {
    $query = "SELECT * FROM categorias WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="#" url="./">Início</a></li>
        <li class="breadcrumb-item" aria-current="page">
            <a href="#" url="<?= $UrlScript; ?>/index.php"><?= $ConfTitulo ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?>
        </li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> <?= $ConfTitulo ?>
        </h6>
    </div>
    <div class="card-body">
        <form id="form-<?= $md5 ?>">
            <div class="form-group">
                <label for="categoria">Categoria <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="categoria"
                        name="categoria"
                        value="<?= $d->categoria; ?>"
                        required
                >
            </div>

            <!-- <div class="form-group">
                <label for="medida">
                    Medida <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control"
                        id="medida"
                        name="medida"
                        data-live-search="true"
                        data-none-selected-text="Selecione"
                        required
                >
                    <option value=""></option>
                    <?php
            $query = "SELECT * FROM categoria_medidas where deletado != '1' ORDER BY medida";
            $result = mysqli_query($con, $query);

            while ($a = mysqli_fetch_object($result)): ?>
                        <option
                            <?= ($codigo and $d->medida == $a->codigo) ? 'selected' : ''; ?>
                                value="<?= $a->codigo ?>">
                            <?= $a->medida; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div> -->

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="medidas">Medidas <i class="text-danger">*</i></label>

                        <?php
                        $query1 = "SELECT * FROM categoria_medidas where deletado != '1' ORDER BY medida";
                        $result1 = mysqli_query($con, $query1);

                        $check = explode(',', $d->medidas);

                        while ($dados = mysqli_fetch_object($result1)):
                            $isChecked = (@in_array($dados->codigo, $check));
                            ?>
                            <div class="form-check">
                                <input
                                        class="form-check-input medidas"
                                        type="checkbox"
                                        id="medidas-<?= $dados->codigo; ?>"
                                        value="<?= $dados->codigo; ?>"
                                    <?= $isChecked ? 'checked' : ''; ?>

                                >
                                <label class="form-check-label" for="medidas-<?= $dados->codigo; ?>">
                                    <?= $dados->medida; ?>
                                </label>
                            </div>
                        <?php endwhile; ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="categorias">Categorias Associadas<i class="text-danger">*</i></label>
                        <?php
                        $query1 = "SELECT codigo, categoria FROM categorias "
                            . "WHERE codigo != '{$d->codigo}' AND deletado != '1' "
                            . "ORDER BY categoria";

                        $result1 = mysqli_query($con, $query1);

                        $check = explode(',', $d->categorias_associadas);

                        while ($dados = mysqli_fetch_object($result1)):
                            $isChecked = (@in_array($dados->codigo, $check));
                            ?>
                            <div class="form-check">
                                <input
                                        class="form-check-input categorias_associadas"
                                        type="checkbox"
                                        id="categorias-<?= $dados->codigo; ?>"
                                        value="<?= $dados->codigo; ?>"
                                    <?= $isChecked ? 'checked' : ''; ?>
                                >

                                <label class="form-check-label" for="categorias-<?= $dados->codigo; ?>">
                                    <?= $dados->categoria; ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="situacao">
                    Situação <i class="text-danger">*</i>
                </label>
                <?php
                if (is_file("icon/{$d->icon}")) {
                    ?>
                    <center><img src="categorias/icon/<?= $d->icon ?>?<?= $md5 ?>"
                                 style="width:200px; margin-bottom:20px;"></center>
                    <?php
                }
                ?>
                <input type="file" name="file_<?= $md5 ?>" id="file_<?= $md5 ?>" accept="image/*"
                       style="margin-buttom:20px">
                <input
                        type="hidden"
                        id="encode_file"
                        nome=""
                        tipo=""
                        value=""
                        atual="<?= $d->icon; ?>"
                />
            </div>


            <div class="form-group">
                <label for="situacao">
                    Situação <i class="text-danger">*</i>
                </label>
                <select
                        class="form-control"
                        id="situacao"
                        name="situacao"
                        required
                >
                    <?php
                    foreach (getSituacao() as $key => $value): ?>
                        <option
                            <?= ($codigo and $d->situacao == $key) ? 'selected' : ''; ?>
                                value="<?= $key; ?>">
                            <?= $value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>

            <a class="btn btn-danger" href="#" url="<?= $UrlScript; ?>/index.php">Cancelar</a>

        </form>
    </div>
</div>

<script>
    $(function () {

        $('#form-<?=$md5?>').validate({
            rules: {
                senha: {
                    minlength: 4
                },
                senha_2: {
                    minlength: 4,
                    equalTo: '[name="senha"]'
                }
            },
            messages: {
                senha: {
                    minlength: 'Digite minímo 4 caracteres'
                },
                senha_2: {
                    minlength: 'Digite minímo 4 caracteres',
                    equalTo: 'As senhas não conferem'
                }
            }
        });

        $('input[type="file"]').fileinput({
            showPreview: false,
            showRemove: false,
            showUpload: false,
        });

        if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {

                if ($(this).val()) {
                    $("div[carregando_metas]").css("display", "block");
                    var files = $(this).prop("files");
                    for (var i = 0; i < files.length; i++) {
                        (function (file) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {
                                var Base64 = f.target.result;
                                var type = file.type;
                                var name = file.name;

                                $("#encode_file").val(Base64);
                                $("#encode_file").attr("nome", name);
                                $("#encode_file").attr("tipo", type);


                            };
                            fileReader.readAsDataURL(file);
                        })(files[i]);
                    }
                }
            });
        } else {
            alert('Nao suporta HTML5');
        }


        $("#telefone").mask("(99) 9 9999-9999");

        $('#form-<?=$md5?>').validate();

        $('#form-<?=$md5?>').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            //Medidas
            var medidas = [];

            $(".medidas").each(function (index, item) {
                //console.log($(item).val());
                if ($(item).is(':checked')) {
                    medidas.push($(item).val());
                }
            });

            dados.push({name: 'medidas', value: medidas.join(',')});

            //Categorias Associadas
            var categorias_associadas = [];

            $(".categorias_associadas").each(function (index, item) {
                //console.log($(item).val());
                if ($(item).is(':checked')) {
                    categorias_associadas.push($(item).val());
                }
            });

            dados.push({name: 'categorias_associadas', value: categorias_associadas.join(',')});

            if ($("#encode_file").val()) {

                dados.push({name: 'file-name', value: $("#encode_file").attr("nome")});
                dados.push({name: 'file-type', value: $("#encode_file").attr("tipo")});
                dados.push({name: 'file-atual', value: $("#encode_file").attr("atual")});
                dados.push({name: 'file-base', value: $("#encode_file").val()});

            }

            $.ajax({
                url: '<?= $UrlScript; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        tata.success('Sucesso', retorno.msg);

                        $.ajax({
                            url: '<?= $UrlScript; ?>/index.php',
                            data: {codigo: retorno.codigo},
                            success: function (response) {
                                $('#palco').html(response);
                            }
                        })
                    } else {
                        tata.error('Error', retorno.msg);
                    }
                }
            })
        });
    });
</script>