<?php
    include("../lib/includes.php");
    if($_GET['s']) {
        $_SESSION = [];
        header("location:./");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#990002"/>
    <title>APP</title>
    <?php include("../lib/header.php"); ?>
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/app.css">


</head>
<body>
<div class="Carregando">
    <span>
        <i class="fa-solid fa-spinner"></i>
    </span>
</div>

<div class="ms_corpo"></div>

<?php include("../lib/footer.php"); ?>

<script src="<?= "js/app.js?" . date("YmdHis"); ?>"></script>
<script src="<?= "js/wow.js"; ?>"></script>

<script>
    $(function () {

        // <?php
        // if($_GET['s']){
        // ?>
        // window.localStorage.removeItem('AppPedido');
        // window.localStorage.removeItem('AppVenda');
        // window.localStorage.removeItem('AppCliente');
        // window.location.href='./';
        // return false;
        // <?php
        // }
        // ?>

        <?php
        if($_GET['n']){
        ?>
        window.localStorage.setItem('AppPedido', '<?=$_SESSION['AppPedido']?>');
        <?php
        }
        ?>
        $.ajax({
            url: "src/home/index.php",
            success: function (dados) {
                $(".ms_corpo").html(dados);
            }
        });
    })


    //Configurações globais

    //Jconfirm
    jconfirm.defaults = {
        theme: "modern",
        type: "blue",
        typeAnimated: true,
        smoothContent: true,
        draggable: false,
        animation: 'bottom',
        closeAnimation: 'top',
        animateFromElement: false,
        animationBounce: 1.5
    }





    // // Socket Variable declaration
    // var mySocket;
    // // const socketMessageListener = (event) => {
    // //     const dados = JSON.parse(event.data);
    // //     if(dados.type === 'chat'){
    // //         output.append('Outro: ' + dados.text, document.createElement('br'));
    // //     }
    // // };

    // // Open
    // const socketOpenListener = (event) => {
    // console.log('Connected');

    // };

    // // Closed
    // const socketCloseListener = (event) => {
    // if (mySocket) {
    //     console.error('Disconnected.');
    // }
    // mySocket = new WebSocket('wss://websocket.yobom.com.br');

    // input = document.querySelector('input');
    // output = document.querySelector('output');

    // mySocket.addEventListener('open', socketOpenListener);
    // //  mySocket.addEventListener('message', socketMessageListener);
    // mySocket.addEventListener('close', socketCloseListener);

    // };
    // socketCloseListener();

    // // input.addEventListener('keypress', e => {
    // //         if(e.code === 'Enter'){
    // //             const valor = input.value;
    // //             //output.append('Eu: ' + valor, document.createElement('br'));
    // //             mySocket.send(valor);

    // //             input.value = '';
    // //         }
    // //     });






</script>
<form></form>
</body>
</html>