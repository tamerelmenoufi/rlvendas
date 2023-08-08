<?php

  if(!$_POST['id']) exit();

  include("../../includes.php");

    $PIX = new MercadoPago;

    $retorno = $PIX->ObterPagamento($_POST['id']);

    $dados = json_decode($retorno);

    $operadora_id = $dados->id;
    $forma_pagamento = $dados->payment_method_id;
    $operadora_situacao = $dados->status;
    $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
    $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;

    if($operadora_id){

      if($operadora_situacao == 'approved'){
        $campos = " situacao = 'p', ";
      }

      $q = "insert into status_venda set
      venda = (select codigo from vendas where operadora_id = '{$operadora_id}'),
      operadora = 'mercado_pago',
      tipo = 'pix',
      data = NOW(),
      retorno = '{$retorno}'";
      mysqli_query($con, $q);

      $q = "update vendas set
                    forma_pagamento = '{$forma_pagamento}',
                    operadora_situacao = '{$operadora_situacao}',
                    {$campos}
                    operadora_retorno = '{$retorno}'
              where operadora_id = '{$operadora_id}'
              ";
      mysqli_query($con, $q);

    }
