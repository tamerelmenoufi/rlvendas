<?php
    include("../../lib/includes.php");
    include "./conf.php";

    file_put_contents('print/'.md5($md5.$_POST['pdf']).'.pdf', $_POST['pdf']);

?>