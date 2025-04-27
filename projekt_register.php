<!DOCTYPE html>
<html>
<head>
  <title>project x</title>
  <link rel="stylesheet" href="projekt_register.css">
  <?php include 'conn.php';?>
</head>
<body>

  <div class="login-container">
    <a href="index.php" class="hyperlink">
    <div class="logo-container">
      <img src="rezervuj.png" alt="logo" class="logo">
      <h1>Rezervujsi</h1>
    </div>
    </a>
    <form action="register.php" method="POST">
        <label for="jmeno">Jméno a Příjmení</label> <br>
        <input type="text" id="jmeno" name="jmeno" class="login-box" placeholder="Petr Novotný">
        <br>
        <label for="email">E-mail</label> <br>
        <input type="email" id="email" name="email" class="login-box" placeholder="rezervujsi@gmail.com">
        <br>
        <label for="telefon">Tel. číslo</label> <br>
        <input type="tel" id="telefon" name="telefon" class="login-box" placeholder="123456789" pattern="[0-9]{9}">
        <br>
        <label for="password">Heslo</label> <br>
        <input type="password" id="password" name="password" class="login-box">
        <br>
        <label for="password_confirm">Heslo znovu</label> <br>
        <input type="password" id="password_confirm" name="password_confirm" class="login-box">
        <br>
        <input type="submit" class="register-button" value="Registrovat se">
        <br>
        <a href="projekt_login.php" style="float: right">Přejít na přihlášení</a>
    </form>
  </div>
</body>
</html>
