<?php
    include("../../lib/includes.php");

    $cod = base64_decode($_POST['cod']);

    $query = "select a.*, b.mesa as mesa from vendas_produtos a left join mesas b on a.mesa = b.codigo where a.codigo IN ({$cod})";
    $result = mysqli_query($con, $query);

    while($d = mysqli_fetch_object($result)){

        $pedido = json_decode($d->produto_json);
        $sabores = false;
        $ListaPedido = [];
        for($i=0; $i < count($pedido->produtos); $i++){
            $ListaPedido[] = $pedido->produtos[$i]->descricao;
        }
        if($ListaPedido) $sabores = implode(', ', $ListaPedido);

?>
    <tr>
        <td>
            <div class="form-group form-check">
                <input status cod="<?=$d->codigo?>" <?=(($d->situacao == 'i')?'checked':false)?> type="checkbox" class="form-check-input" id="<?="{$opc}{$d->codigo}"?>">
            </div>
        </td>
        <td><label class="form-check-label" for="<?="{$opc}{$d->codigo}"?>"><?=$d->mesa?></label></td>
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