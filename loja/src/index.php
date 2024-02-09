<?php
    include("../../lib/includes.php");

    $query = "select * from vendas where app = 'delivery' order by codigo desc";
    $result = mysqli_query($query);
    while($d = mysqli_fetch_object($result)){
?>
<div>
    <?=$d->codigo?>
</div>
<?php
    }
?>