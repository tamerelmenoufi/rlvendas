
<style>
      .PedidoTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:60px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }
</style>

  <div class="PedidoTopoTitulo">
      <h4>Dados do Cartão</h4>
  </div>

<iframe src="cartao/cartao.php" frameborder="0" style="border:0; padding:0; margin:0; width:100%; height:650px;"></iframe>

<script>
    $(function(){
        payConfirm = (cod) => {

            $.ajax({
                url:"cartao/status.php",
                type:"POST",
                data:{
                    venda:cod
                },
                success:function(dados){
                    $.alert(dados);
                    PageClose();
                }
            })

        }
    })
</script>