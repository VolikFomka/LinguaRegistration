<?php
        //заносим    введенный пользователем логин в переменную $login, если он пустой, то    уничтожаем переменную
          if (isset($_POST['login'])) { $login = $_POST['login'];    if ($login == '') { unset($login);} }


        //заносим введенный    пользователем пароль в переменную $password, если он пустой, то уничтожаем    переменную
          if    (isset($_POST['password'])) { $password=$_POST['password']; if ($password    =='') { unset($password);} }


        //заносим    введенный пользователем защитный код в переменную $code, если он пустой, то    уничтожаем переменную
          if (isset($_POST['code'])) { $code = $_POST['code']; if    ($code == '') { unset($code);} }


        //если пользователь не ввел логин или пароль, то выдаем    ошибку и останавливаем скрипт
          if (empty($login) or empty($password)or empty($code))

              
          {
          exit ("Вы ввели не всю информацию, вернитесь назад и    заполните все поля!"); //останавливаем    выполнение сценариев
          }

        //запускаем    функцию, генерирующую код. Можно даже вывести ее в отдельный файл.
          function generate_code()
          {
              $hours = date("H"); // час       
              $minuts = substr(date("H"), 0 ,    1);// минута 
              $mouns = date("m");    // месяц             
              $year_day = date("z"); // день в году 
              $str = $hours . $minuts . $mouns .    $year_day; //создаем строку
              $str =    md5(md5($str)); //дважды шифруем в md5
                  $str =    strrev($str);// реверс строки
                  $str =    substr($str, 3, 6); // извлекаем 6 символов,    начиная с 3
                  // Вам конечно же можно постваить другие значения, так    как, если взломщики узнают, каким именно способом это все генерируется, то в    защите не будет смысла.
              $array_mix = preg_split('//',    $str, -1, PREG_SPLIT_NO_EMPTY);
              srand ((float)microtime()*1000000);
              shuffle ($array_mix);
              return implode("",    $array_mix);
          }
          function    chec_code($code) //проверяем код 
          {
              $code = trim($code);//удаляем пробелы 
              $array_mix = preg_split ('//',    generate_code(), -1, PREG_SPLIT_NO_EMPTY);
              $m_code = preg_split ('//', $code, -1,    PREG_SPLIT_NO_EMPTY);
              $result = array_intersect ($array_mix,    $m_code);
          if    (strlen(generate_code())!=strlen($code))
          { return    FALSE;}
          if    (sizeof($result) == sizeof($array_mix))
          {   return TRUE;}
          else
          {   return FALSE;}}
          // после сравнения проверяем,    пускать ли пользователя дальше или, он сделал ошибку, и остановить скрипт
          if    (!chec_code($_POST['code']))

          {
          exit ("Вы ввели неверно код с картинки"); //останавливаем выполнение сценариев
          }
          //если логин и пароль    введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что    люди могут ввести
          $login = stripslashes($login);






	// если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали

    $login = stripslashes($login);
	$login = stripslashes($login);
    $login = htmlspecialchars($login);
	$password = stripslashes($password);
    $password = htmlspecialchars($password);

	// удаляем лишние пробелы

	$login = trim($login);
    $password = trim($password);

	// добавляем проверку на длину логина и пароля

	if (strlen($login) < 3 or strlen($login) > 15) {
		exit ("The login must consist of at least 3 characters and no more than 15");
	}

	if (strlen($password) < 3 or strlen($password) > 15) {
		exit ("The password must consist of at least 3 characters and no more than 15");
	}

	// проверяем, отправил    ли пользователь изображение

	if (!empty($_POST['fupload'])) {	
		$fupload=$_POST['fupload']; 
		$fupload = trim($fupload);

  		if ($fupload =='' or empty($fupload)) { // если переменная $fupload пуста, то удаляем ее
        	unset($fupload);
		}
	}

	// если переменной не существует,то присваиваем ему заранее приготовленную картинку

	if (!isset($fupload) or empty($fupload) or $fupload == '') {
		$avatar = "avatars/standartava.jpg"; 
	}

	// иначе - загружаем изображение пользователя

	else {
		$path_to_90_directory = 'avatars/'; //папка, куда будет загружаться начальная картинка и ее сжатая копия 

		if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name'])) { // проверка формата исходного изображения
			$filename = $_FILES['fupload']['name'];
			$source = $_FILES['fupload']['tmp_name'];	
			$target = $path_to_90_directory . $filename;
			move_uploaded_file($source, $target); // загрузка оригинала в папку $path_to_90_directory 

			if(preg_match('/[.](GIF)|(gif)$/', $filename)) {
			$im = imagecreatefromgif($path_to_90_directory.$filename) ; //åñëè îðèãèíàë áûë â ôîðìàòå gif, òî ñîçäàåì èçîáðàæåíèå â ýòîì æå ôîðìàòå. Íåîáõîäèìî äëÿ ïîñëåäóþùåãî ñæàòèÿ
			}

			if(preg_match('/[.](PNG)|(png)$/', $filename)) {
			$im = imagecreatefrompng($path_to_90_directory.$filename) ; // если оригинал был в формате gif, то создаем изображение в этом же формате. Необходимо для последующего сжатия
			}
	
			if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
			$im = imagecreatefromjpeg($path_to_90_directory.$filename); // если оригинал был в формате png, то создаем изображение в этом же формате. Необходимо для последующего сжатия
                     }
		}
	
