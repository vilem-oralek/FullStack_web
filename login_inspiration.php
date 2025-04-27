<?php
session_start();
include 'conn.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kontrola loginu - pokud není username prázdné a password není prázdný 
    if (!empty($email) && !empty($password))    
      
        //Najdeme v databázi uživatele s stejným username a heslem. Je tedy nutné aby existoval záznam kde se hodnoty shodují s tím co jsme zadali na webu
        //Zabezpečení proti SQL Injection a SQL Corruption
        $stmt = $conn->prepare("SELECT * FROM USER WHERE username= ?");
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $result = $stmt ->get_result();


        // Uživatel nal
        if ($result->num_rows == 1) {
            $result = $result->fetch_assoc();
            


            //Porovnání hesla s hashem
            if (password_verify($password, $result['password'])) {
            // Nastavíme první session proměnnou a to username a přesměrujeme na hlavní stránku
                $stmt = $conn->prepare("SELECT PICTURE_idPICTURE FROM USER WHERE username = ? AND password = ?");
                $stmt->bind_param("ss", $result['USERNAME'], $result['PASSWORD']);
                $stmt->execute();
                $result = $stmt->get_result();

            $row = $result->fetch_assoc();
            $ID_PIC = $row['PICTURE_idPICTURE'];

            $sql = "SELECT PATH FROM PICTURE WHERE idPICTURE='$ID_PIC'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $PROFILE_PIC = $row['PATH'];


            $_SESSION['username'] = $username;
            $_SESSION['profile_pic'] = $PROFILE_PIC;
                
                //echo "Uživatel přihlášen a tady je jeho fotka";
                //echo '<img src="' . $_SESSION['profile_pic'] . '">';
                header("Location: index.php");

            //Založíme cookie
            //Parametry v pořadí (jméno cookie, data která ukládáme do cookie, čas cookie, dostupnost na serveru, dostupnost na doméně defaultně je pro celou doménu mimo subdomenu, true = poze https komunikace
            //setcookie("username", $username, time() + (86400 * 2), "/", "", true);
            //setcookie("profile_pic", $PROFILE_PIC, time() + (86400 * 2), "/", "", true);


        }else{
                echo "Špatně zadané údaje, špatně hash heslo";
            }
            echo '<br> <a href="index.php">Main page</a> <br>';    
        } else {
            // Špatně zadané hodnoty
            echo "Špatně zadané údaje";
        }
    } else {
        echo "Musíte zadat username a heslo";
    }
}

?>
