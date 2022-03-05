<!DOCTYPE html>
<html>
  <head>
    <title></title>
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
    <div id="preview"></div>
    <script>


        const html5QrCode = new Html5Qrcode("preview");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {

            /* handle success */
            //console.log(`Code matched = ${decodedText}`, decodedResult);
            //alert(`Scan result: ${decodedText}`, decodedResult);
            //html5QrCode.clear();

            html5QrCode.stop().then((ignore) => {
                // QR Code scanning is stopped.
                window.parent.LeituraCamera(decodedText);
            }).catch((err) => {
                // Stop failed, handle it.

            });


            // ...

        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        // If you want to prefer front camera
        //html5QrCode.start({ facingMode: "user" }, config, qrCodeSuccessCallback);

        // If you want to prefer back camera
        //html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);

        // Select front camera or fail with `OverconstrainedError`.
        //html5QrCode.start({ facingMode: { exact: "user"} }, config, qrCodeSuccessCallback);

        // Select back camera or fail with `OverconstrainedError`.
        html5QrCode.start({ facingMode: { exact: "environment"} }, config, qrCodeSuccessCallback);



    </script>
 </body>
</html>