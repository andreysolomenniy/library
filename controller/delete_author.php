<?php
include_once('../model/model.php');

$id_author = isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : 0;

$model = new model();

if ($model->is_author_used($id_author) === false) {
  if ($model->delete_author($id_author))
    header ('Location: /index.php?ctab=2');
  else
    echo 'Не удалось выполнить запрос к БД: ' . $model->error();
} else {
  echo 'Данного автора нельзя удалить, поскольку он привязан к книге.';
}

?>
