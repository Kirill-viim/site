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
$group_id = $_POST['id'];
$error = '';
$change = TRUE;
if (isset($_POST['id']) && isset($_POST['redacted'])){
      $id = $_POST['id'];
      if ($_POST['redacted'] == 'cancel'){
            header("Location: view.php");
            exit;
      }
      if ($_POST['redacted'] == 'save') {
            $new_name = '';
            $new_name = $_POST['new_name'];
            if (strlen($new_name) == 0) $error = 'Поле не заполнено ('.strlen($new_name).')';
            elseif (strlen($new_name) > 10) $error = 'Название группы не должно превышать 10 символов ('.strlen($new_name).')';
            else{
                  $groups = mysqli_query($connect, "SELECT * FROM `groups`");
                  while ($group = mysqli_fetch_assoc($groups)){
                        if ($new_name == $group['group_name']){
                              $error = 'Группа с таким именем существует';
                              break;
                        };
                  };
            };
            if ($error == ''){
                  mysqli_query($connect, "UPDATE `groups` SET `group_name` = '$new_name' WHERE `groups`.`group_id` = '$id'");
                  $change = FALSE;
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
      $group_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE group_id='$group_id'");
      $row_group = mysqli_fetch_assoc($group_query);
      echo "<h1>Редактирование сведений о группе ".$row_group['group_name']."</h1>";
      mysqli_free_result($group_query);
      ?>

      <form method = 'post'>
      <?php
      echo "<input type='hidden' name='id' value='$group_id'>";
      ?>
      <label for="new_name">Новое название группы</label>
      <input type='text' name='new_name'>
      <?php
      echo "<font color='red'><b>".$error."<b></font><br>";
      ?>
      <br>
      <button type = 'submit' name='redacted' value='save'>Сохранить</button>
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