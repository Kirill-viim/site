<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Корнешон</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
<?php
  $host = 'MySQL-8.2';
  $user = 'root';
  $pass = '';
  $db = 'lab11';
  $connect = mysqli_connect($host, $user, $pass, $db);
?>

<?php
session_start();
$error = '';
$change = TRUE;
if (isset($_POST['redacted'])){
      if ($_POST['redacted'] == 'cancel'){
            header("Location: view.php");
            exit;
      }
      if ($_POST['redacted'] == 'save') {
            $del_id = '';
            $del_id = $_POST['del_id'];
            if (is_null($del_id)) $error = 'Поле не заполнено';
            if ($error == ''){
                  mysqli_query($connect, "DELETE FROM `students` WHERE stud_id='$del_id'");
                  header("Location: view.php");
                  exit;
            };
      };
};
?>

<?php
if (!isset($_SESSION['user'])) header("Location: avtoriz.php");
else{
      $id = $_SESSION['user'];
      $user_query=mysqli_query($connect, "SELECT surname, name FROM users WHERE id='$id'");
      $user = mysqli_fetch_assoc($user_query);
      $surname=$user['surname'];
      $name=$user['name'];
      mysqli_free_result($user_query);
};
?>
<div style="text-align: right; padding:1%; background-color: rgba(143, 185, 133, 0.87)">
<p>
      <?php
            echo "Вы вошли как <b>".$name." ".$surname."</b>";
            if ($_SESSION['active']==1) echo " (".$_SESSION['user_email'].")";
            else echo " (неактивный пользователь)";
            echo " / <a style='text-decoration:none; color:rgb(180, 33, 0)' href='avtoriz.php'>Выйти</a>";
      ?>
</p>
<form action='activation.php'>
<button>Активация</button>
</form>
</div>
<div style="margin-left:20%; margin-right:20%; width:60%;">
      <?php
      $group_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE group_id='".$_SESSION['gp']."'");
      $row_group = mysqli_fetch_assoc($group_query);
      echo "<h1>Удаление студентов группы ".$row_group['group_name']."</h1>";
      mysqli_free_result($group_query);
      ?>

      <form method = 'post'>
      <label for="del_id">ID студента</label>
      <select name="del_id"><br>
            <?php
                  $query_students = mysqli_query($connect, "SELECT * FROM students WHERE gp='".$_SESSION['gp']."'");
                  while ($row_studentd = mysqli_fetch_assoc($query_students)){
                        echo "<option>".$row_studentd['stud_id']."</option>";
                  };
                  mysqli_free_result($query_students);
            ?>
      </select>
      <?php
      echo "<font color='red'><b>".$error."<b></font><br>";
      ?>
      <br>
      <button type = 'submit' name='redacted' value='save'>Удалить</button>
      <br>
      <br>
      <button type = 'submit' name='redacted' value='cancel'>Назад</button>
      </form>
</div>

<?php
    mysqli_close($connect);
?>
</body>
</html>