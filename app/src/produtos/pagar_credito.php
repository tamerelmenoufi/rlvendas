<!-- <center>
<br><br><br>
    <h1>Formato Cartão de Crédito indisponível no momento!</h1>
</center> -->
<?php
// exit();
?>

<iframe src="cartao/cartao.php" frameborder="0" style="border:0; padding:0; margin:0; width:100%; height:380px;"></iframe>

<script>
    $(function(){
        payConfirm = () => {
            $.alert('Obrigado pelo seu pagamento!');
            window.location.href="https://cegonha.project.tec.br/index.php?c=<?=md5($_SESSION['convidado'])?>"
        }
    })
</script>