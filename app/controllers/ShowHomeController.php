<?php
require_once dirname(__DIR__, 2)."/config/init.php";
require_once ROOT_PATH."/app/models/showHomeModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Add'])) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];

    $result = addShowhome($title, $category_id) ;
    if ($result){
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=showHome");
    exit();
    } else echo $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Edit'])) {
    $list_id = $_POST['list_id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];

    $result = updateShowhome($list_id,$title, $category_id);
    if ($result){
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=showHome");
    exit();
    } else echo $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete'])) {
    $list_id = $_POST['list_id'];

    $result = deleteShowhome($list_id);
    if ($result){
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=showHome");
    exit();
    } else echo $result;
}