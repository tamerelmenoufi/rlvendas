<style>
    #preview{
        position:fixed;
        top:0px;
        left:0px;
        width:100%;
        height: 100%;
        border:solid 2px red;
    }
</style>
<div class="col">
    <video id="preview"></video>
    <script>
        $(function(){

            let scanner = new Instascan.Scanner(
                {
                    video: document.getElementById('preview')
                }
            );
            scanner.addListener('scan', function(content) {
                alert('Escaneou o conteudo: ' + content);
                window.open(content, "_blank");
            });
            Instascan.Camera.getCameras().then(cameras =>
            {
                if(cameras.length > 0){
                    scanner.start(cameras[1]);
                    console.error(cameras);
                } else {
                    console.error("Não existe câmera no dispositivo!");
                }
            });

        })
    </script>

</div>