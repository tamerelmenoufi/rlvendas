<?php

    include("../../lib/includes.php");
    $con = AppConnect('dawar');
    $conApi = AppConnect('information_schema');
    
    $query = "SELECT * FROM `COLUMNS` where TABLE_SCHEMA = 'dawar' and COLUMN_NAME != 'codigo' order by TABLE_NAME";
    $result = mysqli_query($conApi, $query);
    while($d = mysqli_fetch_object($result)){
        $Comando[$d->TABLE_NAME][] = $d->COLUMN_NAME;
        $tipo[$d->TABLE_NAME][$d->COLUMN_NAME] = $d->DATA_TYPE;
    }

    $Cmd = [];
    foreach($Comando as $ind => $val){
        $cmd = "CREATE TABLE IF NOT EXISTS {$ind} (";
        $campos = [];
        foreach($val as $i => $v){
            $cmd .= $v.(($tipo[$ind][$v] == 'bigint')?' BIGINT':' TEXT').", ";
            $campos[] = $v;
        }
        $cmd .= "codigo INTEGER PRIMARY KEY AUTOINCREMENT);";

        $Cmd[] = ['comando' => "DROP TABLE {$ind}"];
        $Cmd[] = ['comando' => $cmd];

        $query = "select * from {$ind} limit 1000";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $D = [];
            foreach($d as $i => $v){
                if($tipo[$ind][$i] == 'bigint' or $i == 'codigo'){
                    $D[] = str_replace("'", "`", $v);
                }else{
                    $D[] = "'".str_replace("'", "`", $v)."'";
                }
            }
            $Cmd[] = ['comando' => "REPLACE INTO $ind (codigo, ".implode(", ", $campos).") VALUES (".implode(", ",$D).")"];
        }            

    }

    echo json_encode($Cmd);
    //TESTE