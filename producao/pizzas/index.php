<?php
    include("../../lib/includes.php");
?>
<div style="position:fixed; left:0px; top:0px; bottom:0px; width:100%; border:solid 1px red; overflow:auto; padding-left:25px; padding-right:25px; ">
    <div class="row">
        <?php
        $tipos = ['pizzas','sanduiches'];
        foreach($tipos as $ind => $opc){
        ?>
        <div class="col">
            Dados da cozenha (Produção de Pizzas)

        <?php
            $query = "select * from vendas_produtos /*where situacao = 'p'*/ order by data asc";
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
        <div class="card bg-light mb-3" style="padding-bottom:40px;">
            <div class="card-body">

                <h5 class="card-title" style="paddig:0; margin:0; font-size:20px; font-weight:bold;">
                    <span style="font-size:40px;"><?=$d->quantidade?></span> <?=$pedido->categoria->descricao?>
                    - <?=$pedido->medida->descricao?>
                </h5>
                <p class="card-text" style="padding-left:30px; margin:0; font-size:20px;">
                    <?=$sabores?>
                </p>
                <!-- <p class="card-text" style="padding:0; margin:0; text-align:right">
                    R$ <?= number_format($d->valor_unitario, 2, ',', '.') ?>
                </p> -->
                <p class="card-text" style="padding-left:30px; margin:0; font-size:20px; color:red;">
                    <?= $d->produto_descricao?>
                </p>
            </div>
        </div>
        <?php
            }
        ?>
        </div>
    <?php
    }
    ?>
    </div>
</div>