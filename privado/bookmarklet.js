// bruno borges paschoalinoto
// bookmarklet desenvolvido para extrair os dados da fuvest

(function() {
  // algoritmo de compressão LZString, minificado pra caber em ~1.5 kB
  // thanks, @pieroxy
  const _compress = function(o,r,n){if(null==o)return"";var e,t,i,s={},p={},u="",c="",a="",l=2,f=3,h=2,d=[],m=0,v=0;for(i=0;i<o.length;i+=1)if(u=o.charAt(i),Object.prototype.hasOwnProperty.call(s,u)||(s[u]=f++,p[u]=!0),c=a+u,Object.prototype.hasOwnProperty.call(s,c))a=c;else{if(Object.prototype.hasOwnProperty.call(p,a)){if(a.charCodeAt(0)<256){for(e=0;h>e;e++)m<<=1,v==r-1?(v=0,d.push(n(m)),m=0):v++;for(t=a.charCodeAt(0),e=0;8>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1}else{for(t=1,e=0;h>e;e++)m=m<<1|t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t=0;for(t=a.charCodeAt(0),e=0;16>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1}l--,0==l&&(l=Math.pow(2,h),h++),delete p[a]}else for(t=s[a],e=0;h>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1;l--,0==l&&(l=Math.pow(2,h),h++),s[c]=f++,a=String(u)}if(""!==a){if(Object.prototype.hasOwnProperty.call(p,a)){if(a.charCodeAt(0)<256){for(e=0;h>e;e++)m<<=1,v==r-1?(v=0,d.push(n(m)),m=0):v++;for(t=a.charCodeAt(0),e=0;8>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1}else{for(t=1,e=0;h>e;e++)m=m<<1|t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t=0;for(t=a.charCodeAt(0),e=0;16>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1}l--,0==l&&(l=Math.pow(2,h),h++),delete p[a]}else for(t=s[a],e=0;h>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1;l--,0==l&&(l=Math.pow(2,h),h++)}for(t=2,e=0;h>e;e++)m=m<<1|1&t,v==r-1?(v=0,d.push(n(m)),m=0):v++,t>>=1;for(;;){if(m<<=1,v==r-1){d.push(n(m));break}v++}return d.join("")}
  const n = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  function comprimir(o) {
    let b64 = function(o){if(null==o)return"";var r=_compress(o,6,function(o){return n.charAt(o)});switch(r.length%4){default:case 0:return r;case 1:return r+"===";case 2:return r+"==";case 3:return r+"="}}(o);
    return b64.replace(/\+/g, "-").replace(/\//g, "_").replace(/=/g, "!");
  }
  // a motivação? uma fuvest 2200 octetos. comprimindo, uns 700.
  // eu não quero estourar o limite de octetos em URLs tão cedo...

  // avisar o usuário
  function log(s) {
    document.writeln(s + "<br><br>");
  }

  // constantes
  const envios = {}
  const anos = [];
  const feitos = [];
  const versao = 1;
  let iid = null;
  let total = null;
  const desejados_meu = [
    "ano", "nome_carreira_vestibular", "hash_identifiacao", "nome_edital",
    "nome_curso_convocacao_chamada", "nome_resultado_situacao", "cpf_candidato",
    "numero_inscricao", "codigo_edital"
  ];

  log("Iniciando bookmarklet.js versão " + versao + ".");
  log("Fique calmo! Este script só vai extrair suas notas.");
  log("Nada será enviado ou salvo sem seu consentimento.");
  log("Primeiro, vamos ver quais edições da Fuvest você fez...");

  // primeiro, listar os editais
  $.ajax("servicos/edital/listar_meus_concursos").done(function(obj) {
    for (const edital of obj["resposta"]) {
      let m = edital["nome_edital"].match(/Vestibular FUVEST (\d{4})/);
      if (m) {
        let ano = m[1];
        envios[ano] = {
          "dados_edital": edital
        };
        anos.push(ano);
      }
    }
    log("Detectados: " + anos.join(", "));
    total = anos.length;
    // obter um edital por segundo pra não levantar suspeitas
    iid = setInterval(prox_edital, 1000);
  }).fail(function() {
    log("Impossível listar os editais inscritos!");
  });

  // obter cada nota
  function prox_edital() {
    let ano = anos.pop();
    if (!ano) {
      // cabô
      clearInterval(iid);
      log("Tudo pronto, vamos te redirecionar de volta para o site!");
      setTimeout(fuga, 1000);
      return;
    }
    let a = feitos.length + 1;
    let edital = envios[ano]["dados_edital"]
    log("Consultando sua situação no " + edital["nome_edital"] + "...");
    let req = $.ajax({
      type: "POST",
      url: "servicos/edital/consultar_edital",
      contentType: "application/json;charset=utf-8",
      dataType: "json",
      data: JSON.stringify({
        "p_hash_identificacao": edital["hash_identificacao"]
      })
    }).done(function(obj) {
      obj = obj["resposta"][0];
      for (const key in obj)
        if (desejados_meu.indexOf(key) < 0) delete obj[key];
      console.log(obj);
      envios[ano]["meu_edital"] = obj;
      if (obj["nome_resultado_situacao"].indexOf("Convocado") > -1) {
        puxar_nota(ano);
      }
    }).always(function() {
      feitos.push(ano);
    });
  }

  function puxar_nota(ano) {
    let cod = envios[ano]["dados_edital"]["codigo_edital"];
    let req = $.ajax({
      type: "POST",
      url: "servicos/inscricao/consultar_meu_resultado",
      contentType: "application/json;charset=utf-8",
      dataType: "json",
      data: JSON.stringify({
        "p_codigo_edital": cod
      })
    }).done(function(obj) {
      obj = obj["resposta"];
      envios[ano]["notas"] = obj;
      log("Notas para " + ano + " obtidas.");
    }).always(function() {
      feitos.push(ano);
      delete envios[ano]["dados_edital"];
    });
  }


  // tudo pronto, vamo fugir
  function fuga() {
    let qg = "//segredos.oisumida.rs/notas_fuvest_dev/contribua/confirma/";
    let j = JSON.stringify(envios);
    let enc = comprimir(j);
    let url = qg + "?v=" + versao + "&d=" + enc;
    let a = document.createElement("a");
    a.href = url;
    a.innerText = "Se você não for redirecionado, clique aqui.";
    document.body.appendChild(a);
    location.assign(url);
    console.log(envios);
  }

})();
