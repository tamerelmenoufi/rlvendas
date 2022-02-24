<?php
    //Config
    $ConfTitulo = 'Mesas';
    $UrlScript = 'mesas/';
    //Config ----------

    function getSituacao()
    {
        return [
            '1' => 'Liberado',
            '0' => 'Bloqueado',
        ];
    }