<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <section alt="cadastro de usuário">
    <div>
      <h1>Bem vindo ao Payment Logistic's.</h1>
      <p>Fazer Login</p>

      <form action="src/pages/dashboard.php" method="post">
        <div>
          <label>Login: <br></label>
          <input type="text" name="login" />
        </div>
        <div>
          <label>Senha: <br></label>
          <input type="password" name="password" />
        </div>
        <div>
          <button type="submit">Login</button>
        </div>
      </form>

      <div>
        <small><a href="src/pages/register.php">Criar conta</a></small>
      </div>
    </div>
  </section>
</body>

</html>