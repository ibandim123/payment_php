<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/styles/global.css" />
  <title>Dashboard</title>
</head>

<?php

require_once '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['login']);
  $senha = trim($_POST['password']);

  $check_email = "SELECT id,nome,tipo_conta,creditos FROM usuarios WHERE email = ?";
  $user_info = $conn->prepare($check_email);
  $user_info->bind_param("s", $email);
  $user_info->execute();
  $user_info->store_result();

  if ($user_info->num_rows == 1) {
    $user_info->bind_result($id, $nome, $tipo_conta, $creditos);
    $user_info->fetch();
?>

    <body class="flex">
      <section class="flex drawn width-100 height-100 justify-content-center middle flex-column">
        <div class="drawn m-default p-default">
          <section class="flex flex-column drawn m-default p-default" alt="Dashboard" style="height: 100px; width: 400px;">
            <div>
              <p>Nome: <?php echo htmlspecialchars($nome) ?></p>
              <p>Saldo: <?php echo htmlspecialchars($creditos) ?></p>
              <p>Tipo de Conta: <?php echo htmlspecialchars($tipo_conta) ?></p>
            </div>
          </section>

          <section class="flex flex-column drawn m-default p-default" style="height: 200px; width: 400px;">
            <p>Ações: </p>
            <form method="post" action="../pages/payment.php">
              <input type="hidden" name="nome" value="<?php echo $nome; ?>">
              <input type="hidden" name="remetente" value="<?php echo $id; ?>">
              <input type="hidden" name="tipo_conta" value="<?php echo $tipo_conta; ?>">
              <input type="hidden" name="saldo" value="<?php echo $creditos; ?>">
              <p>Destinatário (email): <br></p>
              <input type="email" name="destinatario" />
              <p>Transferir: <br></p>
              <input type="number" name="valor" />
              <button type="submit">Enviar</button>
            </form>
          </section>

          <div class="flex drawn m-default p-default" style="height: 30px; width: 400px;">
            <a href="../../index.php"><button>Voltar</button></a>
          </div>
        </div>
      </section>
    </body>

  <?php
  } else {
  ?>

    <body>
      <h1>Usuário não existe em nossa base de dados</h1>
      <a href="../../index.php">Voltar</a>
    </body>
<?php
  }
}

?>



</html>