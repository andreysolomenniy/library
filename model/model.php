<?php
/* is not finished... */

class model {

  private $mysqly;
  
  public function connect() {
    $this->mysqli = new mysqli('localhost','root','1','library');
    if ($this->mysqli->connect_errno) {
      header ('Location: /index.php/?res=2&msg=Не удалось подключиться к MySQL: ' . $mysqli->connect_error);
      exit;
    }
    if (! $this->mysqli->set_charset("utf8")) {
      header ('Location: /index.php/?res=2&msg=Ошибка установки набора символов utf8: ' . $mysqli->error);
      exit;
    }
  }

  function __construct() {
    if (! isset($this->mysqli))
      $this->connect();
  }
  
  public function get_book_count() {
    $res = $this->mysqli->query("SELECT COUNT(*) As num FROM book");
    if ($res === false) {
      header ('Location: /index.php/?res=2&msg=Не удалось выполнить запрос к БД: ' . $mysqli->error);
      return 0;
    }
    $row = $res->fetch_assoc();
    return isset($row['num']) ? $row['num'] : 0;
  }

  public function get_book_rows($from, $max_row) {
    $res = $this->mysqli->query("SELECT b.id, b.name FROM book b ORDER BY b.name LIMIT $from, $max_row");
    if ($res === false) {
      header ('Location: /index.php/?res=2&msg=Не удалось выполнить запрос к БД: ' . $mysqli->error);
      return 0;
    }
    $i = 1;
    $list = array();
    while ($row = $res->fetch_assoc()) {
      $list[$i] = $row;
      $i++;
    }
    return $list;
  }
  
  public function get_book_authors($id_book) {
    $res = $this->mysqli->query("SELECT a.id, a.name AS author_name FROM book_authors ba WHERE ba.id_book = $id_book LEFT JOIN author a ON ba.id_author = a.id");
    if ($res === false) {
      header ('Location: /index.php/?res=2&msg=Не удалось выполнить запрос к БД: ' . $mysqli->error);
      return 0;
    }
    $i = 1;
    $list = array();
    while ($row = $res->fetch_assoc()) {
      $list[$i] = $row;
      $i++;
    }
    return $list;
  }

  public function get_book($id_book) {
    $res = $this->mysqli->query("SELECT b.id, b.name FROM book b WHERE id = $id_book");
    if ($res === false) {
      header ('Location: /index.php/?res=2&msg=Не удалось выполнить запрос к БД: ' . $mysqli->error);
      return 0;
    }
    return $res->fetch_assoc();
  }
  
  public function add_book($row) {
    $this->mysqli->query("INSERT INTO book (name) VALUES ('${row['name']}')");
    if ($res === false) {
      return false;
    }
    return true;
  }

  public function update_book($row) {
    $this->mysqli->query("UPDATE book SET name='${row['last_name']}' WHERE id = ${row['id']}}");
    if ($res === false) {
      return false;
    }
    return true;
  }

  public function error() {
    return $this->mysqli->error;
  }
}
?>
