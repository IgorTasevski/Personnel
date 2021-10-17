<?php

namespace App\Import;

use App\Helper\Helper;
use App\Database\DB;
use PDO;

session_start();

require_once __DIR__ . "/../database/DB.php";
require_once __DIR__ . "/Helper.php";

class Import
{  
    public static function importFile() 
    {

        if(isset($_POST['importSubmit'])){
    
            $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
                
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                    
                    fgetcsv($csvFile);

                    $lineNumber = 1;

                    while(($line = fgetcsv($csvFile)) !== FALSE){
                        $user   = $line[0];
                        $client = $line[1];
                        $client_type  = $line[2];
                        $date = $line[3];
                        $duration = $line[4];
                        $call_type = $line[5];
                        $call_score = $line[6];

                        $lineNumber++;

                        $connection = DB::connect();                        

                        // Insert into users table
                        $checkUniqueUsersQuery = "SELECT * FROM users WHERE user = :user";
                        $checkUniqueUsersQueryStmt = $connection->prepare($checkUniqueUsersQuery);
                        $checkUniqueUsersQueryStmt->execute([":user" => $user]);
                        $checkUniqueUsersQueryResults = $checkUniqueUsersQueryStmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($checkUniqueUsersQueryResults) == 0) {
                            $usersQuery = "INSERT INTO users (user) VALUES (:user);";
                            $connection->execPrepared($usersQuery, [":user" => $user]);
                        }

                        // Insert into clients table
                        $checkUniqueClientsQuery = "SELECT * FROM clients WHERE name = :client";
                        $checkUniqueClientsQueryStmt = $connection->prepare($checkUniqueClientsQuery);
                        $checkUniqueClientsQueryStmt->execute([":client" => $client]);
                        $checkUniqueClientsQueryResults = $checkUniqueClientsQueryStmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($checkUniqueClientsQueryResults) == 0) {
                            $clientsQuery = "INSERT INTO clients (name, type) VALUES (:name, :type)";
                            $connection->execPrepared($clientsQuery, [":name" => $client, ":type" => $client_type]);
                        }

                        // Insert into calls table
                        $userIdQuery = "SELECT id FROM users WHERE user = :user";
                        $clientIdQuery = "SELECT id FROM clients WHERE name = :client";

                        $userIdQueryStmt = $connection->prepare($userIdQuery);
                        $userIdQueryStmt->execute([":user" => $user]);
                        $userIdQueryResults = $userIdQueryStmt->fetchAll(PDO::FETCH_ASSOC);
                        $userId = $userIdQueryResults[0]["id"];

                        $clientIdQueryStmt = $connection->prepare($clientIdQuery);
                        $clientIdQueryStmt->execute([":client" => $client]);
                        $clientIdQueryResults = $clientIdQueryStmt->fetchAll(PDO::FETCH_ASSOC);
                        $clientId = $clientIdQueryResults[0]["id"];

                        $insertCallsQuery = "INSERT INTO calls (user_id, client_id, date, duration, type, score) VALUES (:user_id, :client_id, :date, :duration, :type, :score)";
                        $connection->execPrepared($insertCallsQuery, [
                            ":user_id" => $userId,
                            ":client_id" => $clientId,
                            ":date" => $date,
                            ":duration" => $duration,
                            ":type" => $call_type,
                            ":score" => $call_score,
                        ]);

                        $lineNumber++;
                    }

                    fclose($csvFile);
                    
                    $_SESSION['import_success'] = true;
                } else {
                    $_SESSION['import_warning'] = true;

                }
            } else {
                $_SESSION['import_invalid'] = true;
            }
        }
    }
}