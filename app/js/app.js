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

PageClose = (pg = 1) => {
    pags = [];
    $("close").each(function(){
        pags.push($(this).attr("chave"));
    });
    //alert('pages:'+pags+"\n\nPG:"+pg);
    for(i=1; i<=pg;i++){
        pos = ((pags.length) - i);
        eval("FecharPopUp"+pags[pos]+"();");
    }
}


RenovaSessao = () =>{

    var AppPedido = window.localStorage.getItem('AppPedido');
    var AppVenda = window.localStorage.getItem('AppVenda');
    var AppCliente = window.localStorage.getItem('AppCliente');

    if(AppPedido == 'undefined' || AppPedido == null) AppPedido = '';
    if(AppVenda == 'undefined' || AppVenda == null) AppVenda = '';
    if(AppCliente == 'undefined' || AppCliente == null) AppCliente = '';

    $.ajax({
        url:"src/cliente/sessao.php",
        type:"POST",
        data:{
            AppPedido,
            AppVenda,
            AppCliente
        },
        success:function(dados){
            $("body").append(dados);
        },
        error:function(){
            //alert('erro');
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