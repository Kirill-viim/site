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
</div>

<?php
if (!isset($_POST['email'])){
    echo "<h2>Запрос активации аккаунта</h2>";
    echo "<form action='activation.php' method='post'>";
    echo "<label for='email'>Email:</label>";
    echo "<input type='email' name='email' required><br><br>";
    echo "<input type='submit' value='Отправить письмо с активацией'>";
    echo "</form>";
    exit;
};

// Получение данных из формы
$email = $_POST["email"];
$_SESSION['email'] = $_POST["email"];
    
// Подключение к базе данных
$host = 'MySQL-8.2'; // Замените на имя хоста вашего сервера
$user = 'root';      // Замените на имя вашего пользователя MySQL
$pass = '';          // Замените на пароль вашего пользователя MySQL
$db = 'lab11';       // Замените на имя вашей базы данных
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Подключаем автозагрузчик, который был создан Composer'ом
require 'vendor/autoload.php';

try {
$mail = new PHPMailer(true);
// Настройки SMTP
$mail->isSMTP();
$mail->Host = 'smtp.mail.ru';  // Замените на ваш SMTP-сервер
$mail->SMTPAuth = true;
$mail->Username = 'kirvened@mail.ru'; // Замените на ваш email
// Пароль приложения Gmail
$mail->Password = 'TQw0M8Wjddjt7rkJGgwe'; // Замените на ваш пароль или пароль приложения
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Используем STARTTLS
$mail->Port = 587;  // Порт для STARTTLS

// Настройки письма
$mail->setFrom('kirvened@mail.ru', 'Cool Guy'); // Замените на ваш email и имя
$mail->addAddress($email, 'Recipient Name'); // Замените на email и имя получателя

// Генерация токена активации
$token = bin2hex(random_bytes(32));

// Обновление токена в базе данных
$stmt = $conn->prepare("UPDATE users SET activ_token = :token WHERE id = :id");
$stmt->bindParam(':token', $token, PDO::PARAM_STR);
$stmt->bindParam(':id', $_SESSION['user'], PDO::PARAM_INT);
$stmt->execute();

// Отправка письма с токеном
$activation_link = "https://site/activation2.php?token=" . $token; // ссылка
$message = "<p>Please, click the link for account activation:</p><a href='https://site/activation2.php'>Activate</a>";

$mail->Subject = 'Account activation';
$mail->Body = $message;
$mail->isHTML(true); // Если письмо в HTML формате
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Выводит подробную отладочную информацию
//  $mail->AltBody = 'Альтернативный текст, для тех, кто не может просмотреть HTML';

$mail->send();
echo '<br>Письмо с активацией вашего аккаунта успешно отправлено. Проверьте почту.';
} catch (Exception $e) {
    echo "Ошибка отправки письма: {$mail->ErrorInfo}";
}

$conn = null;
?>
</body>
</html>