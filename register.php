<?php
$title="Register Page";
require_once 'template/header.php';
require_once 'config/database.php';

?>

<?php
if (isset($_SESSION['logged_in'])) {
  header("location:". $config['app_url']);
  die();
}


$errors=[];
$email ='';
$name ='';
$password='';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $email = mysqli_real_escape_string($mysqli,$_POST['email']);
  $name = mysqli_real_escape_string($mysqli,$_POST['name']);
  $password = mysqli_real_escape_string($mysqli,$_POST['password']);
  $password_confirmation = mysqli_real_escape_string($mysqli,$_POST['password_confirmation']);

  if(empty($email))array_push($errors,"Email is required");
  if(empty($name))array_push($errors,"Name is required");
  if(empty($password))array_push($errors,"Password is required");
  if(empty($password_confirmation))array_push($errors,"Password Confirmation is required");
  if($password != $password_confirmation)array_push($errors,"Password don't match");

  if ( !count($errors) ) {

    $userExist = $mysqli -> query("select id,email from users where email='$email' limit 1");

    if ($userExist -> num_rows) array_push($errors,"Email already registered") ;

  }

  if ( !count($errors) ) {

    $password=password_hash($password,PASSWORD_DEFAULT);

    $InsertQuery = "insert into users(email,name,password) values ('$email','$name','$password')";
    $mysqli ->query($InsertQuery);

    // Log file
    $log = "New User has been created using this email :".$email;
    logger($log);


    $_SESSION['success_message'] = "Registered successed";
    header('location: login.php');
    die();
  }


}
 ?>


<div class="register">

  <h4>Welcome to our website</h4>
  <h3 class="text-info">Please fill in the form below to register new account</h3>
  <hr>

  <?php  include 'template/errors.php'; ?>
  <form action="" method="post">

    <div class="form-group">
      <label for="email">Email:</label>
      <input class="form-control" type="email" name="email" placeholder="Your Email" value="<?php echo $email; ?>" id="email">
    </div>

    <div class="form-group">
      <label for="name">Name:</label>
      <input class="form-control" type="text" name="name" placeholder="Your name" value="<?php echo $name; ?>" id="name">
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input class="form-control" type="password" name="password" placeholder="Your password" id="password">
    </div>

    <div class="form-group">
      <label for="confirm_password">Confirm Password:</label>
      <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm your password" id="password_confirmation">
    </div>

    <div class="form-groupd">
      <button class="btn btn-success">Register</button>
      <a href="login.php">already have an account</a>
    </div>

  </form>

</div>


<?php
require_once 'template/footer.php';
 ?>
