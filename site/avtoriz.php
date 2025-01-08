<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Самса</title>
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
<div style="margin-left:30%; margin-right:30%; width:40%; text-align:center; margin-top: 5%;padding-top: 5%; padding-bottom: 5%; background-color: rgba(129, 255, 101, 0.87)">
  <h2>Авторизация</h2>
  <form method='post'>
    <label for="log">Логин</label>
    <?php
    if (isset($_POST['log'])) echo "<input type='text' name='log' value='".$_POST['log']."'>";
    else echo "<input type='text' name='log'>";
    ?>
    <br>
    <br>
    <label for="pas">Пароль</label>
    <?php
    if (isset($_POST['pas'])) echo "<input type='text' name='pas' value='".$_POST['pas']."'>";
    else echo "<input type='text' name='pas'>";
    ?>
    <br>
    <br>
    <button type='submit' name='enter'>Войти</button>
  </form>

  <form action='registr.php'>
  <button>Регистрация</button>
  </form>
</div>
<?php
session_start();
session_destroy();
session_start();
if (isset($_POST['enter'])){
      $log=$_POST['log'];
      $pas=$_POST['pas'];
      $hash=md5($pas);
      $user_query=mysqli_query($connect, "SELECT * FROM users WHERE login='$log' AND password='$hash'");
      $user = mysqli_fetch_assoc($user_query);
      if ($user) {
            $_SESSION['user'] = $user['id'];
            $_SESSION['access'] = $user['access'];
            $_SESSION['active'] = $user['active'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php");
      }
      else echo "<script>alert('Неверный логин или пароль')</script>";
      mysqli_free_result($user_query);
};
?>

<?php
    mysqli_close($connect);
?>
</body>
</html>