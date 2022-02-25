<?php
include("../../../../lib/includes.php");

$query = "SELECT * FROM lista_item where list_cliente = '{$_SESSION['ms_cli_codigo']}' order by codigo desc";
$result = mysql_query($query);


while ($d = mysql_fetch_object($result)) {
 ?>
    <div del<?=$d->codigo?> class="ms_usuario_lista">
        <div class="ms_usuario_lista_titulo_item">
            <i class="fas fa-pencil-alt fa-2x ms_usuario_lista_icone_esquerdo"></i>
            <span
                    excluir<?=$md5?>
                    class="ms_usuario_lista_icone_direito"
                    data-id="<?= $d->codigo; ?>">
                <i class="far fa-trash-alt fa-2x"></i></span>
            <span ItemLista><?=utf8_encode( $d->list_descricao) ?></span>
        </div>
    </div>

<?php
}

?>
<script>
    $(function () {
        Carregando('none');

        $("span[ItemLista]").off('click').on('click', function () {
            ItemLista = $(this).text();
            console.log(ItemLista);
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"componentes/ms_busca_topo_fixo.php",
                    ItemLista,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });

        });


        $("span[excluir<?=$md5?>]").off('click').on('click', function () {
            obj = $(this);
            codigo = obj.data("id");
            $.confirm({
                content: '<center>Deseja realmente excluir o item da lista?</center>',
                theme: "Material",
                type: 'red',
                title: false,
                buttons: {
                    'SIM': {
                        text: 'SIM',
                        btnClass: 'btn-red',
                        action: function () {
                            //Carregando();
                            $("div[del"+codigo+"]").remove();
                            $.ajax({
                                url: "src/usuarios/lista.php",
                                type: "GET",
                                data: {
                                    acao: "excluir",
                                    codigo
                                },
                                success:function(dados){

                                    /*
                                    $.ajax({
                                        url: "src/usuarios/lista_nova.php",
                                        success: function (dados) {
                                            $("div[conteudo_lista]").html(dados);
                                        }

                                    });
                                    //*/


                                }
                            });
                        }
                    },
                    'NÃO': {
                        text: 'NÃO',
                        btnClass: 'btn-green',
                        action: function () {

                        }
                    }
                }
            });

        });
    })
</script>