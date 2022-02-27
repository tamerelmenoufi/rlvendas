<center style="margin-top:50px;"><h3>Página Principal</h3></center>
<button class="btn btn-success btn-lg btn-block" acao<?=$md5?> local="src/home/j100.php" janela="ms_popup_100">POPUP 100</button>
<button class="btn btn-primary btn-lg btn-block" acao<?=$md5?> local="src/home/j50.php" janela="ms_popup">POPUP 50</button>

<script>
    $(function(){

        Carregando('none');

        $("button[acao<?=$md5?>]").off('click').on('click',function(){
            local = $(this).attr('local');
            janela = $(this).attr('janela');
            Carregando();
            $.ajax({
                url:"componentes/"+janela+".php",
                type:"POST",
                data:{
                    local,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        })


    })

</script>