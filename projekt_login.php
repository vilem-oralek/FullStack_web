<!DOCTYPE html>
<html>
<head>
  <title>project x</title>
  <link rel="stylesheet" href="projekt_login.css">
  <?php include 'conn.php';?>
  <script>
    // funkce alertu
    function ForgottenPassword() {
        alert("Pro změnu hesla kontaktujte administrátora");
    }
</script>
</head>
<body>

  <div class="login-container">
    <a href="index.php" class="hyperlink">
    <div class="logo-container">
      <img src="rezervuj.png" alt="logo" class="logo">
      <h1>Rezervujsi</h1>
    </div>
    </a>
    <form action="login.php" method="POST">
        <label for="email">E-mail</label> <br>
        <input type="email" id="email" name="email" class="login-box" placeholder="rezervujsi@gmail.com">
        <br>
        <label for="password">Heslo</label> <br>
        <input type="password" id="password" name="password" class="login-box">
        <input type="submit" class="login-button" value="Přihlásit se">
        <br>
        <a href="" style="float: left;" onclick="ForgottenPassword()">Zapomenuté heslo</a>
        <a href="projekt_register.php" style="float: right;">přejít na registraci</a>
    </form>
  </div>
</body>
</html>

