<?php
include_once('../model/model.php');
include_once('./templ.php');

$data = array();

$model = new model();

$action = isset($_GET["action"]) ? (integer) htmlspecialchars($_GET["action"]) : 1;
$data['action'] = (string)$action;
$data['mode_name'] = ($action == 1)? 'Добавление новой книги' : 'Редактирование книги';

if ($action == '2') {
  $id_book = isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : 0;
  $data['id_book'] = $id_book;
  $row = $model->get_book($id_book);
  if ($row === false) {
    echo 'Не удалось выполнить запрос к БД: ' . $model-error();
    exit;
  }
  $data['name'] = $row['name'];
} else {
  $id_book = '1';
  $data['id_book'] = $id_book;
  $data['name'] = '';
}

$total_rows = $model->get_author_count();
$ds_authors = $model->get_authors_selected($id_book);

$authors = array();
for($i = 1; $i <= $total_rows; $i++) {
  $a = array();
  $a['id'] = $ds_authors[$i]['id'];
  $a['name'] = $ds_authors[$i]['name'];
  if ($action == 2)
    $a['selected'] = is_null($ds_authors[$i]['selected']) ? '' : 'selected';
  else
    $a['selected'] = '';
  
  $authors[$i] = $a;
}

$data['authors'] = $authors;

$tmpl = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/view/book_form.html');
process_template($tmpl, $data);
?>
