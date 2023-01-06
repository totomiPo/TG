<?php

$db = new mysqli("localhost", "root", "", "chat");
if($db->connect_error){
    die("Ошибка подключения к базе данных!");
}

$res = array();
$login = isset($_POST['login']) ? trim($_POST['login']) : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;

if(!empty($message) || !empty($login)){
    $sql = "INSERT INTO `chat` (`message`, `login`) VALUES ('".$message."', '".$login."')";
    $res['status'] = $db->query($sql);
}

// Вывод сообщений

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$items = $db->query("SELECT * FROM `chat` WHERE `id` > " .$start);
while ($row = $items->fetch_assoc()) {
    //$row - ассоциативный массив, соответствующий ряду
    $res['items'][] = $row;
}

$db->close();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode($res);
 ?>
