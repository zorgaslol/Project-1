<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


//Create an array for holding tasks if not already exists one.
if(!isset($_SESSION["tasks"])){
    $_SESSION["tasks"] = [];
}


//Tell the server how to react if it gets a POST request
if($_SERVER["REQUEST_METHOD"] === "POST"){

    
    //Adding tasks to the array
    $pavadinimas = $_POST["pavadinimas"];
    $aprasymas = $_POST["aprasymas"];

    if($pavadinimas === "" || $aprasymas === ""){
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["success" => false, "error" => "Fields cannot be empty"]);
        exit;
    }

    //How php responds if the requests asks to delete a task
    if (isset($_POST["delete"])) {
        foreach ($_SESSION["tasks"] as $i => $task) {
            if ($task["title"] === $pavadinimas && $task["description"] === $aprasymas) {
                    unset($_SESSION["tasks"][$i]);
                    $_SESSION["tasks"] = array_values($_SESSION["tasks"]);
                    break;
                }
        }

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["success" => true]);
    exit;
}




    //Create an associative array similiar to a js object, which holds the variables of each individual task
    $task = [
        "title" => $pavadinimas,
        "description" => $aprasymas,
    ];

    //Add the individual task objects to the tasks array found in the session array
    $_SESSION["tasks"][] = $task;

    // Telling the broswer that the response is a json
    header("Content-Type: application/json; charset=utf-8");

    //Response to js sending back the $task object
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
            <form  autocomplete="off" action="index.php" method="POST" id=taskForm>

            <label for="pavadinimas" >Pavadinimas:</label>
            <input type="text" id="pavadinimas" name="pavadinimas">

            <label for="aprasymas"> Aprasymas: </label>
            <input type="textarea" id="aprasymas" name="aprasymas">

            <button type="submit" >Prideti</button>
            </form>

            <div class="tasks">
                <ul class="task-list" id="taskList">
                    <?php foreach($_SESSION["tasks"] as $task): ?>
                    <li class="mainCard">
                        <div class="topCard"><?= htmlspecialchars($task['title']) ?></div>
                        <div class="bottomCard">
                        <div class="leftCard"><?= htmlspecialchars($task['description']) ?></div>
                        <div class="rightCard"><button class="delete-btn">Delete</button></div>
                        </div>
                    </li>
                    <?php endforeach; ?>

                </ul>
            </div>
        </div>
    </div>
<script src="script.js"></script>
</body>
</html>