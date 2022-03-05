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
    <div id="preview"></div>
    <script>

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "preview", { fps: 10, qrbox: 250 });

        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            console.log(`Code matched = ${decodedText}`, decodedResult);
            alert(`Scan result: ${decodedText}`, decodedResult);
            // ...
            html5QrcodeScanner.clear();
            // ^ this will stop the scanner (video feed) and clear the scan area.
        }

        html5QrcodeScanner.render(onScanSuccess);


    </script>
 </body>
</html>