/*
СОЗДАНИЕ КВАДРАТНОГО ИЗОБРАЖЕНИЯ И ЕГО ПОСЛЕДУЮЩЕЕ СЖАТИЕ    ВЗЯТО С САЙТА www.codenet.ru           
Создание квадрата 90x90
dest - результирующее изображение 
w - ширина изображения 
ratio - коэффициент пропорциональности */  
		$w = 90;  // квадратная 90x90. Можно поставить и другой размер
		$w_src = imagesx($im); //  вычисляем ширину
		$h_src = imagesy($im); // вычисляем высоту изображения

        /* создаём    пустую квадратную картинку 
        важно именно    truecolor!, иначе будем иметь 8-битный результат */
    	$dest = imagecreatetruecolor($w,$w); 

        // вырезаем квадратную серединку по x, если фото горизонтальное 
    	if ($w_src>$h_src) 
    		imagecopyresampled($dest, $im, 0, 0, round((max($w_src,$h_src)-min($w_src,$h_src))/2), 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src)); 
		// вырезаем    квадратную верхушку по y, если фото    вертикальное (хотя можно тоже серединку) 
    	if ($w_src<$h_src) 
        	imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src)); 
        // квадратная картинка масштабируется без вырезок
   		if ($w_src==$h_src) 
        	imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src); 
		$date=time(); //вычисляем время в настоящий момент
			imagejpeg($dest, $path_to_90_directory.$date.".jpg"); 
			/*сохраняем    изображение формата jpg в нужную папку, именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых имен.          
			почему именно jpg? Он занимает очень мало места + уничтожается анимирование gif изображения, которое отвлекает пользователя. 
			Не очень приятно читать его комментарий, когда краем глаза замечаешь какое-то движение.*/

		$avatar = $path_to_90_directory.$date.".jpg";//çàíîñèì â ïåðåìåííóþ ïóòü äî àâàòàðà.

		$delfull = $path_to_90_directory.$filename; 
			unlink ($delfull); // удаляем оригинал загруженного изображения, он нам больше не нужен. Задачей было - получить миниатюру
		}
		else {
		 // в случае  несоответствия формата, выдаем соответствующее сообщение
        	exit ("Avatar must be in the format <strong>JPG,GIF or PNG</strong>");
	    }
// конец процесса загрузки и присвоения переменной $avatar адреса загруженной авы
	}

$password = md5($password); // шифруем пароль

$password = strrev($password); // для надежности добавим реверс

$password = $password."b3p6f";
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
	$result2 = mysqli_query ($db,"INSERT INTO users (login,password,avatar,email,date) VALUES('$login','$password','$email','$avatar',NOW())");
	// Проверяем, есть ли ошибки
    if ($result2=='TRUE') {
        $result3 = mysqli_query ($db,"SELECT id FROM users WHERE login='$login'");
        // Извлекаем идентификатор пользователя. Благодаря ему у нас и будет уникальный код активации, ведь двух одинаковых идентификаторов быть не может.
        $myrow3 = mysqli_fetch_array($result3);
        $activation  = md5($myrow3['id']).md5($login); // код активации аккаунта. Зашифруем через функцию md5 идентификатор и логин. Такое сочетание пользователь вряд ли сможет подобрать вручную через адресную строку.
 		$subject = "Confirmation of registration"; // тема сообщения
        $message = "Hello! Thank you for registering at citename.ru\nYour username:	".$login."\n Follow the link to activate your account:\n http://localhost/LinguaRegistration/activation.php?login=".$login."&code=".$activation."\nRespectfully,\n
            Administration citename.ru"; // содержание сообщение
            mail($email,    $subject, $message, "Content-type:text/plane;    Charset=windows-1251\r\n");//отправляем сообщение
                     
            echo "An e-mail has been sent to you with a link to confirm registration. Attention! Link valid for 1 hour <a href='index.phtml'>Главная    страница</a>"; // говорим о отправленном письме пользователю
            }
 	else {
    	echo "ERROR! You are dont registration";
    }
?>