
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


Carregando = (opc) => {
    if(opc == 'none'){
        $(".Carregando").css("display","none");
    }else{
        $(".Carregando").css("display","block");
    }
    RenovaSessao();
}

PageBack = () => {
    pags = [];
    $("close").each(function(){
        pags.push($(this).attr("chave"));
    });
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


RenovaSessao = () =>{

    AppPedido = window.localStorage.getItem('AppPedido');
    Appvenda = window.localStorage.getItem('Appvenda');
    Appcliente = window.localStorage.getItem('AppCliente');

    $.ajax({
        url:"src/cliente/sessao.php",
        type:"POST",
        data:{
            AppPedido,
            Appvenda,
            Appcliente,
        },
        success:function(dados){

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
              window.location.hash = '#back';
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
                  history.pushState(null, '', '#back');
              }else{
                  window.location.hash = '#back';
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
