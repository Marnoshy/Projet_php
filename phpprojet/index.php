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
    <h1>Bienvenue sur VéloCity</h1>
    <h3>Veuillez sélectionner les dates souhaitées pour la location de vélo</h3>
    <form action="index.php" method="get" onsubmit="return validateForm()">
        <label for="date_debut">Date de début:</label>
        <input type="date" id="date_debut" name="date_debut" value="<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : ''; ?>" required>

        <label for="date_fin">Date de fin:</label>
        <input type="date" id="date_fin" name="date_fin" value="<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : ''; ?>" required>
        <input type="submit" value="Filtrer">
    </form>


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
        $showUnavailable = isset($_GET['show_unavailable']) ? $_GET['show_unavailable'] : '';

        if ($availabilityResult->num_rows > 0 || !empty($showUnavailable)) {
            $sql .= " WHERE velos_id NOT IN (
                SELECT velos_id FROM rentals
                WHERE (location_start_date <= '$date_fin' AND location_end_date >= '$date_debut')
                OR (location_start_date <= '$date_debut' AND location_end_date >= '$date_fin')
            )";
        }
    } else {
        $sql = "SELECT velos_id, velos_nom, velos_image FROM bikes";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $velo_id = $row["velos_id"];
            $velo_nom = $row["velos_nom"];
            $velo_image = $row["velos_image"];
            
            $availability_query = "SELECT location_end_date FROM rentals WHERE velos_id = '$velo_id' ORDER BY location_end_date DESC LIMIT 1";
            $availability_result = $conn->query($availability_query);

            
            if ($availability_result->num_rows > 0) {
                $availability_row = $availability_result->fetch_assoc();
                $location_end_date = $availability_row["location_end_date"];


                if ($location_end_date < $date_debut || $date_fin < date("Y-m-d")) {
                    // Le vélo est disponible
                    echo "<li>";
                    echo "<img class='bike-image' src='" . $velo_image . "' alt='" . $velo_nom . "'>";
                    echo "<div class='bike-name'>" . $velo_nom . "</div>";
                    echo "<div class='availability available'>Disponible</div>";
                    echo '<form action="reserver.php" method="post" onsubmit="return validateForm()">';
                    echo '<input type="hidden" name="velos_id" value="' . $velo_id . '">';
                    echo '<input type="hidden" name="date_debut" value="' . $date_debut . '">';
                    echo '<input type="hidden" name="date_fin" value="' . $date_fin . '">';
                    echo '<input type="submit" value="Réserver">';
                    echo '</form>';
                    echo "</li>";
                } else {
                    // Le vélo n'est pas disponible
                    echo "<li>";
                    echo "<img class='bike-image' src='" . $velo_image . "' alt='" . $velo_nom . "'>";
                    echo "<div class='bike-name'>" . $velo_nom . "</div>";
                    echo "<div class='availability unavailable'>Non disponible</div>";
                    echo "</li>";
                }
            } else {
                // Aucune réservation existante, le vélo est disponible
                echo "<li>";
                echo "<img class='bike-image' src='" . $velo_image . "' alt='" . $velo_nom . "'>";
                echo "<div class='bike-name'>" . $velo_nom . "</div>";
                echo "<div class='availability available'>Disponible</div>";
                echo "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "Aucun vélo trouvé.";
    }
    $conn->close();
    ?>

        <?php if (!empty($date_debut) && !empty($date_fin)) : ?>
        <form action="veloindispo.php" method="get">
            <input type="hidden" name="date_debut" value="<?php echo $date_debut; ?>">
            <input type="hidden" name="date_fin" value="<?php echo $date_fin; ?>">
            <input type="submit" value="Voir les vélos indisponibles">
        </form>
    <?php endif; ?>
</div>
</body>
</html>

<script>
    document.getElementById('date_debut').addEventListener('change', function() {
        var dateDebut = new Date(this.value);
        var dateFinInput = document.getElementById('date_fin');
        dateFinInput.min = this.value;
        if (dateFinInput.value && new Date(dateFinInput.value) < dateDebut) {
            dateFinInput.value = this.value;
        }
    });

    function validateForm() {
        var dateDebut = document.getElementById('date_debut').value;
        var dateFin = document.getElementById('date_fin').value;
        if (dateDebut === '' || dateFin === '') {
            alert("Veuillez entrer des dates.");
            return false;
        }
        return true;
    }
</script>


