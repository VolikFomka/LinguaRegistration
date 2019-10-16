<?php
    session_start();
if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } 
    if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
    
if (empty($login) or empty($password))
    {
    exit ("You enter not full information turn back and fill all field!");
    }
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
$password = stripslashes($password);
    $password = htmlspecialchars($password);
//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);
// подключаемся к базе
    include ("bd.php");
 
$result = mysqli_query($db,"SELECT * FROM users WHERE login='$login'"); //извлекаем из базы все данные о пользователе с введенным логином
    $myrow = mysqli_fetch_array($result);
    if (empty($myrow['password'])) {
    //если пользователя с введенным логином не существует
    exit ("You enter login or password was wrong try again");
    }
    else {
    //если существует, то сверяем пароли
    if ($myrow['password']==$password) { 
    //если пароли совпадают, то запускаем пользователю сессию
    $_SESSION['login']=$myrow['login']; 
    $_SESSION['id']=$myrow['id'];
    echo "You enjoy in site <a href='index.php'>Главная страница</a>";
    }
 else {
    //если пароли не сошлись

    exit ("You enter login or password was wrong try again");
    }
    }
    ?>