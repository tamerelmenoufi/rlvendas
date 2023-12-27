<?php
    include("../lib/includes.php");

    if($_POST['chave'] == $chave_producao){
        $retorno = [
            'status'=>'success',
            'logado'=>$chave_producao
        ];
        echo json_encode($retorno);
        exit();
    }elseif($_POST['chave']){
        $retorno = [
            'status'=>'error'
        ];     
        echo json_encode($retorno);
        exit();   
    }
?>


<div class="col">
    <label for="chave">Digite a sua chave de acesso</label>
    <input type="text" id="chave" class="form-control" />
    <button class="btn btn-primary mt-3 w-100 validar">Validar</button>
</div>



<script>
    $(function () {

        $(".validar").click(function(){
            chave = $("#chave").val();
            if(!chave){
                $.alert('Digite a chave de acesso!');
                return false;
            }
            $.ajax({
                url:"login.php",
                type:"POST",
                dataType:"JSON",
                data:{
                    chave,
                },
                success:function(dados){
                    console.log(dados);
                    if(dados.status == 'success'){
                        $.alert('Chave ok!');
                        window.localStorage.setItem('logado',dados.logado);
                        window.location.href='./index.php?<?=$_GET['opc']?>'
                    }else{
                        $.alert('Chave inválida!');
                    }
                }

            });

        })

    });

</script>