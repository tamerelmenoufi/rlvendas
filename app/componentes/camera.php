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
        $(function(){

            AtivarCamera('preview<?=$md5?>');

        })
    </script>

</div>