<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baosem";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<script>alert("La connexion a échoué : ' . $e->getMessage() . '")</script>';
}
$db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');



 if (isset($_POST['ajout_c'])) {
            $type_conge = $_POST['type'];
            $date_d = $_POST['date'];
            $date_f = $_POST['date_f'];
            $d_employee = $_POST['responsable'];
            $val_direct_general = '1';
            $val_departement = '1';
            $val_direction = '1';
            $val1_rh = '1';
            $val2_rh = $_POST['statu'];
        
                try {
                    // Préparation et exécution de la requête d'insertion
                    $sql1 = "INSERT INTO demande_conge (type_conge, date_d, date_f,  d_employee, val_departement, val_direction, val1_rh, val_direct_general, val2_rh) 
                    SELECT :type_conge,  :date_d, :date_f, :d_employee, :val_departement, :val_direction, :val1_rh, :val_direct_general, :val2_rh 
                    FROM dual 
                    WHERE NOT EXISTS (
                        SELECT 1 
                        FROM demande_conge 
                        WHERE type_conge = :type_conge 
                        AND date_d = :date_d
                        AND date_f = :date_f
                        AND d_employee = :d_employee
                    )";
                    $stmt1 = $conn->prepare($sql1);
                    $stmt1->bindParam(':type_conge', $type_conge);
                    $stmt1->bindParam(':date_d', $date_d);
                    $stmt1->bindParam(':date_f', $date_f);
                    $stmt1->bindParam(':d_employee', $d_employee);
                    $stmt1->bindParam(':val_departement', $val_departement);
                    $stmt1->bindParam(':val_direction', $val_direction);
                    $stmt1->bindParam(':val1_rh', $val1_rh); 
                    $stmt1->bindParam(':val_direct_general', $val_direct_general);
                    $stmt1->bindParam(':val2_rh', $val2_rh);
                    $stmt1->execute();
                    // $reponse = $requete->fetchAll(PDO::FETCH_ASSOC);
                    // var_dump($reponse);
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
                header('Location: conge.php ' );
                exit;}

                if (isset($_POST['ajout_c'])) {
            $type_conge = $_POST['type'];
            $date_d = $_POST['date'];
            $date_f = $_POST['date_f'];
            $d_employee = $_POST['responsablec'];
            $val_direct_general = '1';
            $val_departement = '1';
            $val_direction = '1';
            $val1_rh = '1';
            $val2_rh = $_POST['statu'];
        
                try {
                    // Préparation et exécution de la requête d'insertion
                    $sql1 = "INSERT INTO demande_conge (type_conge, date_d, date_f,  d_employee, val_departement, val_direction, val1_rh, val_direct_general, val2_rh) 
                    SELECT :type_conge,  :date_d, :date_f, :d_employee, :val_departement, :val_direction, :val1_rh, :val_direct_general, :val2_rh 
                    FROM dual 
                    WHERE NOT EXISTS (
                        SELECT 1 
                        FROM demande_conge 
                        WHERE type_conge = :type_conge 
                        AND date_d = :date_d
                        AND date_f = :date_f
                        AND d_employee = :d_employee
                    )";
                    $stmt1 = $conn->prepare($sql1);
                    $stmt1->bindParam(':type_conge', $type_conge);
                    $stmt1->bindParam(':date_d', $date_d);
                    $stmt1->bindParam(':date_f', $date_f);
                    $stmt1->bindParam(':d_employee', $d_employee);
                    $stmt1->bindParam(':val_departement', $val_departement);
                    $stmt1->bindParam(':val_direction', $val_direction);
                    $stmt1->bindParam(':val1_rh', $val1_rh);
                    $stmt1->bindParam(':val_direct_general', $val_direct_general);
                    $stmt1->bindParam(':val2_rh', $val2_rh);
                    $stmt1->execute();
                    // $reponse = $requete->fetchAll(PDO::FETCH_ASSOC);
                    // var_dump($reponse);
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
                header('Location: conge.php ' );
                exit;
}

if (isset($_POST['ajout_b'])) {
    $d_employee = $_POST['responsableb'];
    $jrs_requisition = $_POST['jrs_auto'];
    $jrs_rest = $_POST['jrs_rest'];
    $jrs_consom = $_POST['jrs_consom'];

        try {
            // Préparation et exécution de la requête d'insertion
            $sql2 = "INSERT INTO conge_requisition (d_employee, jrs_requisition, jrs_consom, jrs_rest) 
            SELECT :d_employee,  :jrs_requisition, :jrs_rest, :jrs_consom 
            FROM dual 
            WHERE NOT EXISTS (
                SELECT 1 
                FROM conge_requisition 
                WHERE d_employee = :d_employee 
                AND jrs_requisition = :jrs_requisition
                AND jrs_rest= :jrs_rest 
                AND jrs_consom = :jrs_consom
            )";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bindParam(':d_employee', $d_employee);
            $stmt2->bindParam(':jrs_requisition', $jrs_requisition);
            $stmt2->bindParam(':jrs_rest', $jrs_rest);
            $stmt2->bindParam(':jrs_consom', $jrs_consom);
            $stmt2->execute();
            // $reponse = $requete->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($reponse);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        header('Location: conge.php ' );
        exit;}
        


        if (isset($_POST['ajout_a'])) {
            $d_employe = $_POST['responsable1'];
            $exercice = $_POST['exercice'];
            $jrs_autoris = $_POST['jrs_autoris'];
            $jrs_consom = $_POST['jrs_consom'];
            $jrs_rest = $_POST['jrs_rest'];

            $query44 = "SELECT * FROM conge_annuel WHERE d_employe = :d_employe AND exercice = :exercice";
            $stmt44 = $conn->prepare($query44);
            $stmt44->bindParam(':d_employe', $d_employe);
            $stmt44->bindParam(':exercice', $exercice);
            $stmt44->execute();
            $result44 = $stmt44->fetchAll();
            
            if (count($result44) > 0) {
                echo '<script>
                    Swal.fire({
                        title: "Cette employe a deja un reliquat dans cet exercice",
                        icon: "warning",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "conge.php";
                        }
                    });
                </script>';
            } else {
                $sql3 = "INSERT INTO conge_annuel (d_employe, exercice, jrs_autoris, jrs_restant, jrs_consome) 
                    SELECT :d_employe, :exercice, :jrs_autoris, :jrs_rest, :jrs_consom 
                    FROM dual 
                    LEFT JOIN conge_annuel ON 
                        conge_annuel.d_employe = :d_employe 
                        AND conge_annuel.exercice = :exercice 
                        AND conge_annuel.jrs_autoris = :jrs_autoris 
                        AND conge_annuel.jrs_restant = :jrs_rest 
                        AND conge_annuel.jrs_consome = :jrs_consom
                    WHERE conge_annuel.d_employe IS NULL";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bindParam(':d_employe', $d_employe);
                $stmt3->bindParam(':exercice', $exercice);
                $stmt3->bindParam(':jrs_autoris', $jrs_autoris);
                $stmt3->bindParam(':jrs_rest', $jrs_rest);
                $stmt3->bindParam(':jrs_consom', $jrs_consom);
                $stmt3->execute();
                header('Location: conge.php ');
                exit;
            }
        }
                
        
        
        

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
    
</body>
</html>