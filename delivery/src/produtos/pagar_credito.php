
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
        payConfirm = () => {
            $.alert('Solicitação processada, aguarde a confirmação!');
            // window.location.href="https://cegonha.project.tec.br/index.php?c=<?=md5($_SESSION['convidado'])?>"
        }
    })
</script>