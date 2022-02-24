<?php
const DATA = 'd/m/Y';
const DATA_HMS = 'd/m/Y H:i:s';
const DATA_HM = 'd/m/Y H:i';
const HORA_MINUTO = 'H:i';

function formata_datahora($datahora, $formato = null)
{
    if (!$formato) $formato = 'd/m/Y H:i:s';

    if ($datahora == 0) return '(Não definido)';

    return date($formato, strtotime($datahora));
}