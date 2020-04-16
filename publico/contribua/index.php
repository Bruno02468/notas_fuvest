<?php
// bruno borges paschoalinoto 2020

$db = new SQLite3("../../privado/banco.db");
$db->busyTimeout(3000);

$sitekey = $db->querySingle("SELECT value FROM config WHERE "
  . "key=\"grecaptcha_sitekey\";");
$secretkey = $db->querySingle("SELECT value FROM config WHERE "
  . "key=\"grecaptcha_secretkey\";");
$grecaptcha_enabled = $sitekey && $secretkey;


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
    <meta name="description" content="Contribua com sua nota!">
    <meta name="author" content="Bruno Borges Paschoalinoto">
    <title>Contribua com sua nota!</title>
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600"
    rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" type="text/css"
    href="//cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <link rel="stylesheet" href="../estiloso.css">
  </head>
  <body>
    <div class="container center">
      <div class="row">
        <h2>Contribua com sua nota!</h2>
        <h6>
          Sabia que a sua performance nas provas da Fuvest, apesar de ser uma
          informação pessoal que parece só ter utilidade pra você, também pode
          ajudar as futuras gerações de vestibulandos?<br>
          <br>
          Pois é, é muito importante ter uma ideia do que as pessoas que
          passaram no curso dos sonhos tiveram que fazer, pra se planejar,
          planejar seus estudos, definir prioridades...<br>
          <br>
          Se você foi aprovado em algum vestibular da Fuvest (2020 ou depois),
          você pode contribuir <i>anonimamente</i> com sua nota 
          aqui! É automático, seguro, e ajuda seus futuros calouros!<br>
          <br>
          Mesmo que seu curso já tenha contribuições, quanto mais notas, melhor.
          <br>
					<hr>
          <strong>
            Se você já enviou sua nota, mas foi aprovado em outro curso numa
            chamada subsequente, você pode enviar essa outra aprovação também!
            <br>
            <br>
            Nós não podemos pegar a outra aprovação automaticamente, pois não
            armazenamos sua senha nem nenhum dado pessoal.
						<hr>
          </strong>
        </h6>
      </div>
			Para contribuir com sua nota, só informe suas credenciais abaixo.<br>
			<br>
			Relaxa, você <b>vai</b> poder rever <b>todas</b> as informações que
			serão salvas antes de confirmar!<br>
      <br>
      <br>
			<form action="confirma/" method="POST">
				<table class="formtab">
					<tbody>
						<tr>
							<td>Ano de aprovação:</td>
							<td>
								<select name="ano">
									<option value="teste">Teste</option>
									<option value="2020">2020</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Login do app.fuvest.br:</td>
							<td><input type="text" name="login"></td>
						</tr>
						<tr>
							<td>Senha do app.fuvest.br:</td>
							<td><input type="password" name="senha"></td>
						</tr>
					</tbody>
				</table>
<?php if ($grecaptcha_enabled) { ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $sitekey; ?>"></script>
        <script>
        grecaptcha.ready(function() {
          grecaptcha.execute("<?php echo $sitekey; ?>", {action: "prelogin"}).then(function(token) {
            document.getElementById("gv3_token").value = token;
          });
        });
        </script>
        <input type="hidden" value="" name="grecaptcha_token" id="gv3_token">
<?php } ?>
			<br>
			<input type="submit" value="Ver meus dados e confirmar">
			</form>
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
  </body>
</html>
