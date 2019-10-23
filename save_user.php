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
	if  (isset($_POST['email'])) { 
		$email = $_POST['email']; 
			if ($email == '') {    
				unset($email);
		} 
	}
	if  (empty($login) or empty($password) or empty($email)){ //or empty($code)
		exit ("You enter not full information turn back and fill all field!");
			if    (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)){
				//проверка е-mail адреса регулярными выражениями на корректность
			exit    ("Invalid e-mail!");
        	}
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
	$result2 = mysqli_query ($db,"INSERT INTO users (login,password,email,date) VALUES('$login','$password','$email',NOW())");
	// Проверяем, есть ли ошибки
    if ($result2=='TRUE') {
        $result3 = mysqli_query ($db,"SELECT id FROM users WHERE login='$login'");
        //извлекаем    идентификатор пользователя. Благодаря ему у нас и будет уникальный код    активации, ведь двух одинаковых идентификаторов быть не может.
        $myrow3 = mysqli_fetch_array($result3);
        $activation  = md5($myrow3['id']).md5($login);//код активации аккаунта. Зашифруем    через функцию md5 идентификатор и логин. Такое сочетание пользователь вряд ли    сможет подобрать вручную через адресную строку.
 		$subject = "Confirmation of registration";//тема сообщения
        $message = "Hello! Thank you for registering at citename.ru\nYour username:	".$login."\n Follow the link to activate your account:\n http://localhost/LinguaRegistration/activation.php?login=".$login."&code=".$activation."\nRespectfully,\n
            Administration citename.ru";//содержание сообщение
            mail($email,    $subject, $message, "Content-type:text/plane;    Charset=windows-1251\r\n");//отправляем сообщение
                     
            echo "An e-mail has been sent to you with a link to confirm registration. Attention! Link valid for 1 hour <a href='index.phtml'>Главная    страница</a>"; //говорим о    отправленном письме пользователю
            }
 	else {
    	echo "ERROR! You are dont registration";
    }
?>