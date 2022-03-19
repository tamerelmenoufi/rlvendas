<?php

    $rede = new Rede;
    echo    $rede->Consulta('
                            {
                                "reference":"'.$_POST['reference'].'"
                            }
                            ');