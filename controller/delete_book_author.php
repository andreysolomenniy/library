<?php
include_once('../model/model.php');

$id_book = isset($_GET["id_book"]) ? htmlspecialchars($_GET["id_book"]) : 0;
$id_author = isset($_GET["id_author"]) ? htmlspecialchars($_GET["id_author"]) : 0;

$model = new model();

if ($model->delete_book_author($id_book, $id_author))
    header ('Location: /index.php?ctab=1');
else
    echo 'Не удалось выполнить запрос к БД: ' . $model->error();
?>
