<?php

    include("../../../lib/includes.php");
?>

<div class="row">
    <div class="col">


    <h1>Estamos em <?php echo date('Y');?></h1>
        <p>Hoje é dia <strong><?php echo date('d / '); ?></strong>
            <?php echo date('m'); ?>
            agora são <?php echo date ('H'); ?>horas e
            <?php echo date('i');?> minutos.</p>

        <?php
            function linha($semana){
                echo "<tr>";
                for ($i = 0; $i <=6; $i++){
                    if(isset($semana[$i])){
                        echo "<td>{$semana[$i]}</td>";
                    } else{
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            function calendario(){
                $dia = 1;
                $semana = array();

                while($dia <= 31){
                    array_push($semana, $dia);
                    if(count($semana) == 7){
                        linha($semana);
                        $semana = array();
                    }
                    $dia++;
                }
                linha($semana);
            }
        ?>
        <table border="1">
            <tr>
                <th>Dom</th>
                <th>Seg</th>
                <th>Ter</th>
                <th>Qua</th>
                <th>Qui</th>
                <th>Sex</th>
                <th>Sáb</th>
                <?php calendario(); ?>
            </tr>
        </table>




    </div>
</div>

