// bruno borges paschoalinoto 2020

let tabela = document.getElementById("entry");
let tha = document.getElementById("tha");
for (const l of linhas) {
  let th = document.createElement("th");
  th.innerText = "Valor armazenado para " + l["ano"];
  tha.appendChild(th);
}

if (linhas.length) {
  for (let key in linhas[0]) {
    if (!linhas[0].hasOwnProperty(key)) continue;
    let tr = document.createElement("tr");
    let ktd = document.createElement("td");
    tr.appendChild(ktd);
    for (const l of linhas) {
      if (!l.hasOwnProperty(key)) continue;
      let vtd = document.createElement("td");
      if (key == "hash")
        l[key] = l[key].slice(0, 8) + "[...]" + l[key].slice(-8);
      ktd.innerText = key;
      vtd.innerText = JSON.stringify(l[key]);
      tr.appendChild(vtd);
    }
    tabela.appendChild(tr);
  }
} else {
  location.assign("../oops/");
}
