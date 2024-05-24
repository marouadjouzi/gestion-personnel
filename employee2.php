<?php
session_start();
require_once('config.php');


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Source+Sans+Pro:wght@600;700&display=swap"
    rel="stylesheet">

        <!-- ===== CSS ===== -->
        <link rel="stylesheet" href="employee.css">
        
        <title>employee</title>
    </head>
    <body id="body-pd">

        <!--SIDE BAR-->
        <div class="l-navbar" id="navbar">
            <nav class="nav">
                <div>
                    <div class="nav__brand">
                        <ion-icon name="menu-outline" class="nav__toggle" id="nav-toggle"></ion-icon>
                    </div>
                    <div class="nav__list">
                    <a href="profil.php" class="nav__link ">
                            <ion-icon name="home-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Acceuil</span>
                        </a>
                        <a href="employee.php" class="nav__link active">
                        <ion-icon name="notifications-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Acceuil</span>
                        </a>
                        
                        <a href="employee2.php" class="nav__link">
                            <ion-icon name="calendar-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Congés</span>
                        </a>
                      
                    </div>
                </div>

                <a onclick="logout()" class="nav__link">
                
                <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                <span class="nav__name">DECONNECTER</span>
            </a>  
            </nav>
        </div>
       
        
      <!-- 
        - #SERVICE
      -->

      <section class="section-service" id="services">
        <div class="container">

          
          <h2 class="h2 section-title underline">Etat de mes demandes</h2>

          <table>
  <thead>
    <tr>
      <th>ID_demande</th>
      <th>MOTIF</th>
      <th>Departement</th>
      <th>Direction</th>
      <th>DRH</th>
      <th>Direction Generale</th>
      <th>Statu</th>
      <th>Observation</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
    $stm = $db->prepare('SELECT * FROM demande_conge Where d_employee = :id');
    $stm->bindParam(':id', $_SESSION['id']);
    $stm->execute();
    $r = $stm->fetchAll();
    foreach ($r as $line4) {
        $direction = '';
        switch ($line4['val_direction']) {
            case 0:
                $direction = 'En attente';
                break;
            case 1:
                $direction = 'Acceptee';
                break;
            case 2:
                $direction = 'Refuse';
                break;
            default:
                $direction = 'Valeur inconnue';
                break;
        }
         $departement = '';
        switch ($line4['val_departement']) {
            case 0:
                $departement= 'En attente';
                break;
            case 1:
                $departement = 'Acceptee';
                break;
            case 2:
                $departement = 'Refuse';
                break;
            default:
                $departement = 'Valeur inconnue';
                break;
        }
         $drh = '';
        switch ($line4['val1_rh']) {
            case 0:
                $drh = 'En attente';
                break;
            case 1:
                $drh = 'Acceptee';
                break;
            case 2:
                $drh = 'Refuse';
                break;
            default:
                $drh = 'Valeur inconnue';
                break;
        }
         $direct_general = '';
        switch ($line4['val_direct_general']) {
            case 0:
                $direct_general = 'En attente';
                break;
            case 1:
                $direct_general = 'Acceptee';
                break;
            case 2:
                $direct_general = 'Refuse';
                break;
            default:
                $direct_general= 'Valeur inconnue';
                break;
        }
      $statu = $line4['val2_rh'] == 1 ? 'En attente de signature de TDC' : 'En attente';
      $statu = $line4['val2_rh'] == 2 ? 'refusee' : $statu;
      $statu = $line4['val1_rh'] == 2 ? 'refusee' : $statu;
      $statu = $line4['val_direct_general'] == 2 ? 'refusee' : $statu;
      $statu = $line4['val_direction'] == 2 ? 'refusee' : $statu;
      $statu = $line4['val_departement'] == 2 ? 'refusee' : $statu;
    ?>
      <tr>
        <td><?php echo $line4['id_demande']; ?></td>
        <td><?php echo $line4['type_conge']; ?></td>
        <td><?php echo $departement; ?></td>
        <td><?php echo $direction; ?></td>
        <td><?php echo $drh; ?></td>
        <td><?php echo $direct_general; ?></td>
        <td><?php echo $statu; ?></td>
        <td><?php echo $line4['observation']; ?></td>
      </tr>
    <?php }
    ?>
  </tbody>
</table>
        </div>
      </section>

<style>
  @import "compass/css3";
 table {
  width: 100%;
	 font-family: 'Arial';
	 margin: 25px auto;
	 border-collapse: collapse;
	 border: 1px solid #eee;
	 border-bottom: 2px solid #0cc;
	 box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.10), 0px 10px 20px rgba(0, 0, 0, 0.05), 0px 20px 20px rgba(0, 0, 0, 0.05), 0px 30px 20px rgba(0, 0, 0, 0.05);
}
 table tr:hover {
	 background: #f4f4f4;
}
 table tr:hover td {
	 color: #555;
}
 table th, table td {
	 color: #999;
	 border: 1px solid #eee;
	 padding: 12px 35px;
	 border-collapse: collapse;font-size: 20px;
}
 table th {
	 background: #0cc;
	 color: #fff;
	 text-transform: uppercase;
	 
}
 table th.last {
	 border-right: none;
}
.popup_v {
    display: none;
    position: absolute;
    top:15%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 999;
  }
</style>
       
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        
        <!-- ===== MAIN JS ===== -->
        <script src="employee.js"></script>
        
</script>
             
</body>
</html>
