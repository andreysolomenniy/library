<?php
include_once('../model/model.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id_book = isset($_POST["id_book"]) ? htmlspecialchars($_POST["id_book"]) : 0;
  $author_name = isset($_POST["author"]) ? htmlspecialchars($_POST["author"]) : 0;

  $model = new model();

  $id_author = $model->get_id_author_by_name($author_name);
  if ($id_author === false) {
    echo 'Для данного автора не нашёлся идентификатор.<cr>';
    exit;
  }  
  if ($model->add_book_author($id_book, $id_author))
    header ('Location: /index.php?ctab=1');
  else
    echo 'Не удалось выполнить запрос к БД: ' . $model->error();
}
?>
