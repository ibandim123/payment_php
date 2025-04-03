<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/styles/global.css" />
  <title>Cadastro de Usuário</title>
</head>

<body class="flex">
  <section class="flex drawn width-100 height-100 justify-content-center middle flex-column">
    <div class="flex flex-column drawn m-default p-default" style="max-height: 700px; max-width: 500px;">
      <h1 class="flex drawn m-default p-default">Criar nova conta</h1>
      <form class="flex drawn m-default p-default flex-column" style="height: 500px; width: 300px;" method="post" action="">
        <div class="flex drawn space-default-input flex-column">
          <label>Nome Completo:</label>
          <input type="text" name="nome_completo" id="nome_completo" />
        </div>

        <fieldset class="flex drawn space-default-input flex-column">
          <legend>tipo de conta</legend>
          <div>
            <input type="radio" name="tipo_conta" value="pessoa" />
            <label for="tipo_conta">Pessoa</label>
          </div>

          <div>
            <input type="radio" name="tipo_conta" value="lojista" />
            <label for="tipo_conta">Lojista</label>
          </div>

        </fieldset>

        <div class="flex drawn space-default-input flex-column">
          <label>CPF/CNPJ: </label>
          <input type="text" name="cpf_cnpj" id="cpf_cnpj" />
        </div>

        <div class="flex drawn space-default-input flex-column">
          <label>e-mail:</label>
          <input type="email" name="email" id="email" />
        </div>

        <div class="flex drawn space-default-input flex-column">
          <label for="senha">senha: </label>
          <input type="password" name="senha" id="senha">
        </div>

        <div class="flex drawn space-default-input gap">
          <button type="submit">Cadastrar</button>
          <a href="../../index.php"><button type="button">Voltar</button></a>
        </div>
      </form>
    </div>
  </section>
</body>

</html>

<?php

require_once '../database.php';
# validar os dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $nome_completo = trim($_POST['nome_completo']);
  $tipo_conta = $_POST['tipo_conta'] ?? '';
  $cpf_cnpj = trim($_POST['cpf_cnpj']);
  $email = trim($_POST['email']);
  $senha = trim($_POST['senha']);

  $erros = [];

  if (empty($nome_completo)) {
    $erros[] = "O nome completo é obrigatório.";
  }

  if (!preg_match("/^[0-9]{11,14}$/", $cpf_cnpj)) {
    $erros[] = "CPF/CNPJ inválido.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "E-mail inválido.";
  }

  if (!empty($erros)) {
    foreach ($erros as $erro) {
      echo "<p style='color: red;'>$erro</p>";
    }
  } else {

    $check_cpf_cpnj = "SELECT id FROM usuarios WHERE cpf_cnpj = ?";
    $cpf_cpnj_check = $conn->prepare($check_cpf_cpnj);
    $cpf_cpnj_check->bind_param("s", $cpf_cnpj);
    $cpf_cpnj_check->execute();
    $cpf_cpnj_check->store_result();

    if ($cpf_cpnj_check->num_rows > 0) {
      echo "<p style='color: red;'>CPF/CPNJ já cadastrado</p>";
    } else {
      $check_email = "SELECT id FROM usuarios WHERE email = ?";
      $email_check = $conn->prepare($check_email);
      $email_check->bind_param("s", $email);
      $email_check->execute();
      $email_check->store_result();

      if ($email_check->num_rows > 0) {
        echo "<p style='color: red;'>Email já cadastrado</p>";
      } else {
        $hashed_senha = password_hash($senha, PASSWORD_DEFAULT);
        $creditos = 10;

        $sql = "INSERT INTO usuarios (nome, tipo_conta, cpf_cnpj, email, senha, creditos) VALUES (?, ?, ?, ?, ?, ?)";
        $sendInfo = $conn->prepare($sql);
        $sendInfo->bind_param("ssssss", $nome_completo, $tipo_conta, $cpf_cnpj, $email, $hashed_senha, $creditos);

        if ($sendInfo->execute()) {
          echo "<p style='color: green;'>Cadastro realizado com sucesso!</p>";
        } else {
          echo "<p style='color: red;'>Erro ao cadastrar usuário: " . $sendInfo->error . "</p>";
        }
      }
    }
  }
}
