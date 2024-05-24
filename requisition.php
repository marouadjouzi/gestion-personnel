<?php
session_start();
$servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "baosem";
 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $query = "SELECT * FROM employees";
 $stmt = $conn->prepare($query);
 $stmt->execute();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout-requisition</title>
    <link rel="stylesheet" href="requisition.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>
<main class="main-container">
        <div class="info--wrapper" style='background-color:#ececff;'>
        <div class="main-title">
        <p class="font-weight-bold">Ajouter une requisition</p>

        <div class="wrap">
            <div class="search">
                <input type="text"  id="searchInput" oninput="searchTable()" class="searchTerm" placeholder="Recherche....">
                <button type="submit" class="searchButton">
                 <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
        </div>
        <button class="button-40" role="button" onclick="conge()">Retour</button>
                <script>
                function conge() {
                    window.location.href = 'conge.php';
                }
                </script>
                <style>
    .button-40 {
      width: 64px;
    height: 36px;
    margin-top: 16px;
    margin-right: 20px;
    border-radius: 11px;
    border: solid #fff;
    background-color: #fff;
    font-size: 14px;
    /* font-style: italic; */
    color: #0d0d43;
    }

    .button-40:hover {
    background-color: #374151;
    }

    .button-40:focus {
    box-shadow: none;
    outline: 2px solid transparent;
    outline-offset: 2px;
    }

</style>
</div>
        <div class="container">
   
    <table id="employeesTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Département</th>
            <th>Email</th>
            <th>Ajouter une réquisition</th>
        </tr>
    </thead>
    <tbody>
        <?php
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $query22 = "SELECT * FROM departement WHERE id_departement = :d_departement";
        $stmt22 = $conn->prepare($query22);
        $stmt22->bindValue(':d_departement', $row['d_departement']);
        $stmt22->execute();

        if ($stmt22->rowCount() > 0) {
            while ($row22 = $stmt22->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                
                <tr>
                    <td><?php echo $row['id_employees']; ?></td>
                    <td><?php echo $row['nom']; ?></td>
                    <td><?php echo $row['prenom']; ?></td>
                    <td><?php echo $row22['nom_departement']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <form action="ajout-requisition.php" method="POST">
                            <input type="hidden" id="id" name="id" value="<?php echo $row['id_employees']; ?>">
                            <input type="hidden" id="nom" name="nom" value="<?php echo $row['nom']; ?>">
                            <input type="hidden" id="prenom" name="prenom" value="<?php echo $row['prenom']; ?>"><br><br>
                            <button type="submit" name="ajouter"><ion-icon name="add-circle"></ion-icon></button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            }
        }
      
      
        ?>
    </tbody>
        </table>
    </diV>
    
</main>
<script>
  function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("employeesTable");
    let rows = table.rows;

    for (let i = 1; i < rows.length; i++) {
      let cells = rows[i].cells;
      let match = false;

      for (let j = 0; j < cells.length - 1; j++) {
        if (cells[j].innerText.toLowerCase().includes(input)) {
          match = true;
          break;
        }
      }

      if (match) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>