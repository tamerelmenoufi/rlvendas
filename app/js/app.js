function AppComponentes(obj){
    $("object["+obj+"]").each(function(){



        comp = $(this).attr("componente");
        get = $(this).attr("get");
        post = $(this).attr("post");

        if(get){
            listGet = get.split('|');
            RetornoGet = '';
            for(i=0;i<listGet.length;i++){
                campos = listGet[i].split(',');
                $("form").append("<input type='hidden' name='"+campos[0]+"' value='"+campos[1]+"' />");
            }
        }

        if(post){
            listPost = post.split('|');
            for(i=0;i<listPost.length;i++){
                campos = listPost[i].split(',');
                $("form").append("<input type='hidden' name='"+campos[0]+"' value='"+campos[1]+"' />");
            }
        }

        AbreComponente(comp,$("form").serializeArray());

    });
}

AbreComponente = (opc, vetor) => {
    //console.log(vetor);
    $.ajax({
        url:"componentes/"+opc+".php",
        type:"POST",
        data:vetor,
        success:function(dados){
            $('object[componente="'+opc+'"]').html(dados);
            $("form").html('');
        }

    });
}



function Anima(local='wow', tipo='animated'){
    wow = new WOW(
        {
            boxClass:     local,      // animated element css class (default is wow)
            animateClass: tipo, // animation css class (default is animated)
            offset:       0,          // distance to the element when triggering the animation (default is 0)
            mobile:       true,       // trigger animations on mobile devices (default is true)
            live:         true,       // act on asynchronously loaded content (default is true)
            callback:     function(box) {
            // the callback is fired every time an animation is started
            // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null,    // optional scroll container selector, otherwise use window,
            resetAnimation: false,     // reset animation on end (default is true)
        }
    );
    wow.init();
}

Carregando = (opc) => {
    if(opc == 'none'){
        $(".Carregando").css("display","none");
    }else{
        $(".Carregando").css("display","block");
    }
    //RenovaSessao();
}

PageBack = () => {
    pags = [];
    $("close").each(function(){
        pags.push($(this).attr("chave"));
    });

    alert(pags);
}

PageClose = () => {
    pags = [];
    $("close").each(function(){
        pags.push($(this).attr("chave"));
    });
    pos = ((pags.length) - 1);
    //console.log(pags);
    //console.log([pos]);

    eval("FecharPopUp"+pags[pos]+"();")
}

CarrinhoOpc = (codigo, opc) => {


        $(".ms_barra_fundo_icone_sacola_up").css("display","none");
        $(".ms_barra_fundo_icone_sacola_down").css("display","none");

        $.ajax({
            url:"lib/includes/add_carrinho.php",
            data:{
                codigo,
                opc
            },
            success:function(retorno){
                //console.log(retorno);
                r = retorno.split('|');
                if(r[0] > 0){
                    $(".ms_barra_fundo_icone_sacola_up").css("display","none");
                    $(".ms_barra_fundo_icone_sacola_down").css("display","block");
                    $("span[valor_total]").html((r[1]*1).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                    $("body").attr("valor_total",(r[1]*1));
                }else{
                    $(".ms_barra_fundo_icone_sacola_up").css("display","none");
                    $(".ms_barra_fundo_icone_sacola_down").css("display","none");
                    $("span[valor_total]").html('');
                    $("body").attr("valor_total",'');
                }
                IconeCompras();
            }
        });

}

AlertaLogin = () => {

    $.ajax({
        url:"componentes/ms_popup.php",
        type:"POST",
        data:{
            local:"src/usuarios/index.php",
        },
        success:function(dados){
            //$(".ms_corpo").append("<div barra_busca_topo>"+dados+"</div>");
            $(".ms_corpo").append(dados);
        }
    });


}

IconeCompras = () => {
    if($("body").attr("valor_total")){
        var cor = 'green';
    }else{
        var cor = '#eee';
    }
    $(".ativa_carrinho").css('color',cor);
}

RenovaSessao = () =>{
    $.ajax({
        url:"lib/includes/sessao.php",
        type:"GET",
        data:{
            ms_cli_codigo
        },
        success:function(dados){
            //console.log($("body").attr("valor_total"));
            IconeCompras();
        }
    });
}

MensagemBack = (msg = 'Utilize os botões do aplicativo para navegar!') =>{
    $.alert({
        content:'<center>'+msg+'</center>',
        theme: "Material",
        type: 'green',
        title:false,
        buttons: {
            'OK': {
                text: 'OK',
                btnClass: 'btn-green',
                action: function(){

                }
            }
        }
    });
}


(function(window) {
    'use strict';

  var noback = {

      //globals
      version: '0.0.1',
      history_api : typeof history.pushState !== 'undefined',

      init:function(){
          window.location.hash = '#no-back';
          noback.configure();
      },

      hasChanged:function(){
          if (window.location.hash == '#no-back' ){
              window.location.hash = '#BLOQUEIO';
              //mostra mensagem que não pode usar o btn volta do browser
              //MensagemBack();
              PageClose();
          }
      },

      checkCompat: function(){
          if(window.addEventListener) {
              window.addEventListener("hashchange", noback.hasChanged, false);
          }else if (window.attachEvent) {
              window.attachEvent("onhashchange", noback.hasChanged);
          }else{
              window.onhashchange = noback.hasChanged;
          }
      },

      configure: function(){
          if ( window.location.hash == '#no-back' ) {
              if ( this.history_api ){
                  history.pushState(null, '', '#BLOQUEIO');
              }else{
                  window.location.hash = '#BLOQUEIO';
                  //mostra mensagem que não pode usar o btn volta do browser
                  //MensagemBack();
                  PageClose();
              }
          }
          noback.checkCompat();
          noback.hasChanged();
      }

      };

      // AMD support
      if (typeof define === 'function' && define.amd) {
          define( function() { return noback; } );
      }
      // For CommonJS and CommonJS-like
      else if (typeof module === 'object' && module.exports) {
          module.exports = noback;
      }
      else {
          window.noback = noback;
      }
      noback.init();
  }(window));


  /////////////////////CAMERA//////////////////////////

  function AtivarCamera(local){

        let scanner = new Instascan.Scanner(
            {
                video: document.getElementById(local)
            }
        );
        scanner.addListener('scan', function(content) {
            alert('Escaneou o conteudo: ' + content);
            window.open(content, "_blank");
        });
        Instascan.Camera.getCameras().then(cameras =>
        {
            if(cameras.length > 0){
                scanner.start(cameras[1]);
                console.error(cameras);
            } else {
                console.error("Não existe câmera no dispositivo!");
            }
        });

  }