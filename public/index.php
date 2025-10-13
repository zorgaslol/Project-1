<?php
session_start();

if(!isset($_SESSION["tasks"])){
    $_SESSION["tasks"] = [];
}

if($_SERVER["REQUEST_METHOD"] === "POST"){

     if (isset($_POST["delete"])) {
        foreach ($_SESSION["tasks"] as $i => $task) {
            if ($task["title"] === $_POST["title"] &&
                $task["description"] === $_POST["description"]) {
                unset($_SESSION["tasks"][$i]);
                break;
            }
        }
        $_SESSION["tasks"] = array_values($_SESSION["tasks"]);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["success" => true]);
        exit;
    }


    $pavadinimas = $_POST["pavadinimas"];
    $aprasymas = $_POST["aprasymas"];

    $task = [
        "title" => $pavadinimas,
        "description" => $aprasymas
    ];

    $_SESSION['tasks'][] = $task;

    header("Content-Type: application/json; charset=utf8");

    echo json_encode($task);
    exit;

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Task Management System</title>
</head>
<body>
    <div class="wrapper">
        <div class="frame">
            <form action="index.php" method="POST" id=taskForm>

            <label for="pavadinimas" >Pavadinimas:</label>
            <input type="text" id="pavadinimas" name="pavadinimas">

            <label for="aprasymas"> Aprasymas: </label>
            <input type="text" id="aprasymas" name="aprasymas">

            <button type="submit" >Prideti</button>
            </form>

            <div class="tasks">
                <ul class="task-list" id="taskList">
                    <?php
                        foreach($_SESSION["tasks"] as $task) {
                            ?>
                            <li>
                                <h3><?php echo $task['title'] ?></h3>
                                <p><?php echo $task['description'] ?></p>
                                <button class="delete-btn">Delete</button>
                            </li>
                             <?php
                            
                        }?>
                </ul>
            </div>
        </div>
    </div>
<script src="script.js"></script>
</body>
</html>