<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
</head>

<?php

require_once '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['login']);
  $senha = trim($_POST['password']);

  # Verificação se o usuário existe.
  $check_email = "SELECT id,nome,creditos FROM usuarios WHERE email = ?";
  $user_info = $conn->prepare($check_email);
  $user_info->bind_param("s", $email);
  $user_info->execute();
  $user_info->store_result();

  if ($user_info->num_rows > 1) {
    $user_info->bind_result($id, $nome, $creditos);
    $user_info->fetch();
    print_r("<section>");
    print_r("<div> Nome: " . $nome . "</div>");
    print_r("<div> Saldo: " . $creditos . "</div>");
    print_r("</section>");
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