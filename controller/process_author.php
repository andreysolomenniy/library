<?php
include_once('../model/model.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = isset($_POST["action"]) ? (integer) htmlspecialchars($_POST["action"]) : 1;
  $row = array();
  $row['id'] = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
  $row['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';

  $model = new model();
  if ($action == 1) {
    if ($model->add_author($row))
      header ("Location: /index.php?ctab=2");
    else
      echo 'Не удалось выполнить запрос к БД: ' . $model->error();
  } else {
    if ($model->update_author($row))
      header ("Location: /index.php?ctab=2");
    else
      echo 'Не удалось выполнить запрос к БД: ' . $model->error();
  }
}
?>
