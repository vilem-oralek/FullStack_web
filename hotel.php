<?php
include 'conn.php'; // Načtení souboru pro připojení k databázi

// Získání ID hotelu z GET parametrů
$hotelId = isset($_GET['id']) ? (int)preg_replace('/[^0-9]/', '', $_GET['id']) : 0;
echo "Parametr 'id' v URL je: " . $_GET['id'] . "<br>";
echo "Hodnota hotelId po konverzi na celé číslo: " . $hotelId . "<br>";

if ($hotelId <= 0) {
    echo "Neplatné ID hotelu.";
    exit;
}

$pdo = getDbConnection();

if ($pdo) {
    echo "Připojení k databázi bylo úspěšné.<br>";
} else {
    echo "Chyba při připojení k databázi.<br>";
    exit;
}

// Příprava a provedení dotazu
$stmt = $pdo->prepare('SELECT nazev_hotelu, popis FROM Hotel WHERE idHotel = :id');
$stmt->execute(['id' => $hotelId]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

if ($hotel) {
    echo "Hotel nalezen: " . htmlspecialchars($hotel['nazev_hotelu'], ENT_QUOTES, 'UTF-8') . "<br>";
} else {
    echo "Hotel nenalezen.";
    exit;
}
?>
