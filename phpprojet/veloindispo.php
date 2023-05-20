<!DOCTYPE html>
<html>
<head>
    <style>
        img.bike-image {
            max-width: 200px;
            max-height: 200px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            margin-bottom: 10px;
        }

        .bike-name {
            flex-grow: 1;
            margin-left: 20px;
        }

        .availability {
            width: 20px;
            height: 20px;
            margin-left: 10px;
        }

        .availability.available {
            background-color: green;
            margin-right: 100px;
            width: 110px;
            color: white;
            text-align: center;
        }

        .availability.unavailable {
            background-color: orange;
            margin-right: 100px;
            width: 110px;
            color: white;
            text-align: center;

        }

        nav {
            background-color: black;
            color: white;
            padding: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }

        .container {
            padding: 20px;
        }

        body {
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Accueil</a>
</nav>
    
<div class="container">
    <a href="index.php?date_debut=<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : ''; ?>&date_fin=<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : ''; ?>" style="background-color: black; color: white; text-decoration: none; padding: 10px 20px; display: inline-flex; align-items: center;">
    <img src="../photovelos/logoback.png" alt="Logo" style="margin-right: 10px; max-width: 20px; max-height: 20px;">
    Retourner à l'accueil
    </a>

    <h1>Les vélos indisponibles</h1>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "location_velos";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : '';
    $date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : '';

    if (!empty($date_debut) && !empty($date_fin)) {
        $sql = "SELECT velos_id, velos_nom, velos_image FROM bikes";
        $availabilitySql = "SELECT velos_id FROM rentals WHERE 
            (location_start_date <= '$date_fin' AND location_end_date >= '$date_debut')
            OR (location_start_date <= '$date_debut' AND location_end_date >= '$date_fin')";
        
        $availabilityResult = $conn->query($availabilitySql);

        if ($availabilityResult->num_rows > 0) {
            $bikeIds = array();
            while ($row = $availabilityResult->fetch_assoc()) {
                $bikeIds[] = $row['velos_id'];
            }

            $bikeIdsStr = implode(',', $bikeIds);
            $sql .= " WHERE velos_id IN ($bikeIdsStr)";
        } else {
            // Aucun vélo indisponible
            echo "Aucun vélo indisponible trouvé.";
            $conn->close();
            exit();
        }
    } else {
        // Les dates de début et de fin sont requises
        echo "Veuillez sélectionner les dates de début et de fin.";
        $conn->close();
        exit();
    }

    $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $velo_id = $row["velos_id"];
            $velo_nom = $row["velos_nom"];
            $velo_image = $row["velos_image"];

            echo "<li>";
            echo "<img class='bike-image' src='" . $velo_image . "' alt='" . $velo_nom . "'>";
            echo "<div class='bike-name'>" . $velo_nom . "</div>";
            echo "<div class='availability unavailable'>Non disponible</div>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "Aucun vélo indisponible trouvé.";
    }
    $conn->close();
    ?>
</div>
</body>
</html>

