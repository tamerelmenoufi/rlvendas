<?php
    //Config
    $ConfTitulo = 'Pagamentos';
    $UrlScript = 'pagamentos/';
    //Config ----------

    function getSituacao()
    {
        return [
            '1' => 'Liberado',
            '0' => 'Bloqueado',
        ];
    }