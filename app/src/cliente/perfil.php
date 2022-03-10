<?php
    include("../../../lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:70px;
        top:0px;
        height:60px;
        background:#fff;
        padding-top:15px;
        z-index:1;
    }

</style>
<div class="PedidoTopoTitulo">
    <h4>Perfil do Cliente</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">

                <div class="form-group">
                    <label for="nome">Telefone</label>
                    <div class="form-control form-control-lg"><?=$c->telefone?></div>
                </div>

                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="form-control form-control-lg" id="nome" placeholder="Seu Nome Completo" value="<?=$c->nome?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="seuemail@seudominio.com" value="<?=$c->email?>">
                </div>
                <div class="form-row">
                    <div class="form-group col">
                    <label for="Senha">Senha</label>
                    <input type="text" class="form-control form-control-lg" id="Senha">
                    </div>
                    <div class="form-group col">
                    <label for="ConfirmaSenha">Confirmar Senha</label>
                    <input type="text" class="form-control form-control-lg" id="ConfirmaSenha">
                    </div>
                </div>
                <button type="buttom" class="btn btn-primary">Salvar dados</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){


    })
</script>