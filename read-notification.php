<?php
 
    // start the session
    session_start();
 
    // connect with database
    $conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");
 
    
    
        
                $id_receive = $_POST['user_id'];
                $id = $_POST['id'];
                $sql = "DELETE FROM `notifications` WHERE id = ? AND id_receive = ?";

                
                $statement = $conn->prepare($sql);
                $statement->execute([$id, $id_receive]);
    
                // send the response back to client
                echo json_encode([
                    "status" => "success"
                ]);
                exit();
            
    
    ?>