<?php

    $rede = new Rede;
    $rede->Ambiente = 'homologacao';
    $rede->PV = '19348375';
    $rede->TOKEN = '2b4e31d3a75b429c9ef5fdd02f2b5c59';

    echo    $rede->ConsultaCancelaTID('
                                    {
                                        "tid":"'.$_POST['tid'].'"
                                    }
                                    ');