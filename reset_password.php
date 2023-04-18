<?php
$title="Reset Password";
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $email = mysqli_real_escape_string($mysqli,$_POST['email']);

  if(empty($email))array_push($errors,"Email is required");

  if ( !count($errors) ) {

    $userExist = $mysqli -> query("select id,name,email,password from users where email='$email' limit 1");

    if ($userExist -> num_rows){

      $userId = $userExist->fetch_assoc()["id"];

      //Delete previous tokens
      $mysqli -> query("DELETE FROM reset_password WHERE user_id = '$userId' ");

      #Generate special tokens
      $token = bin2hex(random_bytes(16));

      $expires_at = date("Y-m-d  H:i:s" , strtotime("+1 day"));

      $mysqli -> query("insert into reset_password (user_id, token, expires_at)
                        values ('$userId' , '$token' , '$expires_at')
      ");

      //Messages are not working :(
      $changePasswordUrl = $config['app_url'] . 'change_password.php?token='.$token;
      $headers = 'MIME-Version: 1.0'. "\r\n";
      $headers .= 'Content-type: text/html; charset=UFT-8' . "\r\n";

      $headers .= 'From: '.$config['admin_email']."\r\n".
          'Reply-To: '.$config['admin_email']."\r\n".
          'X-Mailer: PHP/' . phpversion();

      $htmlMessage = '<html><body>';
      $htmlMessage .= '<p style="color:#ff0000;"> '.$message .'</p>';
      $htmlMessage .= '</body></html>';

      // mail($email, 'Password reset link', $changePasswordUrl , $headers); # Not Working

      //mail($email, 'Password reset link', $changePasswordUrl , $config['admin_email']);

    }

    $_SESSION['success_message'] = "Please check your Email";
    header('location: reset_password.php');
    die();

  }


}
 ?>


<div class="password_reset">

  <h3 class="text-info">Please fill your Email</h3>
  <hr>

  <?php  include 'template/errors.php'; ?>
  <form action="" method="post">

    <div class="form-group">
      <label for="email">Email:</label>
      <input class="form-control" type="email" name="email" placeholder="Your Email" value="<?php echo $email; ?>" id="email">
    </div>

    <div class="form-groupd">
      <button class="btn btn-primary">Request Reset Password Link</button>
    </div>

  </form>

</div>





<?php
require_once 'template/footer.php';
 ?>
