<?php
$title="Login Page";
require_once 'template/header.php';
require_once 'config/database.php';
require_once 'classes/User.php';
?>

<?php
$user = new User;

if ($user->isLoggedIn()) {
  header("location: index.php");
  die();
}


$errors=[];
$email ='';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  #Filter the input from sql
  $email = mysqli_real_escape_string($mysqli,$_POST['email']);
  $password = mysqli_real_escape_string($mysqli,$_POST['password']);

  if(empty($email))array_push($errors,"Email is required");
  if(empty($password))array_push($errors,"Password is required");

  if ( !count($errors) ) {

    $userExist = $mysqli -> query("select id,name,email,password,role from users where email='$email' limit 1");

    if (! $userExist -> num_rows){
      array_push($errors,"Email isn't registered");
    }else{
        $foundUser = $userExist->fetch_assoc();

        if (password_verify($password,$foundUser['password'])) {
          $_SESSION['logged_in']=true;
          $_SESSION['user_id']= $foundUser["id"];
          $_SESSION['user_name'] = $foundUser["name"];
          $_SESSION['user_role'] = $foundUser["role"];

          // Log file
          $log = "User_ID:" .$_SESSION['user_id']. ", email :".$email." has successfuly logged in.";
          logger($log);


          if($_SESSION['user_role'] == 'admin'){
            header('location: admin');
            die();
          }else {
            $_SESSION['success_message'] = "Welcome Back ".$foundUser["name"];
            header('location: index.php');
            die();
          }


        }else {
          array_push($errors,"Wrong Credintals");
        }

    }

  }


}
 ?>


<div class="login">

  <h4>Welcome Back!</h4>
  <h3 class="text-info">Please fill in the form below to login</h3>
  <hr>

  <?php  include 'template/errors.php'; ?>
  <form action="" method="post">

    <div class="form-group">
      <label for="email">Email:</label>
      <input class="form-control" type="email" name="email" placeholder="Your Email" value="<?php echo $email; ?>" id="email">
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input class="form-control" type="password" name="password" placeholder="Your password" id="password">
    </div>

    <div class="form-group">
      <button class="btn btn-success">Login</button>
      <a href="reset_password.php">Forgot your password?</a>
    </div>

  </form>

</div>





<?php
require_once 'template/footer.php';
 ?>
