<?php 
    session_start();//starts  new session
    $host = 'localhost';
    $user = 'root';
    $pass ='';
    $dbname = 'phplogin';

    $con = mysqli_connect($host, $user,$pass,$dbname);
    if(mysqli_connect_errno()){
        exit('Failed to connect to MySQL:'.mysql_connect_errno());
    }

    if (!isset($_POST['username'],$_POST['password'])) {
        exit('Please fill both username and password');
    }

    if ($stmt = $con->prepare('SELECT id,password FROM accounts WHERE username = ?')) {
        # code...
        $stmt->bind_param('s',$_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows >0) {
            $stmt->bind_result($id,$password);
            $stmt->fetch();
            if (password_verify($_POST['password'], $password)) {
                # code...
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['id'] = $id;
                header('Location:home.php');
            }else{
                echo "Incorrect username and/or password";
            }
        }else{
            echo "Incorrect username and/or password";
        }
        $stmt->close();
    }
 ?>
