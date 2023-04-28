<?php
$title="Change Password";
require_once 'template/header.php';
require_once 'config/database.php';
?>

<?php
if (isset($_SESSION['logged_in'])) {
  header("location:". $config['app_url']);
  die();
}

if ( !isset($_GET['token']) || !$_GET['token']) {
  die("Token parameter is missing");
}

$timeNow = date("Y-m-d  H:i:s");

$token = $_GET['token'];

$stmt = $mysqli -> prepare("SELECT * FROM reset_password WHERE token = ? AND expires_at > '$timeNow' ");
$stmt -> bind_param('s', $token);
$stmt -> execute();

$tokenValidation = $stmt->get_result();

// If number of rows was 0 then the ! symbol will make it 1 and page will die
if ( ! $tokenValidation-> num_rows ) {
  die("Token is invalid");
}

$errors=[];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $password = mysqli_real_escape_string($mysqli,$_POST['password']);
  $password_confirmation = mysqli_real_escape_string($mysqli,$_POST['password_confirmation']);

  if(empty($password))array_push($errors,"Password is required");
  if(empty($password_confirmation))array_push($errors,"Password Confirmation is required");
  if(!count($errors) && $password != $password_confirmation)array_push($errors,"Password don't match");

  if ( !count($errors) ) {

    //$userExist = $mysqli -> query("select user_id,token from reset_password where token=' " . $_GET['token'] . " ' limit 1");
    $userId = $tokenValidation->fetch_assoc()["user_id"];

    $password = password_hash($password,PASSWORD_DEFAULT);

    $mysqli -> query("UPDATE users SET password = '$password' WHERE id = '$userId' ");

    // Log file
    $log = "User_ID: ".$userId." has updated his password.";
    logger($log);

    //Delete previous tokens
    $mysqli -> query("DELETE FROM reset_password WHERE user_id = '$userId' ");

    header("location: login.php");
    $_SESSION['success_message'] = "your password has been changed successfuly";
    die();


  }



}

 ?>


<div class="password_reset">

  <h3 class="text-info">Create new Password</h3>
  <hr>

  <?php  include 'template/errors.php'; ?>
  <form action="" method="post">

    <div class="form-group">
      <label for="password">Password:</label>
      <input class="form-control" type="password" name="password" placeholder="Your password" id="password">
    </div>

    <div class="form-group">
      <label for="password_confirmation">Password Confirmation:</label>
      <input class="form-control" type="password" name="password_confirmation" placeholder="Password Confirmation" id="password">
    </div>

    <div class="form-groupd">
      <button class="btn btn-primary">Change Password</button>
    </div>

  </form>

</div>





<?php
require_once 'template/footer.php';
 ?>
