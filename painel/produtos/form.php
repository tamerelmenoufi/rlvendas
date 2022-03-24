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
        $attr[] = "icon = '{$md5}{$ext}'";
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

    if (!$codigo) $attr[] = "categoria = '" . $ConfCategoria->codigo . "'";

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE produtos SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO produtos SET {$attr}";
    }

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('produtos', $codigo, $query);

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
    $query = "SELECT * FROM produtos WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
}

?>

<style>
    .cor {
        padding-top: 15px;
    }

    .cor:hover {
        background-color: #eee;
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="#" url="./">Início</a></li>
        <li class="breadcrumb-item" aria-current="page">
            <a href="#" url="<?= $UrlScript; ?>/index.php"><?= $ConfTitulo ?> - <?= $ConfCategoria->categoria ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?>
        </li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> <?= $ConfTitulo ?> - <?= $ConfCategoria->categoria ?>
        </h6>
    </div>

    <div class="card-body">
        <form id="form-<?= $md5 ?>">
            <div class="form-group">
                <label for="produto">produto <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="produto"
                        name="produto"
                        value="<?= $d->produto; ?>"
                        required
                >
            </div>
            <div class="form-group">
                <label for="descricao">Descrição <i class="text-danger"></i></label>

                <textarea
                        class="form-control"
                        id="descricao"
                        name="descricao"
                ><?= $d->descricao; ?></textarea>

            </div>

            <div class="form-group">
                <label for="medidas">Valores <i class="text-danger">*</i></label>

                <?php
                $query1 = "SELECT * FROM categoria_medidas "
                    . "WHERE deletado != '1' AND codigo IN({$ConfCategoria->medidas}) "
                    . "ORDER BY ordem, medida";
                $result1 = mysqli_query($con, $query1);

                $detalhes = json_decode($d->detalhes, true);

                while ($dados = mysqli_fetch_object($result1)):
                    ?>
                    <div class="row cor">
                        <div class="col-md-8">
                            <?= $dados->medida; ?>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>

                                <input
                                        valores
                                        opc="<?= $dados->codigo ?>"
                                        value="<?= $detalhes[$dados->codigo]['valor']; ?>"
                                        type="number"
                                        class="form-control"
                                >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input
                                    situacao
                                    opc="<?= $dados->codigo ?>"
                                    value="<?= (($detalhes[$dados->codigo]['quantidade']) ?: '0') ?>"
                                    type="checkbox" <?= (($detalhes[$dados->codigo]['quantidade']) ? 'checked' : false) ?>
                                    data-toggle="toggle"
                            >
                        </div>

                    </div>
                <?php endwhile; ?>

            </div>

            <div class="form-group">
                <label for="situacao">
                    Situação <i class="text-danger">*</i>
                </label>
                <?php
                if (is_file("icon/{$d->icon}")) {
                    ?>
                    <center>
                        <i mg
                           src="produtos/icon/<?= $d->icon ?>?<?= $md5 ?>"
                           style="width:200px; margin-bottom:20px;"
                        >
                    </center>
                    <?php
                }
                ?>
                <input
                        type="file"
                        name="file_<?= $md5 ?>"
                        id="file_<?= $md5 ?>"
                        accept="image/*"
                        style="margin-buttom:20px"
                >

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

        $("input[situacao]").bootstrapToggle();

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


        $('input[situacao]').change(function () {
            opc = $(this).attr("opc");
            if ($(this).prop('checked') === true) {
                $(this).val(opc);
            } else {
                $(this).val('0');
            }
        })

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

        $('#form-<?=$md5?>').validate();

        $('#form-<?=$md5?>').submit(function (e) {

            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo});
            }

            detalhes = [];
            dds = [];

            $("input[valores]").each(function () {
                opc = $(this).attr('opc');
                stu = $('input[situacao][opc="' + opc + '"]').val();
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

            dados.push({name: 'detalhes', value: detalhes});

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
                    //console.log(response);
                    //return false;
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



