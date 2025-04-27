
<?php
include 'conn.php';
session_start();
// Získání ID hotelu z GET parametrů
$hotelId = isset($_GET['id']) ? (int)preg_replace('/[^0-9]/', '', $_GET['id']) : 0; // Hotel ID z URL
$pdo = getDbConnection();

// Fetchování dat z Hotelu
$stmt = $pdo->prepare('SELECT * FROM Hotel WHERE idHotel = :id');
$stmt->execute(['id' => $hotelId]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetchování pokojů daného hotelu
$stmtRooms = $pdo->prepare('SELECT * FROM Pokoje WHERE Hotel_idHotel = :hotelId');
$stmtRooms->execute(['hotelId' => $hotelId]);
$rooms = $stmtRooms->fetchAll(PDO::FETCH_ASSOC);

// Získání počtu nocí ze session
$nights = isset($_SESSION['nights']) ? $_SESSION['nights'] : 1; // Defaultní počet: 1
?>
<!DOCTYPE html>
<html>
<head>
  <title>project x</title>
  <link rel="stylesheet" href="projekt_hotel.css">
  <link rel="stylesheet" href="header.css">
  <script>
    function reserveRoom() {
      <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
      alert("Pokoj úspěšně zarezervován");
      window.location.href = "index.php";
      <?php else: ?>
      alert("Pro rezervaci hotelu se prosím přihlašte");
      <?php endif; ?>
    }
  </script>
</head>
<body>
  <header>
    <a href="index.php" class="hyperlink">
      <div class="logo-container">
        <div class="logo">
          <img src="rezervuj.png" alt="logo" class="logo">
        </div>
        <h1 class="name">Rezervujsi.cz</h1>
      </div>
     </a>
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
        <div class="picture-container">
            <img src="placeholderimg.jpg" alt="obrázek hotelu" class="hotel-img">
        </div>
        <div class="text-container">
            <div class="hotel-name">
                <h2><?php echo $hotel["nazev_hotelu"]; ?></h2>
                <h2 class="hotel-rating"><?php echo $hotel["hodnoceni"]; ?>☆</h2>
             </div>
             <div class="popis">
               <?php echo $hotel["popis"]; ?>
             </div>
             <table class="rooms-table">
                <thead>
                    <tr>
                        <th>Typ pokoje</th>
                        <th>Počet lidí</th>
                        <th>Cena za noc</th>
                        <th>Rezervace</th>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($rooms as $room): ?>
                    <tr>
                      <td><?php echo $room["typ_pokoje"]; ?></td>
                      <td><?php echo $room["pocet_lidi"]; ?></td>
                      <td><?php echo ($room["cena_za_noc"] * $nights) . ' CZK'; ?></td>
                      <td><button class="reserve-btn" onclick="reserveRoom()">Rezervovat</button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="under-img">
      <p>
        📍   <?php echo $hotel["zeme"]; ?>, <?php echo $hotel["mesto"]; ?>, <?php echo $hotel["adresa"]; ?><br>
        📧   <?php echo $hotel["email"]; ?><br>
        📞   <?php echo $hotel["tel_cislo"]; ?>
      </p>
    </div>
</body>
</html>