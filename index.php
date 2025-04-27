<!DOCTYPE html>
<html>
<head>
  <title>project x</title>
  <link rel="stylesheet" href="projekt_home.css">
  <link rel="stylesheet" href="header.css">
  <?php
  session_start();
  include 'conn.php';

  // pokud jsou zadaný obě data, zapíše je do proměných
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
      if (!empty($_GET['datum-prijezdu']) && !empty($_GET['datum-odjezdu'])) {
          $arrival_date = $_GET['datum-prijezdu'];
          $departure_date = $_GET['datum-odjezdu'];

          // ukládání dat do session z proměných
          $_SESSION['datum-prijezdu'] = $arrival_date;
          $_SESSION['datum-odjezdu'] = $departure_date;

          // předělání datumu na DateTime
          $arrival = new DateTime($arrival_date);
          $departure = new DateTime($departure_date);

          // Vypočítání počtu nocí (rozdíl mezi datumy)
          $interval = $arrival->diff($departure);
          $nights = $interval->days;

          // Uložení počtu nocí v session
          $_SESSION['nights'] = $nights;
      }
  }

  // Retrieve stored filter values
  $filters = isset($_SESSION['filters']) ? $_SESSION['filters'] : array();

  // Update session filters with current GET parameters
  $_SESSION['filters'] = $_GET;
  ?>
