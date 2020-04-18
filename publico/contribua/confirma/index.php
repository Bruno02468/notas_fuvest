<?php
// bruno borges paschoalinoto 2020

if (!isset($_GET["v"]) or !isset($_GET["d"])) {
	header("Location: ..");
	die();
}

function descomprimir($a) {
	$e = escapeshellarg($a);
	return shell_exec("../../../privado/decomp.js $e");
}

try {

	$atual = 1;
	$versao = $_GET["v"];
	$totalidade_json = descomprimir($_GET["d"]);
	$todos = json_decode($totalidade_json, true);
	$linhas = array();
	$db = new SQLite3("../../../privado/banco.db");
	$db->busyTimeout(3000);

	foreach ($todos as $ano => $fuv) {
		try {
			$n = $fuv["notas"];
			$cn = $fuv["meu_edital"]["nome_curso_convocacao_chamada"];
			if (!$cn) continue;
			$car = explode(" - ", $fuv["meu_edital"]["nome_carreira_vestibular"]);
			$cur = explode(" - ", $cn);
			preg_match("/(\d+). (.+)/u",
				$fuv["meu_edital"]["nome_resultado_situacao"], $mch);
			$linha = array(
				"gerais" => intval($n[0]["pontos_disciplina"]),
				"fase2" => array(
					floatval($n[1]["pontos_disciplina_decimal"]),
					floatval($n[2]["pontos_disciplina_decimal"])
				),
				"redacao" => floatval($n[4]["pontos_disciplina_decimal"]),
				"media" => floatval($n[3]["pontos_disciplina_decimal"]),
				"cod_carreira" => intval($car[0]),
				"nome_carreira" => $car[1],
				"cod_curso" => intval($cur[0]),
				"nome_curso" => $cur[1],
				"num_chamada" => intval($mch[1]),
				"tipo_chamada" => $mch[2],
				"ano" => intval($ano)
			);
			$cpf = $fuv["meu_edital"]["cpf_candidato"];
			$nc = $linha["cod_curso"];
			$linha["hash"] = hash_pbkdf2("sha512", "$cpf-$ano-$nc", "$ano", 100000);
			$h = $linha["hash"];
			$dup = $db->querySingle("SELECT hash FROM notas WHERE hash='$h';");
			if (!$dup) array_push($linhas, $linha);
		} catch (Throwable $t) {
			continue;
		}
	}

} catch (Throwable $t) {
	die("Foi encontrado um erro espetacular!<br>Se quiser ajudar, envie a URL e, "
		. "se conseguir, os conteúdos desta página para bruno@oisumida.rs!");
	var_dump($t);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-163820330-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-163820330-1');
		</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Confirme seus dados!">
    <meta name="author" content="Bruno Borges Paschoalinoto">
    <title>Confirmar contribuição</title>
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600"
    rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <link rel="stylesheet" href="../../estiloso.css">
  </head>
  <body>
    <div class="container center">
      <div class="row">
        <h2>Confirmar contribuição</h2>
        <h6>
					Obrigado por querer contribuir com sua nota!<br>
        </h6>
      </div>
			Seus dados foram extraídos com sucesso!<br>
			<br>
			Isso é <b>tudo</b> que vai ser salvo no nosso banco de dados, se você
			autorizar, claro:
			<br>
			<br>
			<table class="datable">
				<thead>
					<tr id="tha">
						<th>Nome da coluna</th>
					</tr>
				</thead>
				<tbody id="entry"></tbody>
			</table>
			<br>
			Uma vez enviados, os dados são anônimos e não serão removidos.
			<br>
			<br>
			<button onclick="concordo();">Concordo. Enviar!</button>
      <br>
      <br>
      <br>
      <br>
      <i>
        Se encontrar qualquer problema ou bug, <b>reporte</b> para
        <a href="mailto:bruno@oisumida.rs">bruno@oisumida.rs</a>
        ou via issue no GitHub.
        <br>
        Se você estiver interessado no código, ele é aberto sob a licença
        MIT, e está
        <a href="https://github.com/Bruno02468/notas_fuvest"
        target="_blank">num repositório do GitHub</a>!
        <br>
        <br>
        © 2020
        <a href="//oisumida.rs" target="_blank">Bruno Borges Paschoalinoto</a>
        <br>
        Alguns direitos reservados!
      </i>
      <br>
      <br>
		</div>
		<script>
			/* gerado automaticamente */
			let linhas = <?php echo json_encode($linhas); ?>;
		</script>
    <script src="confirma.js"></script>
  </body>
</html>
