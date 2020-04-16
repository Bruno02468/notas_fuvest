// bruno borges paschoalinoto 2020

document.getElementById(status).style.display = "block";

if (status == "ok") {
  let tabela = document.getElementById("entry");
  for (let key in dados) {
    if (!dados.hasOwnProperty(key)) continue;
    let tr = document.createElement("tr");
    let ktd = document.createElement("td");
    let vtd = document.createElement("td");
    if (key == "hash" || key == "salt")
      dados[key] = dados[key].slice(0, 16) + "[...]" + dados[key].slice(-16);
    ktd.innerText = key;
    vtd.innerText = JSON.stringify(dados[key]);
    tr.appendChild(ktd);
    tr.appendChild(vtd);
    tabela.appendChild(tr);
  }
}
