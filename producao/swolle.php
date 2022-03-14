<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<output></output>
<input type="text">


<script>

    // var ws;

    // WebSocket = function(){

    //     ws = new WebSocket("wss://websocket.yobom.com.br");
    //     input = document.querySelector('input');
    //     output = document.querySelector('output');

    //     ws.addEventListener('open', console.log);
    //     ws.addEventListener('message', console.log);
    //     ws.addEventListener('close', function(){
    //         setTimeout(function() { WebSocket(); }, 1000);
    //     });

    //     ws.addEventListener('message', message => {
    //         const dados = JSON.parse(message.data);
    //         if(dados.type === 'chat'){
    //             output.append('Outro: ' + dados.text, document.createElement('br'));
    //         }
    //     })

    //     input.addEventListener('keypress', e => {
    //         if(e.code === 'Enter'){
    //             const valor = input.value;
    //             output.append('Eu: ' + valor, document.createElement('br'));
    //             ws.send(valor);

    //             input.value = '';
    //         }
    //     });

    // }

    // WebSocket();






    // Socket Variable declaration
var mySocket;
const socketMessageListener = (event) => {

    const dados = JSON.parse(event.data);
    if(dados.type === 'chat'){
        output.append('Outro: ' + dados.text, document.createElement('br'));
    }


};

// Open
const socketOpenListener = (event) => {
   console.log('Connected');

};

// Closed
const socketCloseListener = (event) => {
   if (mySocket) {
      console.error('Disconnected.');
   }
   mySocket = new WebSocket('wss://websocket.yobom.com.br');

   input = document.querySelector('input');
   output = document.querySelector('output');

   mySocket.addEventListener('open', socketOpenListener);
   mySocket.addEventListener('message', socketMessageListener);
   mySocket.addEventListener('close', socketCloseListener);


   input.addEventListener('keypress', e => {
        if(e.code === 'Enter'){
            const valor = input.value;
            output.append('Eu: ' + valor, document.createElement('br'));
            mySocket.send(valor);

            input.value = '';
        }
    });


};
socketCloseListener();




</script>

</body>
</html>
