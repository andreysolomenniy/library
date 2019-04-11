<?php
include_once('../model/model.php');
include_once('./templ.php');

$data = array();

$model = new model();

$action = isset($_GET["action"]) ? (integer) htmlspecialchars($_GET["action"]) : 1;
$data['action'] = (string)$action;
$data['mode_name'] = ($action == 1)? 'Добавление нового автора' : 'Редактирование автора';

if ($action == '2') {
  $id_author = isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : 0;
  $data['id'] = $id_author;
  $author_name = $model->get_author($id_author);
  if ($author_name === false) {
    echo 'Не удалось выполнить запрос к БД: ' . $model->error();
    exit;
  }
  $data['name'] = $author_name;
} else {
  $id_author = '0';
  $data['id'] = $id_author;
  $data['name'] = '';
}

$tmpl = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/author_form.html');
process_template($tmpl, $data);
?>
