<?php
include("../../lib/includes.php");

$query = "SELECT codigo, medida FROM categoria_medidas ORDER BY ordem";
$result = mysqli_query($con, $query);

?>

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
