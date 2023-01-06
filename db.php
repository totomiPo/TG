<?php

session_start();
$dr = 'mysql';
$host = 'tg';
$db_name = 'chat';
$dbus = 'root';
$dbps = '';
$charset = 'utf8';
$opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
try{
    $pdo = new PDO(
        "$dr:host=$host;dbname=$db_name; charset=$charset",
        $dbus, $dbps, $opt
    );
}catch (PDOEception $i){
    die("Ошибка подключения к базе данных");
}

function dbCheck($query){
    // Ошибка - массив
    $errInfo = $query->errorInfo();
    if ($errInfo[0] !== PDO::ERR_NONE){
        echo $errInfo[2];
        exit();
    }
    return true;
}

function selectOne($table, $params = []){
    global $pdo;

    $sql = "SELECT * FROM $table";
    if(!empty($params)){
        $i = 0;
        foreach ($params as $key => $value){
            if (!is_numeric($value)){
                $value = "'".$value."'";
            }
            if ($i === 0){
                $sql = $sql . " WHERE $key = $value";
            }else{
                $sql = $sql . " AND $key = $value";
            }
            $i++;
        }
    }
    $query = $pdo->prepare($sql);
    $query->execute();
    dbCheck($query);
    return $query->fetch();
}

function selectAll($table, $params = []){
    global $pdo;

    $sql = "SELECT * FROM $table";
    if(!empty($params)){
        $i = 0;
        foreach ($params as $key => $value){
            if (!is_numeric($value)){
                $value = "'".$value."'";
            }
            if ($i === 0){
                $sql = $sql . " WHERE $key = $value";
            }else{
                $sql = $sql . " AND $key = $value";
            }
            $i++;
        }
    }
    $query = $pdo->prepare($sql);
    $query->execute();
    dbCheck($query);
    return $query->fetchAll();
}

function insert($table, $params){
    global $pdo;

    $i = 0;
    $coll = ''; // Столбец бд
    $mask = ''; // Значение
    foreach ($params as $key => $value) {
        if ($i === 0){
            $coll = $coll . "$key";
            $mask = $mask . "'" ."$value" . "'";
        }else {
            $coll = $coll . ", $key";
            $mask = $mask . ", '" . "$value" . "'";
        }
        $i++;
    }

    $sql = "INSERT INTO $table ($coll) VALUES ($mask)";
    $query = $pdo->prepare($sql);
    $query->execute();
    dbCheck($query);
    // Возврат ID добавленной записи
    return $pdo->lastInsertId();
}

 ?>
