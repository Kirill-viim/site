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
if (!isset($_SESSION['vedom'])) $_SESSION['vedom'] = [];
if (isset($_POST['add'])){
  $add_id = $_POST['add'];
  if (!in_array($add_id, $_SESSION['vedom'])) array_push($_SESSION['vedom'], $add_id);
};
if (isset($_GET['gp'])) $_SESSION['gp'] = $_GET['gp'];
$group_id = $_SESSION['gp'];
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

<div style="padding-left:2%; padding-top:5px; padding-bottom:5px; margin-top:10px; background-color: rgba(199, 255, 146, 0.87)">
<?php  
  $group_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE group_id='$group_id'");
  $row_group = mysqli_fetch_assoc($group_query);
  echo "<p>";
  if ($_SESSION['access']==1) echo "<b style='margin-left:2%; font-size:20px;'>id Группы</b> <font style='margin-left:1%; margin-right:3%; font-size:16px'>".$row_group['group_id']."</font> <b>|</b> ";
  echo "<b style='margin-left:2%; font-size:20px;'>Группа</b> <font style='margin-left:1%; margin-right:3%; font-size:16px; padding-bottom: 50px'>".$row_group['group_name']."</font> <b>|</b> ";
  $gp_id = $row_group['group_id'];
  $students_count = mysqli_query($connect, "SELECT COUNT('stud_id') as k FROM students WHERE gp='$group_id'");
  $count = mysqli_fetch_assoc($students_count);
  echo "<b style='margin-left:2%; font-size:20px;;'>Количество студентов</b> <font style='margin-left:1%; font-size:16px'>".$count['k']."</font>";
  echo "</p>";
  mysqli_free_result($students_count);
  mysqli_free_result($group_query);
?>
</div>

<div style="display: flex; width:95%; margin-left:5%; flex-direction: row; padding-top:1%; margin-top:1%">
<?php
if ($_SESSION['access']==1){
  echo "<div><form style='padding-right: 20px;' action='redact.php' method='post'>";
  echo "<input type = 'hidden' name = 'id' value = '$group_id'>";
  echo "<button type='submit'>Редактировать</button></form></div>";
  echo "<div><form style='padding-right: 20px;' action='add_stud.php'><button>Добавить студента</button></form></div>";
  echo "<div><form style='' action='del_stud.php'><button>Удалить студента</button></form></div>";
}
?>
</div>

<table style=" margin-left:5%; " border = 5>
  <tr>
    <?php
    if ($_SESSION['access']==1) echo "<th style=' background-color: rgba(200, 255, 207, 0.87);padding:5px;'>id Студента</th>";
    echo "<th style=' background-color: rgba(200, 255, 207, 0.87);padding:5px;'>ФИО</th>";
    if ($_SESSION['access']==1) echo "<th style='background-color: rgba(200, 255, 207, 0.87);padding:5px;'>Добавить в ведомость</th>";
    ?>
  </tr>
<?php  
  $student_query = mysqli_query($connect, "SELECT * FROM `students` WHERE gp='$group_id' ORDER BY `students`.`fio` ASC");
  while ($row_student = mysqli_fetch_assoc($student_query)){
    echo "<tr>";
    if ($_SESSION['access']==1) echo "<td style='text-align:center; background-color: rgba(222, 222, 222, 0.87)'>".$row_student['stud_id']."</td>";
    echo "<td style='padding:10px; padding-right:30px; background-color: rgba(222, 222, 222, 0.87)'>".$row_student['fio']."</td>";
    if ($_SESSION['access']==1){
      $stud_id = $row_student['stud_id'];
      echo "<td><form style='text-align:center; padding:5%; margin:0; background-color: rgba(222, 222, 222, 0.87)' method = 'post'>";
      echo "<button type = submit name = 'add' value='$stud_id'>Добавить</button>";
      echo "</form></td>";
    };
    echo "</tr>";
  };
  mysqli_free_result($student_query);
?>
</table>

<br>
<form style='margin-left:5%;' action='index.php'>
  <button>Назад</button>
</form>
<?php
  mysqli_close($connect);
?>
</body>
</html>