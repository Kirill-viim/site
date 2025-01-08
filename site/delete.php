<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Чечевичка</title>
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

<?php
if (isset($_POST['delete']) && isset($_POST['confirmed'])){
  $group_ids = $_POST['delete'];
  foreach ($group_ids as $id) mysqli_query($connect, "DELETE FROM `groups` WHERE group_id='$id'");
  header("Location: index.php");
}
elseif (isset($_POST['delete'])){
  $group_ids = $_POST['delete'];
?>
<form method='POST'> 
<table style="margin-left:20%; margin-right:20%; margin-top:2%; width:60%;" border = 10>
  <tr>
    <th>
      id Группы
    </th>
    <th>
      Группа
    </th>
    <th>
      Удалить
    </th>
  </tr>
<?php  
foreach ($group_ids as $id){
  $groups_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE group_id='$id'");
  $row_group = mysqli_fetch_assoc($groups_query);
  echo "<tr>";
  echo "<td style='text-align:center'>".$row_group['group_id']."</td>";
  echo "<td style='text-align:center'>".$row_group['group_name']."</td>";
  $students_count = mysqli_query($connect, "SELECT COUNT('stud_id') as k FROM students WHERE gp='$id'");
  $count = mysqli_fetch_assoc($students_count);
  if ($count['k']==0) echo "<td style='text-align:center'><input name = 'delete[]' type = 'checkbox' value = '".$id."' checked /></td>";
  else echo "<td style='text-align:center'>В группе имеются студенты!</td>";
  echo "</tr>";
  mysqli_free_result($groups_query);
};
?>
</table>
<br>
<button style="margin-left:20%;" type='submit' name='confirmed'>Подтвердить</button>
</form>
<form style="margin-left:20%;" action='index.php'>
  <button>Назад</button>
</form>
<?php
}
else header("Location: index.php");
?>
<?php
  mysqli_close($connect);
?>
</body>
</html>