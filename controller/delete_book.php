<?php
include_once('../model/model.php');

$id_book = isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : 0;

$model = new model();

if ($model->delete_book($id_book))
    header ("Location: /index.php?ctab=1");
else
    echo 'Не удалось выполнить запрос к БД: ' . $model->error();
?>
