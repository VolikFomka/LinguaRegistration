<?php
	if (isset ($_POST['login'])) { 
		$login = $_POST['login']; 
			if ($login == '') {
				unset($login);
			} 
	}
	if (isset ($_POST['password'])) { 
		$password = $_POST['password']; 
			if ($password == '') { 
			unset($password);
		} 
	}
	if (empty($login) or empty ($password)){
		exit ("You enter not full information turn back and fill all field!");
	}
	//если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали
    $login = stripslashes($login);
	$login = stripslashes($login);
    $login = htmlspecialchars($login);
	$password = stripslashes($password);
    $password = htmlspecialchars($password);
	//удаляем лишние пробелы
	$login = trim($login);
    $password = trim($password);
	// подключаемся к базе
	$link = mysqli_connect("localhost", "root", "", "mysql");
		if ( ! $link ) {
 			echo "Error: Unable to connect to MySQL. </b>";
 			echo "Error code errno: ".mysqli_connect_errno( );
 			echo "Error text error: ".mysqli_connect_error( );
 		}
 		else {
 			echo "Successfully connecting to MySQL. </b>";
 		}
	include ("bd.php");
	// проверка на существование пользователя с таким же логином
	$result = mysqli_query($db,"SELECT id FROM users WHERE login='$login'");
    $myrow = mysqli_fetch_array($result);
    if (!empty($myrow['id'])) {
    exit ("Sorry your login is already create");
    }
	// если такого нет, то сохраняем данные
	$result2 = mysqli_query ($db,"INSERT INTO users (login,password) VALUES('$login','$password')");
	// Проверяем, есть ли ошибки
    if ($result2=='TRUE') {
    	echo "You successfully registration <a href='index.php'>Main page</a>";
    }
 	else {
    	echo "ERROR! You are dont registration";
    }
?>