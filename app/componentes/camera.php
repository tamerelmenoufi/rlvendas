<?php
    include("../../lib/includes.php");
?>
<style>
    #preview<?=$md5?>{
        position:fixed;
        top:0px;
        left:0px;
        width:100%;
        height: 100%;
        border:solid 2px red;
    }
</style>
<div class="col">
    <video id="preview<?=$md5?>"></video>
    <script>


        let scanner<?=$md5?> = new Instascan.Scanner(
            {
                video: document.getElementById('preview<?=$md5?>')
            }
        );
        scanner<?=$md5?>.addListener('scan', function(content) {
            alert('Escaneou o conteudo: ' + content);
            window.open(content, "_blank");
        });
        Instascan.Camera.getCameras().then(cameras =>
        {
            if(cameras.length > 0){
                scanner<?=$md5?>.start(cameras[1]);
                console.error(cameras);
            } else {
                console.error("Não existe câmera no dispositivo!");
            }
        });


    </script>

</div>