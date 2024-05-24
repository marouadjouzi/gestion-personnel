<?php
// start session
session_start();

// connect with database
$conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");

// get all notifications sorting by unread goes first
$requete444 = "SELECT * FROM departement WHERE nom_departement = :nom_departement";
$statement = $conn->prepare($requete444);
$statement->bindParam(':nom_departement', $_SESSION['approbation']);
$statement->execute();
$resultat444 = $statement->fetchAll(PDO::FETCH_ASSOC);

if (!empty($resultat444)) {
    foreach ($resultat444 as $line444) {
        $id_receive = $line444['id_departement'];
        $sql = "SELECT * FROM notifications WHERE id_receive = ? ORDER BY is_read ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_receive]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Traiter les notifications ici
    }
}
$conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");

// get all notifications sorting by unread goes first
$requete444 = "SELECT * FROM departement WHERE nom_departement = :nom_departement";
$statement = $conn->prepare($requete444);
$statement->bindParam(':nom_departement', $_SESSION['approbation']);
$statement->execute();
$resultat444 = $statement->fetchAll(PDO::FETCH_ASSOC);

$total_unread_notifications = 0;

if (!empty($resultat444)) {
    foreach ($resultat444 as $line444) {
        $id_receive = $line444['id_departement'];
        $receiv_num = "SELECT COUNT(*) AS total_unread_notifications FROM notifications WHERE id_receive = ? AND is_read = 0";
        $statement = $conn->prepare($receiv_num);
        $statement->execute([$id_receive]);
        $row = $statement->fetch();
        $total_unread_notifications += $row['total_unread_notifications'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Project</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="notification.css">
</head>
<body>
     <div id="popup">
    <div class="container"style="background-color: #dddddd;">
        <div class="notificationContainer" >
            <header>
                <div class="notificationHeader">
                    <h1>Notification</h1>
                    <span id="num-of-notif"><?php echo $total_unread_notifications;?></span>

                </div>
                <p id="mark-as-read">Marquer tout comme lu</p>
            </header>
            <main>
            <?php if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                ?>
               
                <div class="notificationCard unread">
                    <div class="description">
                        <p style="font-size:20px; margin-top: -32px; text-decoration: underline; smargin-bottom: 30px;">Notification:</p>
                        <p style="font-size:20px;"><?php echo $notification['message']; ?></p>
                        <p style="margin-left:370px;" id="notif-time"><?php echo $notification['created_at']; ?></p>
                        <?php if (!$notification['is_read']): ?>
                            <form action="read-notification.php" method="post" onsubmit="return markAsRead();">
                                <input type="hidden" name="id" value="<?php echo $notification['id']; ?>" />
                                <input type="hidden" name="user_id" value="<?php echo $notification['id_receive']; ?>" />
                                <button type="submit" style=" margin-left: 527px;font-size: 28px;padding: 3px;margin-top: 16px;border-radius: 17px;border: none;background-color: #48b948;color: white;" name="read" ><i class='bx bx-check-double'></i></button>
                                </button>
                            </form>
                            <script>
                                
                                    var unread = document.getElementById("unread");
                                    <?php if($notification['is_read'] == '0'){?>
                                        unread.style.background-color: rgb(215, 255, 196);
                                    <?php }else{?>
                                        unread.style.background-color: #fff;
                                    <?php }?>    
                                    
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        }
    
?></div>
                
            </main>
        </div>
    </div>
    <input type="hidden" id="total_unread_notifications" value="<?php echo $total_unread_notifications;?>" />
    <input type="hidden" id="user-id" value="<?php echo $notification['id_receive'];?>"/>
<script>
    var totalUnreadNotifications = document.getElementById("total_unread_notifications").value;
    totalUnreadNotifications = parseInt(totalUnreadNotifications);

    showTitleBarNotifications(){
    var pattern = /^\(\d+\)/;

    if (totalUnreadNotifications == 0) {
        document.title = document.title.replace(pattern, "");
        return;
    }

    if (pattern.test(document.title)) {
        document.title = document.title.replace(pattern, "(" + totalUnreadNotifications + ")");
    } else {
        document.title = "(" + totalUnreadNotifications + ")" + document.title;
    }
}

</script>
</body>
</html>

<script>
   
    
    // when the read button is clicked
    function markAsRead() {
        // prevent the form from submitting
        event.preventDefault();
 
        // get the form node
        var form = event.target;
 
        // create AJAX object
        var ajax = new XMLHttpRequest();
 
        // set method and URL of request
        ajax.open("POST", "read-notification.php", true);
 
        // when the status of request changes
        ajax.onreadystatechange = function () {
 
            // when the response is received from server
            if (this.readyState == 4) {
 
                // if the response is successful
                if (this.status == 200) {
 
                    // convert the JSON string into Javascript object
                    var data = JSON.parse(this.responseText);
                    console.log(data);
 
                    // if there is no error
                    if (data.status == "success") {
 
                        // remove the 'read' button
                        form.remove();
 
                        // [emit read notification event here]
                        
                    }
                }
            }
        };
 
        // create form data object with the form
        var formData = new FormData(form);
 
        // send the AJAX request with the form data
        ajax.send(formData);
    }
    

</script>