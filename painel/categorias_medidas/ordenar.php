<?php
include("../../lib/includes.php");

$query = "SELECT codigo, medida FROM categoria_medidas ORDER BY ordem, medida";
$result = mysqli_query($con, $query);

?>
<style>
    #sortable .list-group-item{
        cursor: grab;
    }
</style>
<ul class="list-group" id="sortable">
    <?php
    $i = 0;
    while ($d = mysqli_fetch_object($result)):
        $i++;
        ?>

        <li class="list-group-item" data-codigo="<?= $d->codigo; ?>"><?= $d->medida; ?></li>

    <?php endwhile; ?>
</ul>
<script>
    $(function () {
        $("#sortable").sortable();
    });
</script>
