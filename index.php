<?php

use App\Import\Import as Import;
use App\Database\DB as DB;

require_once __DIR__ . "/database/DB.php";
require_once __DIR__ . "/pages/parts/header.php"; 
require_once __DIR__ . "/helpers/Import.php";
require_once __DIR__ . "/pages/parts/footer.php";


Import::importFile();

//import notifications
if (isset($_SESSION['import_success'])) {
    echo "<p class='alert alert-success'>Successfully imported file!</p>";
    unset($_SESSION['import_success']);
} else if (isset($_SESSION['import_warning'])) {
    echo "<p class='alert alert-warning'>Some problem occurred, please try again.</p>";
    unset($_SESSION['import_warning']);
} else if (isset($_SESSION['import_invalid'])) {
    echo "<p class='alert alert-danger'>Please upload a valid CSV file!</p>";
    unset($_SESSION['import_invalid']);
}

//delete notifications
if (isset($_SESSION['delete_success'])) {
    echo "<p class='alert alert-success'>The call was successfully deleted.</p>";
    unset($_SESSION['delete_success']);
} else if(isset($_SESSION['delete_error'])) {
    echo "<p class='alert alert-danger>The call could not be deleted.</p>";
    unset($_SESSION['delete_error']);
}

//update notifications
if (isset($_SESSION['update_success'])) {
    echo "<p class='alert alert-success'>The call was successfully updated.</p>";
    unset($_SESSION['update_success']);
} else if(isset($_SESSION['update_error'])) {
    echo "<p class='alert alert-danger>The call could not be updated.</p>";
    unset($_SESSION['update_error']);
}

//add notifications
if (isset($_SESSION['add_success'])) {
    echo "<p class='alert alert-success'>The call was successfully added.</p>";
    unset($_SESSION['add_success']);
} else if(isset($_SESSION['add_error'])) {
    echo "<p class='alert alert-danger>The call could not be added.</p>";
    unset($_SESSION['add_error']);
}

?>

<?php if(!empty($statusMsg)){ ?>
<div class="col-xs-12">
    <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<?php require_once __DIR__ . "/pages/parts/header.php"; ?>

<div class="container">
    <p class="h1">Please upload your CSV file</p>
    <div class="col-md-12">
        <div class="float-right d-flex">
            <button class="btn btn-success mr-4" onclick="formToggle('import');"><i class="plus"></i> Import</button>
            <form action="" method="POST">
                <a href="pages/add.php" class="btn btn-primary">Add</a>
            </form>
        </div>
    </div>
    <div class="row mt-5" id="import" style="display: none;">
        <div class="col-2 p-3">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" accept=".csv">
                <button class="btn btn-primary mt-3" name="importSubmit">Upload CSV</button>
            </form>
        </div>
        <hr>
    </div>
</div>

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
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $connection = DB::connect();

            $callsQuery = "SELECT ca.id AS id, ca.date AS date, ca.duration AS duration, ca.type AS callType, 
                                ca.score as score, cl.name AS clientName, cl.type AS clientType, u.user AS user, ca.user_id as user_id
                            FROM calls ca
                            LEFT JOIN users u on u.id = ca.user_id
                            LEFT JOIN clients cl on cl.id = ca.client_id
                            ORDER BY ca.id ASC";

            $callsQueryStmt = $connection->prepare($callsQuery);
            $callsQueryStmt->execute();
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
                    <td> 
                        <a href='pages/view.php?id={$call['user_id']}' class='btn btn-primary mr-3'>View</a>
                        <a href='pages/edit.php?id={$call['id']}' class='btn btn-success mr-3'>Edit</a>
                        <a href='pages/delete.php?id={$call['id']}' class='btn btn-danger'>Delete</a>
                    </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }

            ?>
        </tbody>
    </table>
</div>

<script src="js/import.js"></script>
<?php require_once __DIR__ . "/pages/parts/footer.php"; ?>
