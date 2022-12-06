<?php

##### TASK 10

if (isset($_POST['form']) && $_POST['form'] == 'task10') {

    $pdo = new PDO('mysql:dbname=md0_lesson_10;host=localhost', 'root', 'root');

    $sql = $pdo->prepare("INSERT INTO texts (value) VALUES (:txt)");
    $sql->execute([
        'txt' => $_POST['txt'],
    ]);


    # END
    header("Location: /task_10.php");
    die();

}