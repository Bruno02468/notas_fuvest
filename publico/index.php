<?php
// bruno borges paschoalinoto 2020

$db = new SQLite3("../privado/banco.db");
$db->busyTimeout(3000);

$contribs = $db->querySingle("SELECT COUNT(*) FROM notas;");

function fetchAll($r) {
	$a = array();
	while ($row = $r->fetchArray()) array_push($a, $row);
	return $a;
}

$carreiras = fetchAll($db->query("SELECT cod_carreira, nome_carreira, "
	. "COUNT(cod_carreira) as total_carreira FROM notas GROUP BY cod_carreira "
	. "ORDER BY cod_carreira;"));

$cursos = fetchAll($db->query("SELECT cod_curso, nome_curso, "
	. "COUNT(cod_curso) as total_curso FROM notas GROUP BY cod_curso "
	. "ORDER BY cod_curso;"));

$pares = fetchAll($db->query("SELECT DISTINCT cod_curso, cod_carreira FROM notas "
	. "ORDER BY cod_carreira, cod_curso;"));

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
    <meta name="description" content="Notas de aprovados na Fuvest">
    <meta name="author" content="Bruno Borges Paschoalinoto">
    <title>Notas de aprovados na Fuvest</title>
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600"
    rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <link rel="stylesheet" href="estiloso.css">
  </head>
  <body>
    <div class="container center">
      <div class="row">
        <h2>Notas de aprovados da Fuvest</h2>
        <h6>
					Aqui, pessoas que foram aprovadas no vestibular da Fuvest podem
					compartilhar, de maneira segura e anônima, suas notas.<br>
					<br>
					Dessa maneira, vestibulandos podem ter uma ideia de que notas precisam
					tirar para serem aprovados no curso de sua escolha.<br>
					<br>
					Se você passou na Fuvest 2020 ou depois, considere
					<a href="contribua"> contribuir anonimamente com sua nota</a>!
					Nenhum dado pessoal seu é armazenado, apenas as notas, os códigos de
					carreira e curso, e a chamada.<br>
					<br>
					Na dúvida, todo este site é código aberto, disponível no
					<a href="//github.com/Bruno02468/notas_fuvest">GitHub</a>.
					Já temos <?php echo $contribs; ?> notas armazenadas!
        </h6>
      </div>
      <br>
			Para ver as notas, especifique uma carreira e, opcionalmente, um curso:
			<br>
			<br>
			<select id="carreira" onchange="atualizar_select();"></select><br>
			<br>
			<select id="curso"></select>
      <br>
      <br>
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
			let carreiras = <?php echo json_encode($carreiras); ?>;
			let cursos = <?php echo json_encode($cursos); ?>;
			let pares = <?php echo json_encode($pares); ?>;
		</script>
    <script src="home.js"></script>
  </body>
</html>
