<?php
session_start();
?>
	<html>
	<head>
	<title>Main Page</title>
	</head>
	<body>
	<h2>Main Page</h2>
	<form action = "testreg.php" method="post">
<p>
	<label>Your login:<br></label>
	<input name = "login" type = "text" size = "15" maxlength="15">
    </p>
<p>

    <label>Your password:<br></label>
    <input name="password" type="password" size="15" maxlength="15">
    </p>
<p>
    <input type="submit" name="submit" value="Enter">
<br>
<a href="reg.php">Registration</a> 
    </p>
	</form>
    <br>
	<?php
    // Проверяем, пусты ли переменные логина и id пользователя
    if (empty($_SESSION['login']) or empty($_SESSION['id']))
    {
    // Если пусты, то мы не выводим ссылку
    echo "You enter like guest<br><a href='#'>This link for registration users</a>";
    }
    else
    {

    // Если не пусты, то мы выводим ссылку
    echo "You enter on site like ".$_SESSION['login']."<br><a  href='https://www.anekdot.ru/'>This link for registration users</a>";
    }
    ?>
    </body>
    </html>