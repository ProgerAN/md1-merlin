<?php
session_start();

####
##### TASK 10
####

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

####
##### TASK 11
####

if (isset($_POST['form']) && $_POST['form'] == 'task11') {

    $pdo = new PDO('mysql:dbname=md0_lesson_10;host=localhost', 'root', 'root');

    //    $sql = $pdo->prepare("INSERT INTO texts (value) VALUES (:txt)");
    //    $sql->execute([
    //        'txt' => $_POST['txt'],
    //    ]);

    $textCheck = $pdo->prepare('SELECT id FROM texts WHERE `value` =:txt LIMIT 1');
    $textCheck->bindValue(':txt', $_POST['txt'], PDO::PARAM_INT);
    $textCheck->execute();

    if (!$textCheck->fetchColumn()) {

        $sql = $pdo->prepare("INSERT INTO texts (value) VALUES (:txt)");
        $sql->execute([
            'txt' => $_POST['txt'],
        ]);

        $_SESSION['flash_message'] = [
            'status' => 'success',
            'mess' => 'текст `' . $_POST['txt'] . '` успешно добавлен'
        ];

    } else {
        $_SESSION['flash_message'] = [
            'status' => 'warning',
            'mess' => 'такой текст уже есть в базе'
        ];
    }

    # END
    header("Location: /task_11.php");
    die();
}

####
##### TASK 13
####

if (isset($_POST['form']) && $_POST['form'] == 'task13') {

    $_SESSION['flash_message'] = [
        'status' => 'warning',
        'mess' => 'такой текст уже есть в базе'
    ];

    # END
    header("Location: /task_13.php");
    die();

}

####
##### TASK 14 "button_counter"
####

if (isset($_POST['form']) && $_POST['form'] == 'task14') {

    $_SESSION['button_counter'] = $_SESSION['button_counter'] + 1;

    # END
    header("Location: /task_14.php");
    die();

}

####
##### TASK 15 "Login"
####

if (isset($_POST['form']) && $_POST['form'] == 'task15') {

    $email  = $_POST['email'];
    $passwd = $_POST['passwd'];

    // mail exp@mail.com
    // pass admin

    if ($email == 'exp@mail.com') {

        if (password_verify($passwd, '$2y$10$/tVmmB1LL1LOlLzrz1pk..mWGk.O8dthp29yq8geuh8LM3jG9hlf6')) {

            $_SESSION['user'] = [
                'email' => $email,
            ];

            header("Location: /task_16.php");
            die();

        } else {
            $_SESSION['flash_message'] = [
                'status' => 'warning',
                'mess' => 'Пароль не верный'
            ];
        }

    } else {
        $_SESSION['flash_message'] = [
            'status' => 'warning',
            'mess' => 'Логин не верный'
        ];
    }

    # END
    header("Location: /task_15.php");
    die();

}

####
##### TASK 17 18 19 "IMG Upload"
####

function uploadFile ($fileTmpName, $errorType) {

    if ($_FILES['image']['error'] == UPLOAD_ERR_OK || is_uploaded_file($fileTmpName)) {

        $fi = finfo_open(FILEINFO_MIME_TYPE);
        $mime = (string) finfo_file($fi, $fileTmpName);

        if (strpos($mime, 'image') !== false) {

            $image = getimagesize($fileTmpName);
            $extension = image_type_to_extension($image[2]);
            $format = str_replace('jpeg', 'jpg', $extension);
            $uID_path = uniqid();

            if (move_uploaded_file($fileTmpName, __DIR__ . '/uploads/' . $uID_path . $format)) {

                $pdo = new PDO('mysql:dbname=md0_lesson_10;host=localhost', 'root', 'root');
                $sql = $pdo->prepare("INSERT INTO images (uID) VALUES (:uID)");
                $sql->execute([
                    'uID' => $uID_path.$format,
                ]);

                $_SESSION['flash_message'] = [
                    'status' => 'success',
                    'mess' => 'Картинка ~'.$_FILES['img']['name'].'~ успешно добавлена'
                ];

            } else {
                $_SESSION['flash_message'] = [
                    'status' => 'danger',
                    'mess' => 'Ошибка при загрузки файла : : Ошибка записи'
                ];
            }

        } else {
            $_SESSION['flash_message'] = [
                'status' => 'danger',
                'mess' => 'Ошибка при загрузки файла : : Загружаемый фаил не являться картинкой'
            ];
        }

    } else {
        $_SESSION['flash_message'] = [
            'status' => 'danger',
            'mess' => 'Ошибка при загрузки файла : : ' . $errorType['image']['error']
        ];
    }

}

####
##### TASK 17 "IMG Upload"
####

if (isset($_POST['form']) && $_POST['form'] == 'task17') {

    $img = $_FILES['img'];

    uploadFile($_FILES['img']['tmp_name'], $_FILES['img']['error']);

    # END
    header("Location: /task_17.php");
    die();

}

####
##### TASK 18 (17.2) "IMG Upload"
####

if (isset($_POST['form']) && $_POST['form'] == 'task18') {

    $img = $_FILES['img'];

    uploadFile($_FILES['img']['tmp_name'], $_FILES['img']['error']);

    # END
    header("Location: /task_18.php");
    die();

}

####
##### TASK 19 "IMG Upload"
####

if (isset($_POST['form']) && $_POST['form'] == 'task19') {

    $img = $_FILES['img'];

    for ($i=0; $i < count($_FILES['img']['name']); $i++) {
        uploadFile($_FILES['img']['tmp_name'][$i], $_FILES['img']['error'][$i]);
    }

    # END
    header("Location: /task_19.php");
    die();

}

####
##### TASK 18 (17.2) "IMG Upload"
####

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    if ($id){

        $pdo = new PDO('mysql:dbname=md0_lesson_10;host=localhost', 'root', 'root');

        $imgCheck = $pdo->prepare('SELECT * FROM images WHERE `id` =:id LIMIT 1');
        $imgCheck->bindValue(':id', $id, PDO::PARAM_INT);
        $imgCheck->execute();
        $imgRow = $imgCheck->fetch();

        if ($imgRow){

            unlink(__DIR__ . '/uploads/'.$imgRow['uID']);

            $imgDel= $pdo->prepare("DELETE FROM images WHERE `id` =:id");
            $imgDel->bindValue(':id', $imgRow['id'], PDO::PARAM_INT);
            $imgDel->execute();

            $_SESSION['flash_message'] = [
                'status' => 'success',
                'mess' => 'Картинка успешно удалена'
            ];

        } else {
            $_SESSION['flash_message'] = [
                'status' => 'danger',
                'mess' => 'Ошибка удаления : : Ошибка выборки'
            ];
        }
        
    } else {
        $_SESSION['flash_message'] = [
            'status' => 'danger',
            'mess' => 'Ошибка удаления : : Не указан ID'
        ];
    }

    # END
    header("Location: /task_18.php");
    die();

}

?>
Loading . . .
