<!DOCTYPE html>
<html>
  <head>
    <title>Instascan</title>
    <script type="text/javascript" src="<?= "camera.js"; ?>" ></script>
    <style>
        *{
            border: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            flex: 1;
        }
        body{
            border: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            background-color:#333;
        }
        #preview{
            position:fixed;
            top:0px;
            left:0px;
            width:100%;
            height: 100%;
        }
    </style>
  </head>
  <body>
    <video id="preview"></video>
    <script>

        function onScanSuccess(decodedText, decodedResult) {
            // Handle the scanned code as you like, for example:
            console.log(`Code matched = ${decodedText}`, decodedResult);
            $.alert(`Scan result: ${decodedText}`, decodedResult);
            html5QrcodeScanner.clear();
        }

        const formatsToSupport = [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.UPC_EAN_EXTENSION,
        ];
        const html5QrcodeScanner = new Html5QrcodeScanner(
        "preview",
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            formatsToSupport: formatsToSupport
        },
        /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess);

    </script>
 </body>
</html>