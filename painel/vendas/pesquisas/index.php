<?php
    include("../../../lib/includes.php");
?>
<div class="col">
    <div class="row">
        <div class="col-md-4">
            <label for="mesa">Mesa</label>
            <select id="" class="form-control">
                <option value="">::Selecione a mesa::</option>
                <?php
                $q = "select * from mesas where situacao = '1' and deletado != '1' order by mesa";
                $r = mysqli_query($con, $q);
                while($m = mysqli_fetch_object($r)){
                ?>
                <option value="<?=$m->codigo?>"><?=$m->mesa?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="data">Data</label>
            <input type="text" class="form-control" />
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-success">Buscar</button>
        </div>
    </div>
</div>