</head>
<body>
  <header>
    <div class="logo-container">
      <div class="logo">
        <img src="rezervuj.png" alt="logo" class="logo">
      </div>
      <h1 class="name">Rezervujsi.cz</h1>
    </div>
      <form style="width: 30%;" class="search-bar-conteiner" method="GET" action="index.php" style="display: flex;">
        <input type="text" placeholder="Search..." class="search-bar" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="search-button">Search</button>
      </form>
    <div>
      <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <a href="logout.php"><button class="button"> Odhlásit se </button></a>
      <?php else: ?>
        <a href="projekt_login.php"><button class="button"> Přihlásit </button></a>
        <a href="projekt_register.php"><button class="button"> Zaregistrovat </button></a>
      <?php endif; ?>
    </div>
  </header>
  <hr>
  <div class="two-div-container">
    <div class="filter">
      <h2>Filtry</h2>
      <hr style="border-width: 2px">
      <form method="GET" action="index.php">
        <label for="datum-prijezdu" style="font-size: 18px">Datum příjezdu</label>
        <br>
        <input type="date" id="datum-prijezdu" name="datum-prijezdu" style="padding: 5px" value="<?php echo isset($_GET['datum-prijezdu']) ? $_GET['datum-prijezdu'] : ''; // zajišťuje ponechání filtru po refreshnutí?>"><br>
        <br>
        <label for="datum-odjezdu" style="font-size: 18px">Datum odjezdu</label>
        <br>
        <input type="date" id="datum-odjezdu" name="datum-odjezdu" style="padding: 5px" value="<?php echo isset($_GET['datum-odjezdu']) ? $_GET['datum-odjezdu'] : ''; ?>"><br>
        <br>
        <p style="font-size: 18px; margin: 0;">Počet hvězdiček</p><br>
        <label for="pocet-hvezdicek-od" style="font-size: 18px">Od</label>
        <input id="pocet-hvezdicek-od" name="pocet-hvezdicek-od" type="number" style="width: 35px; margin-right: 20px;" max="5" min="0" value="<?php echo isset($_GET['pocet-hvezdicek-od']) ? $_GET['pocet-hvezdicek-od'] : ''; ?>"><br>
        <br>
        <label for="pocet-hvezdicek-do" style="font-size: 18px">Do</label>
        <input id="pocet-hvezdicek-do" name="pocet-hvezdicek-do" type="number" style="width: 35px; margin-right: 20px;" max="5" min="0" value="<?php echo isset($_GET['pocet-hvezdicek-do']) ? $_GET['pocet-hvezdicek-do'] : ''; ?>"><br>
        <br>
        <h3> <u>Vlastnosti ubytování</u></h3>
        <label for="WIFI" style="font-size: 18px">WiFi zdarma</label>
        <input id="WIFI" name="WIFI" type="checkbox" class="check-box" <?php echo isset($_GET['WIFI']) && $_GET['WIFI'] == 'on' ? 'checked' : ''; ?>> <br>
        <br>
        <label for="bazen" style="font-size: 18px">Bazén</label>
        <input id="bazen" name="bazen" type="checkbox" class="check-box" <?php echo isset($_GET['bazen']) && $_GET['bazen'] == 'on' ? 'checked' : ''; ?>> <br>
        <br>
        <label for="wellness" style="font-size: 18px">Wellness/Spa</label>
        <input id="wellness" name="wellness" type="checkbox" class="check-box" <?php echo isset($_GET['wellness']) && $_GET['wellness'] == 'on' ? 'checked' : ''; ?>> <br>
        <br>
        <label for="all_inclusive" style="font-size: 18px">All inclusive</label>
        <input id="all_inclusive" name="all_inclusive" type="checkbox" class="check-box"<?php echo isset($_GET['all_inclusive']) && $_GET['all_inclusive'] == 'on' ? 'checked' : ''; ?>> <br>
        <br>
        <label for="restaurace" style="font-size: 18px">Restaurace</label>
        <input id="restaurace" name="restaurace" type="checkbox" class="check-box" <?php echo isset($_GET['restaurace']) && $_GET['restaurace'] == 'on' ? 'checked' : ''; ?>> <br>
        <br>
        <input type="submit" class="button" style="margin-left: 100%" value="použít filtr">
        <a href="index.php" style="float: right; margin: 18px;">zrušit filtr</a>
      </form>
    </div>
    <div class="hotels-list">
      <?php
      // Fetchnutí dat z tabulky Hotel
      $sql = "SELECT * FROM Hotel WHERE 1=1";

      // Přidání funkčnosti filtrů (checkboxů) - z tabulky Hotel selectne věci které mají v tabulce zapsané "ano"
      if (isset($_GET['WIFI']) && $_GET['WIFI'] == 'on') {
        $sql .= " AND wifi = 'ano'";
      }
      if (isset($_GET['bazen']) && $_GET['bazen'] == 'on') {
        $sql .= " AND bazen = 'ano'";
      }
      if (isset($_GET['wellness']) && $_GET['wellness'] == 'on') {
        $sql .= " AND wellness = 'ano'";
      }
      if (isset($_GET['all_inclusive']) && $_GET['all_inclusive'] == 'on') {
        $sql .= " AND all_inclusive = 'ano'";
      }
      if (isset($_GET['restaurace']) && $_GET['restaurace'] == 'on') {
        $sql .= " AND restaurace = 'ano'";
      }
        // Přidání filtru na počet hvězdiček
      if (!empty($_GET['pocet-hvezdicek-od']) && !empty($_GET['pocet-hvezdicek-do'])) {
        $rating_from = intval($_GET['pocet-hvezdicek-od']);
        $rating_to = intval($_GET['pocet-hvezdicek-do']);
        if ($rating_from >= 0 && $rating_to <= 5 && $rating_from <= $rating_to) {
          $sql .= " AND hodnoceni BETWEEN $rating_from AND $rating_to";
        }
      }
      // Přidání možnosti vyhledávání pomocí search baru
      if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = $_GET['search'];
        $sql .= " AND nazev_hotelu LIKE '%$search_term%'";
      }

      $result = $conn->query($sql); // Provede finální příkaz - result v query
      if ($result->num_rows > 0) {
          // Loop vypisování hotelů
          while ($row = $result->fetch_assoc()) {
              ?>
              <a href="projekt_hotel.php?id=<?php echo $row["idHotel"]; ?>" class="hyperlink">
                <div class="hotel-container">
                  <div style="width: fit-content;">
                    <img src="placeholderimg.jpg" alt="placeholder" class="hotel-img">
                  </div>
                  <div class="hotel-text">
                    <div style="display: flex; align-items: center; width: 100%;">
                      <h2><?php echo $row["nazev_hotelu"]; ?></h2>
                      <h2 class="hotel-rating"><?php echo $row["hodnoceni"]; ?> ☆</h2>
                    </div>
                    <p style="margin-top: 0;">
                      <?php echo $row["popis"]; ?>
                    </p>
                  </div>
                </div>
              </a>
              <?php
          }
      } else {
          echo "Omlouváme se, ale vašemu filtru neodpovídá žádný výsledek";
      }
      ?>
    </div>
  </div>
</body> 
</html>