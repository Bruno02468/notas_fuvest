<?php
// bruno borges paschoalinoto 2020

$status = "nostatus";
$db = new SQLite3("../../../privado/banco.db");
$db->busyTimeout(3000);

// verificar se google recaptcha está atiado.
$sitekey = $db->querySingle("SELECT value FROM config WHERE "
  . "key=\"grecaptcha_sitekey\";");
$secretkey = $db->querySingle("SELECT value FROM config WHERE "
  . "key=\"grecaptcha_secretkey\";");
$grecaptcha_enabled = $sitekey && $secretkey;
$ip = $_SERVER["REMOTE_ADDR"];

// se recaptcha estiver ativo, verificar a resposta.
if ($grecaptcha_enabled and false) {
	if (!isset($_POST["grecaptcha_token"])) {
		$status = "grc_notoken";
	} else {
		$token = $_POST["grecaptcha_token"];
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$params = array(
			"secret" => $secretkey,
			"response" => $token,
			"remoteip" => $ip
		);
		$options = array(
			"http" => array(
				"header" => "Content-type: application/x-www-form-urlencoded\n",
				"method" => "POST",
				"content" => http_build_query($params)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if (!$result) $status = "grc_noresponse";
		else {
			$response = json_decode($result, true);
			if (!$response["success"]) $status = "grc_fail";
			else if ($response["action"] != "prelogin") $status = "grc_action";
			else if ($response["score"] < 0.5) $status = "grc_score";
		}
	}
}

$saida_json = "[]";

if ($status == "nostatus") {
	// beleza, agora só puxar os dados.
	if (!isset($_POST["ano"])) {
		header("Location: ..");
		die();
	}
	$aarg = escapeshellarg($_POST["ano"]);
	$larg = escapeshellarg($_POST["login"]);
	$parg = escapeshellarg($_POST["senha"]);
	$saida_json = shell_exec("../../../privado/extrator.py $aarg $larg $parg");
	$saida = json_decode($saida_json, true);

	if (!strlen($saida_json) or !count($saida)) $status = "empty_array";
	else {
		$status = "ok";
	}
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
			<div class="prehide" id="nostatus">
				Um erro desconhecido aconteceu...<br>
				<br>
				Se o erro persistir,
				<a href="mailto:bruno@oisumida.rs">notifique-nos</a>!
			</div>
			<div class="prehide" id="grc_noresponse">
				O Google não respondeu à nossa requisição para verificar se você é um
				humano.<br>
				<br>
				Como não podemos comprovar que você é um ser humano,
				<a href="..">tente novamente</a>.<br>
				<br>
				Se o erro persistir,
				<a href="mailto:bruno@oisumida.rs">notifique-nos</a>!
			</div>
			<div class="prehide" id="grc_notoken">
				Seu navegador não forneceu o token do Google reCAPTCHA v3.<br>
				<br>
				Como não podemos comprovar que você é um ser humano,
				<a href="..">tente novamente</a>.
			</div>
			<div class="prehide" id="grc_fail">
				O Google respondeu à nossa requisição para verificar se você é um
				humano, mas indicou uma falha indeterminada.<br>
				<br>
				Como não podemos comprovar que você é um ser humano,
				<a href="..">tente novamente</a>.<br>
				<br>
				Se o erro persistir,
				<a href="mailto:bruno@oisumida.rs">notifique-nos</a>!
			</div>
			<div class="prehide" id="grc_action">
				Seu navegador informou um valor incorreto ao Google reCAPTCHA v3.
				<br>
				Como não podemos comprovar que você é um ser humano,
				<a href="..">tente novamente</a>.<br>
				<br>
				Se o erro persistir,
				<a href="mailto:bruno@oisumida.rs">notifique-nos</a>!
			</div>
			<div class="prehide" id="grc_notoken">
				Numa escala de 0 (robô) a 1 (humano), o Google te deu uma pontuação de
				<?php if (isset($response) and isset($response["score"]))
					echo $response["score"];
				?>. Só aceitamos requisições com pontuações de 0.5 pra cima.<br>
				<br>
				Como não podemos comprovar que você é um ser humano,
				<a href="..">tente novamente</a>.
			</div>
			<div class="prehide" id="empty_array">
				O programa extrator não conseguiu puxar seus dados do site da Fuvest.
				<br>
				<br>
				Verifique se suas credenciais estão corretas e
				<a href="..">tente novamente</a>!<br>
				<br>
				Se você tiver <b>certeza</b> que estão certas e mesmo assim este erro
				persistir,
				<a href="mailto:bruno@oisumida.rs">notifique-nos</a>!
			</div>
			<div class="prehide" id="ok">
				Seus dados foram extraídos com sucesso!<br>
				<br>
				Isso é <b>tudo</b> que vai ser salvo no nosso banco de dados, se você
				autorizar, claro:
				<br>
				<br>
				<table class="datable">
					<thead>
						<tr>
							<th>Nome da coluna</th>
							<th>Valor armazenado</th>
						</tr>
					</thead>
					<tbody id="entry"></tbody>
				</table>
				<br>
				<br>
				<form method="POST" action="salvar">
				<?php if ($grecaptcha_enabled) { ?>
					<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $sitekey; ?>"></script>
					<script>
					grecaptcha.ready(function() {
						grecaptcha.execute("<?php echo $sitekey; ?>", {action: "postlogin"}).then(function(token) {
							document.getElementById("gv3_token").value = token;
						});
					});
					</script>
					<input type="hidden" value="" name="grecaptcha_token" id="gv3_token">
				<?php } ?>
				<br>
				<input type="submit" value="Ver meus dados e confirmar">
				</form>
			</div>
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
			let dados = <?php echo $saida_json; ?>;
			let status = <?php echo json_encode($status); ?>;
		</script>
    <script src="confirma.js"></script>
  </body>
</html>
