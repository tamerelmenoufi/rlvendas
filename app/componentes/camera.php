<?php
    include("../../lib/includes.php");
?>
<style>
    #videoCaptura{
        position:fixed;
        top:0px;
        left:0px;
        width:100%;
        height: 100%;
        border:solid 2px red;
        margin:0;
        padding:0;
    }
    #DadosCaptura{
        position:fixed;
        bottom:10px;
        left:0px;
        width:100%;
        padding:10px;
        color:#fff;
        font-weight:bold;
        text-align:center;
        border:solid 1px green;
    }
</style>
    <iframe id="videoCaptura" src="../lib/vendor/camera/camera.html?<?=$md5?>"></iframe>
    <div id="DadosCaptura"></div>
    <script>

    </script>