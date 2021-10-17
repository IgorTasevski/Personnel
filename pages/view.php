<?php

use App\Database\DB;
use App\Helper\Helper as Helper;

session_start();

require_once __DIR__ . "/../database/DB.php";
require_once __DIR__ . "/../helpers/Helper.php";

$connection = DB::connect();

//select user
$usersQuery = "SELECT user from users WHERE id = :id";
$stmtUsers = $connection->prepare($usersQuery);
$stmtUsers->execute(['id' => $_GET['id']]);
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

//select average score
$scoreQuery = 'SELECT AVG(score) FROM calls WHERE user_id = :id AND duration > 10';
$stmtScore = $connection->prepare($scoreQuery);
$stmtScore->execute(['id' => $_GET['id']]);
$scores = $stmtScore->fetchAll(PDO::FETCH_ASSOC);

// die(var_dump($scores));

?>

<?php require_once __DIR__ . "/parts/header.php"; ?>

<p class="h2 text-center m-3">View user</p>

<input type="hidden" name="id" value="<?= $_GET['id'] ?> " />
<p class="h5 m-3">Name:
    <?php echo $users[0]['user'] ?>
</p>
    
<p class="h6 m-3">Average score:
    <?php foreach ($scores as $score ) { echo $score['AVG(score)']; } ?>
</p>

<div class="container-fluid pt-5 text-center">
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">User</th>
                <th scope="col">Client</th>
                <th scope="col">Client Type</th>
                <th scope="col">Date</th>
                <th scope="col">Duration</th>
                <th scope="col">Type Of Call</th>
                <th scope="col">External Call Score</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $connection = DB::connect();

            $callsQuery = "SELECT ca.id AS id, ca.date AS date, ca.duration AS duration, ca.type AS callType, 
                                ca.score as score, cl.name AS clientName, cl.type AS clientType, u.user AS user 
                            FROM calls ca
                            LEFT JOIN users u on u.id = ca.user_id
                            LEFT JOIN clients cl on cl.id = ca.client_id
                            WHERE ca.user_id = :id AND duration > 10
                            ORDER BY ca.id DESC LIMIT 5";

            $callsQueryStmt = $connection->prepare($callsQuery);
            $callsQueryStmt->execute(['id' => $_GET['id']]);
            $calls = $callsQueryStmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($calls)) {

                foreach ($calls as $call) {
                    echo "<tr>
                    <td>{$call["id"]}</td>
                    <td>{$call["user"]}</td>
                    <td>{$call["clientName"]}</td>
                    <td>{$call["clientType"]}</td>
                    <td>{$call['date']}</td>
                    <td>{$call['duration']}</td>
                    <td>{$call['callType']}</td>
                    <td>{$call['score']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }

            ?>
        </tbody>
    </table>
</div>

<a href="<?= APP_URL ?>index.php" class="btn btn-primary m-5">Go back</a>

<?php require_once __DIR__ . "/parts/footer.php"; ?>