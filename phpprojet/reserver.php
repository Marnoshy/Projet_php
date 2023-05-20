<!DOCTYPE html>
<html>
<head>
    <style>
        /* Style for the container */
        .container {
            padding: 20px;
        }

        /* Reset padding for the body */
        body {
            padding: 0;
            margin: 0;
        }

        /* Style for the navbar */
        nav {
            background-color: black;
            color: white;
            padding: 20px;
        }

        /* Style for the navbar links */
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }

        /* Style for the reservation information box */
        .reservation-info {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
        }

        /* Define a maximum width and height for bike images */
        img.bike-image {
            max-width: 150px;
            max-height: 150px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <a href="index.php">Accueil</a>
</nav>

<div class="container">
    <h1>Réservation de vélo</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "location_velos";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $velos_id = $_POST["velos_id"];
        $date_debut = $_POST["date_debut"];
        $date_fin = $_POST["date_fin"];

        $insert_query = "INSERT INTO rentals (velos_id, location_start_date, location_end_date) VALUES ('$velos_id', '$date_debut', '$date_fin')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Réservation effectuée avec succès !<br>";
        } else {
            echo "Erreur : " . $insert_query . "<br>" . $conn->error;
        }

        
    }

    $conn->close();
    ?>


</div>
</body>
</html>

