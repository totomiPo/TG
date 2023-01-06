<?php
include("db.php");

$err = [];
$messages = selectAll('dialog');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topcr'])){
    $name = trim($_POST['name']);
    if($name === ''){
        array_push($err, "Поле не заполнено!");
    }elseif (mb_strlen($name, 'UTF8') < 3){
        array_push($err, "Название должно быть больше 2-ух символов!");
    }else{
        $row = selectOne('dialog', ['name' => $name]);
        if(!empty($row['name']) && $row['name'] === $name){
            array_push($err, "Такой диалог существует!");
        }else{
            $dialog = [
                'name' => $name
            ];
            $id = insert('dialog', $dialog);
            $info = selectOne('dialog', ['id' => $id]);
            header('location: http://tg/dialog.php?id=' . $id);
        }
    }
}else{
    $name = '';
}
