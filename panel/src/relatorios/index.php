<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");


    if($_POST['acao'] == 'busca'){

        $_SESSION['data_inicial'] = $_POST['data_inicial'];
        $_SESSION['data_final'] = $_POST['data_final'];



    }

?>


<div class="row g-0">
    <div class="col">
        <div class="m-3">
            <h4>Relatório de Vendas</h4>
            <div class="input-group">
                <span class="input-group-text">Em</span>
                <input id="data_inicial" value="<?=$_SESSION['vendas_data_inicial']?>" type="date" class="form-control" >
                <span class="input-group-text">até</span>
                <input id="data_final" value="<?=$_SESSION['vendas_data_final']?>" type="date" class="form-control" >
                <button buscar class="btn btn-outline-secondary" type="button" id="button-addon1">Listar</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){
        Carregando('none')


        $("button[buscar]").click(function(){
            data_inicial = $("#data_inicial").val()
            data_final = $("#data_final").val()
            if(data_inicial && data_final){

                $.ajax({
                    url:"src/relatorios/index.php",
                    data:{
                        data_inicial,
                        data_final,
                        acao:'busca'
                    },
                    type:"POST",
                    success:function(dados){
                        $("#paginaHome").html(dados);
                    }
                });

            }else{

                $.alert({
                    title:"Erro Busca",
                    content:"Informe o intervalo de datas para a busca",
                    type:"red"
                })
                return false;

            }
        })
    })
</script>