<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Корнешон</title>
  <link rel="stylesheet" href="./main.css">
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
<div style="display: flex; margin-left:5%; margin-top:2%; flex-direction: row;">
<?php
if ($_SESSION['access']==1){ 
      echo "<div><form style='padding-right: 20px;' action='vedom.php'><button>Просмотр ведомости</button></form></div>";
      echo "<div><form style='padding-right: 20px;' action='add_group.php'><button>Добавить группу</button></form></div>";
}
?>
</div>
<div style="margin-left:5%; margin-right:20%; width:60%;">
<form method='POST' action='delete.php'>
<?php
if ($_SESSION['access']==1) echo "<button style='margin-bottom: 20px;' type = 'submit'>Удалить</button>";
?>

      <table class='tb' border = 10>
            <tr>
                  <?php
                  echo "<th style='padding-top:1%; padding-bottom:1%; background-color: rgba(200, 255, 207, 0.87)'>Группа</th>";
                  echo "<th style='padding-top:1%; padding-bottom:1%; background-color: rgba(200, 255, 207, 0.87)'> Просмотр</th>";
                  if ($_SESSION['access']==1) echo "<th style='padding-top:1%; padding-bottom:1%; background-color: rgba(200, 255, 207, 0.87)'>Удалить</th>";
                  ?>
            </tr>
            <?php
                  $query_groups = mysqli_query($connect, "SELECT * FROM `groups`");
                  while ($row_group = mysqli_fetch_assoc($query_groups)){
                        echo "<tr>";
                        echo "<td style='padding: 5px;text-align:center; background-color: rgba(222, 222, 222, 0.87)'>".$row_group['group_name']."</td>";
                        echo "<style>a{color: black} a:hover{color: rgba(75, 176, 36, 0.87)}</style>";
                        echo "<td style='padding: 5px;text-align:center; background-color: rgba(222, 222, 222, 0.87);'><a style='text-decoration: none;' href = 'view.php?gp=".$row_group['group_id']."'>Просмотр</a></td>";
                        if ($_SESSION['access']==1) echo "<td style='padding: 5px;text-align:center; background-color: rgba(222, 222, 222, 0.87)';><input name = 'delete[]' type = 'checkbox' value = '".$row_group['group_id']."'/>";
                        echo "</tr>";
                  };
                  mysqli_close($connect);
            ?>
      </table>
</form>
</div>

</body>
</html>