<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./src/assets/styles/global.css" />
  <title>Document</title>
</head>

<body class="flex">
  <section class="flex drawn width-100 height-100 justify-content-center middle" alt="cadastro de usuário">
    <div class="flex drawn m-30 p-30 flex-column" style="max-height: 500px; max-width: 700px;">
      <div class="flex drawn width-100 flex-column m-default ">
        <h1>Bem vindo ao Payment Logistic's.</h1>
      </div>

      <form class="flex drawn flex-column m-default p-default" action="src/pages/dashboard.php" method="post">
        <p>Fazer Login</p>
        <div class="flex drawn space-default-input">
          <label>Login: <br></label>
          <input type="text" name="login" />
        </div>
        <div class="flex drawn  space-default-input">
          <label>Senha: <br></label>
          <input type="password" name="password" />
        </div>
        <div class="flex drawn p-default space-default-input">
          <button type="submit">Login</button>
        </div>
      </form>

      <div class="flex drawn p-default m-default space-default-input">
        <small><a href="src/pages/register.php">Criar conta</a></small>
      </div>
    </div>
  </section>
</body>

</html>