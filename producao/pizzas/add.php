<?php
    include("../../lib/includes.php");

    $query = "select a.*, b.mesa as mesa from vendas_produtos a left join mesas b on a.mesa = b.codigo where a.codigo = ({$_POST['cod']})";
    $result = mysqli_query($con, $query);

    while($d = mysqli_fetch_object($result)){
?>
    <tr>
        <td>
            <div class="form-group form-check">
                <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">
            </div>
        </td>
        <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">XXX <?=$d->mesa?></label></td>
        <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><b><?=$d->quantidade?></b></label></td>
        <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>">
            <?=$pedido->categoria->descricao?>
            - <?=$pedido->medida->descricao?> (<?=$sabores?>)
            <p class="card-text" style="color:red;">
            <?= $d->produto_descricao?></p>
        </label></td>
        <td><button concluir cod="<?=$d->codigo?>" class="btn btn-primary btn-sm">Concluir</button></td>
    </tr>
<?php
    }
?>