<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Хинкаля</title>
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
if (isset($_POST['clear'])) $_SESSION['vedom'] = [];
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
<form method='POST' action='delete.php'>
<table style="margin-left:25%; margin-right:25%;  margin-top:2%;width:50%;" border = 10>
      <tr>
            <?php
            echo "<th style='background-color: rgba(200, 255, 207, 0.87)'>Группа</th>";
            echo "<th style='background-color: rgba(200, 255, 207, 0.87)'>ФИО</th>";
            ?>
      </tr>
      <?php
      $all_groups = mysqli_query($connect, "SELECT * FROM `groups` ORDER BY `groups`.`group_name` ASC");
      while ($group =  mysqli_fetch_assoc($all_groups)){
            $gp_id = $group['group_id'];
            $students_query = mysqli_query($connect, "SELECT * FROM `students` WHERE gp='$gp_id' ORDER BY `students`.`fio` ASC");
            while ($student = mysqli_fetch_assoc($students_query)){
                  if (in_array($student['stud_id'], $_SESSION['vedom'])){
                        echo "<tr>";
                        echo "<td style=' background-color: rgba(222, 222, 222, 0.87); text-align:center'>".$group['group_name']."</td>";
                        echo "<td style=' background-color: rgba(222, 222, 222, 0.87); text-align:center; width: 70%;'>".$student['fio']."</td>";
                        echo "</tr>";
                  };
            };
            mysqli_free_result($students_query);
      };
      mysqli_free_result($all_groups);
      ?>
</table>
</form>
<form style="margin-left:25%;" method='post'>
  <button type='submit' name='clear'>Очистить ведомость</button>
</form>
<br>
<form style="margin-left:25%;" action='index.php'>
  <button>Назад</button>
</form>

<?php
    mysqli_close($connect);
?>
</body>
</html>