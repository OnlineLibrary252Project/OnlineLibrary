<?php
$title = 'Contact Page';
require_once 'template/header.php';
require_once 'config/database.php';


if (isset($_SESSION["contact_form"])) {
    $_SESSION['success_message'] = "Your message has been sent";
}


setcookie('isAdmin',"0",time()+30*24*60*60);


setcookie("TestForExpiry","",time()+30*24*60*60);

?>

<h1>Contact us</h1>

<!-- <a href="<?php echo $uploadDir."/163839963629264.png"; ?>">Download</a> -->


<form action=<?php echo $_SERVER["PHP_SELF"] ; ?> method="post" enctype="multipart/form-data">

<div class="form-group">
  <label for="name">Your name</label>
  <input type="text" value="<?php if(isset($_SESSION["contact_form"]["name"])) echo $_SESSION["contact_form"]["name"]; ?>" name="name" class="form-control" placeholder="Your name">
  <span class="text-danger"><?php echo $nameError; ?></span>
</div>

<div class="form-group">
  <label for="email">Your email</label>
  <input type="email" value="<?php if(isset($_SESSION["contact_form"]["email"])) echo $_SESSION["contact_form"]["email"]; ?>" name="email" class="form-control" placeholder="Your email">
  <span class="text-danger"><?php echo $emailError; ?></span>
</div>

<div class="form-group">
  <label for="document">Your documnet</label>
  <input type="file" name="document" class="form-control" placeholder="Your file">
  <span class="text-danger"><?php echo $documentError; ?></span>
</div>



<div class="form-group">
  <label for="message">Message</label>
  <textarea name="message" class="form-control" rows="8" cols="80" ><?php if(isset($_SESSION["contact_form"]["message"])) echo $_SESSION["contact_form"]["message"]; ?></textarea>
  <span class="text-danger"><?php echo $messageError; ?></span>
</div>

<button class="btn btn-primary">Send</button>
</form>

<?php require_once 'template/footer.php';

//$input = "<script>$('body').html('<h1>Bye</h1>')</script>";
//echo $input;
 ?>
