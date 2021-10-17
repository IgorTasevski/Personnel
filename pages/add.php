<?php

use App\Database\DB;
use App\Helper\Helper as Helper;

session_start();

require_once __DIR__ . "/../database/DB.php";
require_once __DIR__ . "/../helpers/Helper.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $connection = DB::connect();
    $sql = "INSERT INTO calls (user_id, client_id, date, duration, type, score) 
            VALUES (:user_id, :client_id, :date, :duration, :type, :score)";

    $stmt = $connection->prepare($sql);

    if ($stmt->execute([
        ':user_id' => $_POST['user_id'],
        ':client_id' => $_POST['client_id'],
        ':date' => $_POST['date'],
        ':duration' => $_POST['duration'],
        ':type' => $_POST['type'],
        ':score' => $_POST['score']
    ])) {
        $_SESSION['add_success'] = true;
    } else {
        $_SESSION['add_error'] = true;
    }

    Helper::redirect("index.php");
}

$connection = DB::connect();

$sql = "SELECT u.user, cl.name, cl.type, c.date, c.duration, c.type, c.score
        FROM calls AS c 
        INNER JOIN clients as cl ON cl.id = c.client_id 
        INNER JOIN users as u ON u.id = c.user_id";

$stmt = $connection->prepare($sql);
$stmt->execute();

$call = $stmt->fetch();

$usersQuery = "SELECT * from users ORDER BY user";
$stmtUsers = $connection->prepare($usersQuery);
$stmtUsers->execute();
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

$clientsQuery = "SELECT * FROM clients ORDER BY name";
$stmtClients = $connection->prepare($clientsQuery);
$stmtClients->execute();
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require_once __DIR__ . "/parts/header.php"; ?>

<p class="h2 text-center p-3">Add call</p>

<div class="col d-flex justify-content-center text-center">
    <form method="POST" action="">
        <div class="form-group p-2">
            <label for="user">User</label>
            <select class="custom-select" name="user_id" id="user">
                <?php
                    if (count($users)) {
                        foreach ($users as $user) {
                            echo "<option value='{$user['id']}'>{$user['user']}</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group p-2">
            <label for="client">Client</label>
            <select name="client_id" id="client" class="custom-select">
                <?php
                    if (count($clients)) {
                        foreach ($clients as $client) {
                            echo "<option value='{$client['id']}'>{$client['name']}</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group p-2">
            <label for="date">Date</label>
            <input id="date" class="form-control" type="date" name="date" />
        </div>
        <div class="form-group p-2">            
            <label for="duration">Duration</label>
            <input class="form-control" id="duration" type="text" name="duration" />
        </div>
        <div class="form-group p-2">
            <label for="call_type">Type of Call</label>
            <select class="custom-select" id="call_type" name="type" id="call_type">
                <option value="Outgoing">Outgoing</option>
                <option value="Incoming">Incoming</option>
            </select>
        </div>
        <div class="form-group p-2">
            <label id="call_score">External Call Score</label>
            <input class="form-control" for="call_score" type="text" name="score" />
        </div>
        <button class="btn btn-primary">Add</button>
    </form>
</div>

<?php require_once __DIR__ . "/parts/footer.php"; ?>