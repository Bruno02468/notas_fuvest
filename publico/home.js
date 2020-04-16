// bruno borges paschoalinoto 2020

const sel_carreiras = document.getElementById("carreira");
const sel_cursos = document.getElementById("carreira");

// preparar o select de carreiras
for (const carreira of carreiras) {
  let opt = document.createElement("option");
  opt.value = carreira["cod_carreira"];
  opt.innerText = carreira["cod_carreira"] + " - " + carreira["nome_carreira"]
    + " (" + carreiras["total_carreira"] + " notas)";
  sel_carreiras.appendChild(opt);
}

// preparar a relação curso-carreira
const cpc = {};
for (const par of pares) {
  let car = par[0];
  let cur = par[1];
  if (!cpc.hasOwnProperty(car)) cpc[car] = [];
  if (cpc[car].indexOf(cur) < 0) cpc[car].push(cur);
}

function atualizar_select() {
  let carreira = sel_carreiras.value;
  sel_cursos.innerHTML = "";
  let opt_all = document.createElement("option");
  opt_all.value = "todos";
  otp_all.innerText = "<todos os cursos>";
  for (const curso of cursos) {
    if (cpc[carreira].indexOf(curso["cod_curso"]) < 0) continue;
    let opt = document.createElement("option");
    opt.value = curso["cod_curso"];
    opt.innerText = curso["cod_curso"] + " - " + curso["nome_curso"]
      + " (" + cursos["total_curso"] + " notas)";
    sel_cursos.appendChild(opt);
  }
}
