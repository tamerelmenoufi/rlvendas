<?php
    include("../../lib/includes.php");
    include "./conf.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    unset($data['codigo'], $data['senha_2']);

    if (!$codigo) $data['data_cadastro'] = date("Y-m-d H:i:s");

    if ($codigo and empty($data['senha'])) unset($data['senha']);

    foreach ($data as $name => $value) {
        if ($name == 'senha') {
            $attr[] = "{$name} = '" . md5($value) . "'";
        } else {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
    }

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE atendentes SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO atendentes SET {$attr}";
    }

    #file_put_contents("query.txt",$query);

    if (mysqli_query($con, $query)) {
        $codigo = $codigo ?: mysqli_insert_id($con);

        sis_logs('atendentes', $codigo, $query);

        echo json_encode([
            'status' => true,
            'msg' => 'Dados salvo com sucesso',
            'codigo' => $codigo,
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Erro ao salvar ',
            'codigo' => $codigo,
            'mysql_error' => mysqli_error($con),
        ]);
    }

    exit;
}

$codigo = $_GET['codigo'];

if ($codigo) {
    $query = "SELECT * FROM atendentes WHERE codigo = '{$codigo}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $GetPerfis = json_decode($d->perfil);

    var_dump($GetPerfis);
}

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="#" url="./">Início</a></li>
        <li class="breadcrumb-item" aria-current="page">
            <a href="#" url="<?= $UrlScript; ?>/index.php"><?=$ConfTitulo?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?>
        </li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?> <?=$ConfTitulo?>
        </h6>
    </div>
    <div class="card-body">
        <form id="form-<?=$md5?>">
            <div class="form-group">
                <label for="nome">Nome <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="nome"
                        name="nome"
                        value="<?= $d->nome; ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="nome">CPF <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="cpf"
                        name="cpf"
                        value="<?= $d->cpf; ?>"
                        inputmode="numeric"
                        required
                >
            </div>

            <div class="form-group">
                <label for="telefone">Telefone <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="telefone"
                        name="telefone"
                        value="<?= $d->telefone; ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="email">E-mail <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?= $d->email; ?>"
                        required
                >
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4>Perfil de Acesso</h4>
                    <?php
                    $perfis = [
                        'ExcluirProduto' => 'Excluir Priduto em produção',
                        'ExcluirProduto1' => 'Excluir Priduto em produção 1',
                        'ExcluirProduto2' => 'Excluir Priduto em produção 2',

                    ];
                    foreach($perfis as $indice => $valor){
                    ?>
                    <div class="form-group">
                        <div class="form-check">
                        <input
                                perfil
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="<?=$indice?>"
                                <?=(($GetPerfis[$indice])?'checked':false)?>
                        >
                        <label class="form-check-label" for="<?=$indice?>">
                            <?=$valor?>
                        </label>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="senha">Senha <i class="text-danger">*</i></label>
                        <input
                                type="password"
                                class="form-control"
                                id="senha"
                                name="senha"
                                value="<?= !$codigo ? $d->senha : ''; ?>"
                            <?= !$codigo ? 'required' : ''; ?>
                        >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="senha_2">Confirmar senha <i class="text-danger">*</i></label>
                        <input
                                type="password"
                                class="form-control"
                                id="senha_2"
                                name="senha_2"
                            <?= !$codigo ? 'required' : ''; ?>
                        >
                    </div>
                </div>
            </div>


            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>

            <a class="btn btn-danger" href="#" url="<?= $UrlScript; ?>/index.php">Cancelar</a>

        </form>
    </div>
</div>

<script>
    $(function () {

        $("#cpf").mask("999.999.999-99");

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

        $("#telefone").mask("(99) 9 9999-9999");

        $('#form-<?=$md5?>').validate();

        $('#form-<?=$md5?>').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            perfil = [];
            $("input[perfil]").each(function(){
                perfil.push({name: $(this).attr("id"), value: $(this).prop("checked")});
            });

            if(perfil){
                 dados.push({name: 'perfil', value: JSON.stringify(perfil)});
            }

            if (codigo) {
                dados.push({name: 'codigo', value: codigo});
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



