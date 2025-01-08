<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Чак-чак</title>
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
if (isset($_POST['reg'])){
      $surname = $_POST['surname'];
      $name = $_POST['name'];
      $log = $_POST['log'];
      $pas = $_POST['pas'];
      $hash = md5($pas);
      mysqli_query($connect, "INSERT INTO `users` (`id`, `surname`, `name`, `login`, `password`, `access`, `email`, `activ_token`, `active`) VALUES (NULL, '$surname', '$name', '$log', '$hash', 0, NULL, NULL, 0)");
      echo "<script>alert('Вы успешно зарегистрировались')</script>";
      header("Location: avtoriz.php");
}
?>
<div style="margin-left:30%; margin-right:30%; width:40%; margin-top: 5%; background-color: rgba(129, 255, 101, 0.87)">
  <div style="text-align:center; padding-top: 5%; padding-bottom: 5%;">
    <h2>Регистрация</h2>
    <form method='post'>
      <label for="surname">Фамилия</label>
      <input type='text' name='surname'><br><br>
      <label for="name">Имя</label>
      <input type='text' name='name'><br><br>
      <label for="log">Логин</label>
      <input type='text' name='log'><br><br>
      <label for="pas">Пароль</label>
      <input type='text' name='pas'><br><br>
      <button type='submit' name='reg'>Зарегистрироваться</button>
    </form>
  </div>
  <form style="padding-left:2%; padding-bottom:2%;" action='avtoriz.php'>
    <button>Назад</button>
  </form>
</div>
<?php
    mysqli_close($connect);
?>
</body>
</html>