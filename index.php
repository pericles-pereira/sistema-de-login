<?php
// Conexão
require_once 'db_connect.php';

// Sessão
session_start();

// Botão enviar
if(isset($_POST['btn-entrar'])):
	$erros = array();
	// Esses filtros escape impedem ataques hacker ao banco de dados ao inputar comandos como o 
	// 105 OR 1=1
	// 1; DROP TABLE tabela1
	$login = mysqli_escape_string($connect, $_POST['login']);
	$senha = mysqli_escape_string($connect, $_POST['senha']);

	if(empty($login) or empty($senha)):
		$erros[] = "<li> O campo login/senha precisa ser preenchido </li>";
	else:
		$sql = "SELECT login FROM usuarios WHERE login = '$login'";
		$resultado = mysqli_query($connect, $sql);

		if(mysqli_num_rows($resultado) > 0):
			$senha = md5($senha);
			$sql = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha'";
			$resultado = mysqli_query($connect, $sql);

			if(mysqli_num_rows($resultado) == 1):
				$dados = mysqli_fetch_array($resultado);
				mysqli_close($connect);
				$_SESSION['logado'] = true;
				$_SESSION['id_usuario'] = $dados['id'];
				header('Location: home.php');
			else:
				$erros[] = "<li> Usuário e senha não conferem </li>";
			endif;

		else:
			$erros[] = "<li> Usuário inexistente</li> ";
		endif;

	endif;

endif;
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
</head>
<body>

	<h1> Login </h1>
	<?php
	if(!empty($erros)):
		foreach ($erros as $erro):
			echo $erro;
		endforeach;
	endif;
	?>
	<hr>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		Login: <input type="text" name="login"><br>
		Senha: <input type="password" name="senha">
		<button type="submit" name="btn-entrar"> Entrar </button>
	</form>

</body>
</html>