<?php
    include("../../lib/includes/includes.php");


    if ($_SERVER['REQUEST_METHOD'] === "GET" and $_GET['acao'] === "excluir") {
    $codigo = $_GET['codigo'];

    $query = "DELETE FROM produtos WHERE codigo = '$codigo'";

    if (mysql_query($query)) {
        echo json_encode(['status' => true, 'msg' => 'Registro excluído com sucesso']);
    } else {
        echo json_encode(['status' => false, 'msg' => 'Error ao excluír', 'query' => $query]);
    } 
}
    elseif($_SERVER['REQUEST_METHOD'] === "POST"){
    $data = $_POST;

       $inserir="INSERT INTO lista_item set list_descricao";
    }
     exit;



    $query="SELECT * FROM lista_item";
    $result = mysql_query($query);

?>
<style>
    .ms_usuario_lista_titulo_topo{
        position:fixed;
        left:0;
        top:0;
        width:100%;
        height:65px;
        background:#fff;
        text-align:center;
        color:#777;
        font-size:18px;
        font-weight:bold;
        z-index:10;
        padding:15px;
    }

    .ms_usuario_lista{
        position:relative;
        width:100%;
        height:60px;
        margin-bottom:10px;
    }

    .ms_usuario_lista_titulo_item{
        position:absolute;
        left:10px;
        right:10px;
        height:100%;
        background-color:#F1F3F2;
        padding-top:20px;
        padding-left:50px;
        padding-right:45px;
        border-radius:20px;
        color:#777777;
        cursor:pointer;
    }
    .ms_usuario_lista_icone_esquerdo{
        position:absolute;
        left:10px;
        top:15px;
        color:#32CB4B;
        opacity:0.4;
    }
    .ms_usuario_lista_icone_direito{
        position:absolute;
        right:15px;
        top:15px;
        color:red;
        opacity:0.4;
    }




    .ms_usuario_lista_form{
        position:fixed;
        width:100%;
        height:60px;
        top:65px;
        background-color:#fff;
        border:solid 0px red;
        z-index:10;
    }

    .ms_usuario_lista_form span{
        position: absolute;
        left:10px;
        top:10px;
        right:110px;
    }

    .ms_usuario_lista_form span input{
        position: relative;
        width:100%;
        padding:5px;
        padding-left:35px;
        height:40px;
        background-color:#F1F4F3;
        background-position:left 10px center;
        background-size:20px 20px;
        background-repeat:no-repeat;
        border:0;
        border-radius:10px;
        color:#777777;
    }

    .ms_usuario_lista_form button{
        position:absolute;
        right:5px;
        top:10px;
        width:100px;
        height:40px;
        border-radius:10px;
        color:#4CBB5E;
    }

    .ms_usuario_lista_form svg{
        position:absolute;
        left:20px;
        top:22px;
        z-index:1;
        color:#32CB4B;
    }
</style>

<div class="ms_usuario_lista_titulo_topo">Lista de Itens</div>

<div class="ms_usuario_lista_form">
    <i class="fas fa-pencil-alt"></i>
    <span><input valor_busca type="text" /></span>
    <button incluir_item
            type="button"
            class="btn btn-light"
            value="<?= $d->list_descricao; ?>"
            required
    >
        Incluir
    </button>
</div>
<div style="margin-top:70px"></div>
  <?php
        if (mysql_num_rows($result)) {
        while ($d = mysql_fetch_object($result)) {
        ?>
<div
    class="ms_usuario_lista">
    <div class="ms_usuario_lista_titulo_item">
        <i class="fas fa-pencil-alt fa-2x ms_usuario_lista_icone_esquerdo"></i>
        <span class="ms_usuario_lista_icone_direito"  data-id="<?= $d->codigo; ?>"><i class="far fa-trash-alt fa-2x"></i></span>
        <?=$d->list_descricao?>
    </div>
</div>

<?php
    }
?>
<script>
    $(function(){
        Carregando('none');

        $("[incluir_item]").off('click').on('click', function(){
             obj = $(this).parent("div").parent("div");

              var list_valor = $("#subcat_categoria").val();
              var dados = jQuery(this).serialize();
             //alert("ok");
              $.ajax({
                 url: "src/usuarios/lista.php",
                  type: "POST",
              data: {
                  dados: dados,
                },
                type: "POST",
                success: function (data) {
                  
                  $inserir=`INSERT INTO lista_item('list_descricao') VALUES ('$obj')`;

                }
              
              })
            // $.confirm({
            //     content:'<center>Deseja realmente incluir?</center>',
            //     theme: "Material",
            //     type: 'red',
            //     title:false,
            //     buttons: {
            //         'SIM': {
            //             text: 'SIM',
            //             btnClass: 'btn-red',
            //             action: function(){
            //                 obj.remove();
            //             }
            //         },
            //         'NÃO': {
            //             text: 'NÃO',
            //             btnClass: 'btn-green',
            //             action: function(){

            //             }
            //         }
            //     }
            // });

        })
        $(".ms_usuario_lista_icone_direito").off('click').on('click', function(){

            obj = $(this).parent("div").parent("div");
            var codigo = $(this).data("id");
            $.confirm({
                content:'<center>Deseja realmente excluir o item da lista?</center>',
                theme: "Material",
                type: 'red',
                title:false,
                buttons: {
                    'SIM': {
                        text: 'SIM',
                        btnClass: 'btn-red',
                        action: function(){
                            //obj.remove();
                             $.ajax({
                                url: "src/usuarios/lista.php",
                                type: "GET",
                                data: {
                                    acao: "excluir",
                                    codigo
                                }


                             });

                        }
                    },
                    'NÃO': {
                        text: 'NÃO',
                        btnClass: 'btn-green',
                        action: function(){

                        }
                    }
                }
            });

        });
    })
</script>