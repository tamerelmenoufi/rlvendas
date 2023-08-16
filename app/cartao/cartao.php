<?php
    include("../../lib/includes.php");

    echo $query = "select * from vendas where codigo = '{$_SESSION['AppVenda']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $valor_pago = "select sum(retorno->>'$.transaction_amount') from status_venda where venda = '{$d->codigo}' and retorno->>'$.status' = 'approved'";
    list($valor_pago) = mysqli_fetch_row(mysqli_query($con, $valor_pago));

    $pedido = str_pad($d->codigo, 6, "0", STR_PAD_LEFT);



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#990002"/>
    <title>APP</title>
    <?php include("../../lib/header.php"); ?>
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/app.css">


</head>
<body>

  <script src="https://sdk.mercadopago.com/js/v2"></script>


<script>
  const mp = new MercadoPago('<?=$cYb['mercado_pago']['producao']['PUBLIC-KEY']?>');
</script>



<style>
  </style>
  <form id="form-checkout" class="p-3">
    <h5>Dados do Cartão</h5>
      <div class="row mb-2">
        <div class="col">
          <progress value="0" class="w-100">Carregando...</progress>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col">
          <div id="form-checkout__cardNumber" class="form-control"></div>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col">
          <div id="form-checkout__expirationDate" class="form-control"></div>
        </div>
        <div class="col">
          <div id="form-checkout__securityCode" class="form-control"></div>
        </div>
      </div>


      <div class="row mb-2">
        <div class="col">
          <input type="text" id="form-checkout__cardholderName" class="form-control" />
        </div>
      </div>

      <div class="row mb-2">
        <div class="col">
          <select id="form-checkout__issuer" class="form-control"></select>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col">
          <select id="form-checkout__installments" class="form-control"></select>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col">
          <select id="form-checkout__identificationType" class="form-control"></select>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col">
          <input type="text" id="form-checkout__identificationNumber" class="form-control" />
        </div>
        <div class="col">
          <input type="email" id="form-checkout__cardholderEmail" class="form-control" />
        </div>
      </div>
      <div class="row">
        <div class="col">
          <button type="submit" id="form-checkout__submit" class="btn btn-success w-100">Pagar</button>
        </div>
      </div>
  </form>


<script>

    const cardForm = mp.cardForm({
      amount: "<?=($d->total - $valor_pago)?>",
      iframe: true,
      form: {
        id: "form-checkout",
        cardNumber: {
          id: "form-checkout__cardNumber",
          placeholder: "Número do cartão",
        },
        expirationDate: {
          id: "form-checkout__expirationDate",
          placeholder: "MM/YY",
        },
        securityCode: {
          id: "form-checkout__securityCode",
          placeholder: "Código de segurança",
        },
        cardholderName: {
          id: "form-checkout__cardholderName",
          placeholder: "Titular do cartão",
        },
        issuer: {
          id: "form-checkout__issuer",
          placeholder: "Banco emissor",
        },
        installments: {
          id: "form-checkout__installments",
          placeholder: "Parcelas",
        },
        identificationType: {
          id: "form-checkout__identificationType",
          placeholder: "Tipo de documento",
        },
        identificationNumber: {
          id: "form-checkout__identificationNumber",
          placeholder: "Número do documento",
        },
        cardholderEmail: {
          id: "form-checkout__cardholderEmail",
          placeholder: "E-mail",
        },
      },
      callbacks: {
        onFormMounted: error => {
          if (error) return console.log("Form Mounted handling error: ", error);
          console.log("Form mounted");
        },
        onSubmit: event => {
          event.preventDefault();

          const {
            paymentMethodId: payment_method_id,
            issuerId: issuer_id,
            cardholderEmail: email,
            amount,
            token,
            installments,
            identificationNumber,
            identificationType,
          } = cardForm.getCardFormData();

          fetch("/cartao/pagar.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              token,
              issuer_id,
              payment_method_id,
              transaction_amount: Number(amount),
              installments: Number(installments),
              description: "Venda <?=$pedido?> - APP Yobom",
              payer: {
                email,
                identification: {
                  type: identificationType,
                  number: identificationNumber,
                },
              },
            }),
          });
          parent.payConfirm();

        },
        onFetching: (resource) => {
          console.log("Fetching resource: ", resource);

          // Animate progress bar
          const progressBar = document.querySelector(".progress-bar");
          progressBar.removeAttribute("value");

          return () => {
            progressBar.setAttribute("value", "0");
          };
        }
      },
    });

</script>

</body>
</html>