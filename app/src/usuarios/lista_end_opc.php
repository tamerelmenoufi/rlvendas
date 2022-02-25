<?php
    include("../../../../lib/includes.php");
    $codigo= $_POST['codigo'];

    $select = "select * from clientes_enderecos where codigo = '{$codigo}'";
    $result = mysql_query($select);
    $d = mysql_fetch_object($result)
?>
<style>
    .ms_lista_end_opc{
        padding: 20px;
        text-align: center;
        width:100%;
    }

</style>
<div class="lista_end_opc">

<div style="text-align:center">
    <?php
        echo utf8_encode($d->cli_end_rua .
        (($d->cli_end_numero)?', '. $d->cli_end_numero:false) .
        (($d->cli_end_bairro)?', '. $d->cli_end_bairro:false) .

        (($d->cli_end_cep)?', '. $d->cli_end_cep:false) .
        (($d->cli_end_cidade)?', '. $d->cli_end_cidade:false) .
        (($d->cli_end_estado)?' - '. $d->cli_end_estado:false));
    ?>
</div>

<div class="ms_lista_end_opc" style="padding: 20px">

            <button
                type="button"
                class="btn btn-outline-secondary btn-block"
                acao
                opc="ativar"
                codigo = "<?=$codigo?>"
                local= "src/usuarios/lista_end.php"
                >
                Ativar como Endereço Padrão
            </button>

            <button
                type="button"
                class="btn btn-outline-secondary btn-block"
                acaoEditar
                opc="editar"

                codigo="<?=$codigo?>"
                codigo_cli = "<?=$codigo?>"
                local="src/usuarios/enderecos.php"
                >
                Editar o cadastro do Endereço
            </button>

            <button
                type="button"
                class="btn btn-danger btn-block"
                acaoExcluir
                opc="excluir"

                codigo="<?=$codigo?>"
                codigo_cli = "<?=$codigo?>"
                local="src/usuarios/lista_end.php"
                >
                Excluir este endereço
            </button>



</div>
</div>

<script>
    $(function(){
        Carregando('none');

$("button[acao]").click(function(){

    opcao = $(this).attr('opc');
    codigo = $(this).attr('codigo');
    local = $(this).attr('local');
    codigo_cli = $(this).attr('codigo_cli');

    $(".ms_usuario_compras_100").removeClass('ms_usuario_compras_100_item_click');
    $("div[card"+codigo+"]").addClass('ms_usuario_compras_100_item_click');

    //$("p[card_rua"+codigo+"], .ruaTexto").html(dados);

    $.ajax({
        url:local,
        type:"POST",
        data: {
            codigo,
            acao:opcao,
            codigo_cli
        },
        success:function(){
            //console.log(codigo);
            PageClose();

            $.ajax({
                url:"src/usuarios/endereco_mapa.php",
                type:"POST",
                data:{
                    cod_end:codigo,
                },
                success:function(dados){
                    $(".ms_usuario_endereco_mapa").html(dados);
                    AppComponentes('home');
                }
            });

        },
        error:function(){

        }
    });
});


        $("button[acaoEditar]").click(function(){
            opcao = $(this).attr('opc');
            codigo = $(this).attr('codigo');
            local = $(this).attr('local');
            codigo_cli = $(this).attr('codigo_cli');
            PageClose();

            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data: {
                    local,
                    codigo,
                    codigo_cli,

                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                        //console.log(codigo);


                },
                error:function(){
                }
            });
        });


        $("button[acaoExcluir]").click(function(){
            opcao = $(this).attr('opc');
            codigo = $(this).attr('codigo');
            local = $(this).attr('local');
            codigo_cli = $(this).attr('codigo_cli');


            $.confirm({
                content:"Deseja Realmente excluir o endereço?",
                title:false,
                buttons:{
                    'SIM':function(){
                        PageClose();
                        $.ajax({
                            url:"componentes/ms_popup_100.php",
                            type:"POST",
                            data: {
                                local,
                                codigo,
                                codigo_cli,
                                acao:opcao,
                            },
                            success:function(dados){
                                PageClose();
                                $(".ms_corpo").append(dados);
                                AppComponentes('home');

                            },
                            error:function(){

                            }
                        });
                    },
                    'NÃO':function(){

                    }
                }
            });



        });


    })
</script>