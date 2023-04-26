<?php
$title = 'Contact Page';
require_once 'template/header.php';
require_once 'config/database.php';



$errors=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed_file_types = array('image/jpeg', 'image/png', 'image/gif');
    $max_file_size = 8 * 1024 *1024; //8 MB

    if (!isset($_FILES['document']))array_push($errors,'No file uploaded.');

    $file = $_FILES['document'];

    if (!in_array($file['type'], $allowed_file_types))array_push($errors,'File type not allowed.');

    if ($file['size'] > $max_file_size)array_push($errors,'File size exceeds maximum allowed.');


    $upload_dir = 'uploads/';
    $toUpload_file = $upload_dir . basename($file['name']);
    $file_type = strtolower(pathinfo($toUpload_file, PATHINFO_EXTENSION));

    // Generate a unique filename to avoid conflicts
    $unique_filename = uniqid() . '.' . $file_type;
    $toUpload_file = $upload_dir . $unique_filename;

    if (!$errors && move_uploaded_file($file['tmp_name'], $toUpload_file)) {
        $_SESSION['success_message'] = 'File uploaded successfully.';

        $InsertQuery= $mysqli->prepare("insert into messages(name,email,document,message)
                                      values (?, ?, ?, ?)");
        $InsertQuery->bind_param("ssss",$dbContacterName,$dbEmail,$dbDocument,$dbMessage);

        $dbContacterName= mysqli_real_escape_string($mysqli,$_POST['name']);
        $dbEmail= mysqli_real_escape_string($mysqli,$_POST['email']);
        $dbDocument= $toUpload_file;
        $dbMessage= mysqli_real_escape_string($mysqli,$_POST['message']);
        $InsertQuery->execute();

        header('Location: contact.php');
        die();
    }
}



?>

<h1>Contact us</h1>
<!-- <a href="<?php echo $uploadDir."/163839963629264.png"; ?>">Download</a> -->

<?php  include 'template/errors.php'; ?>

<form action=<?php echo $_SERVER["PHP_SELF"] ; ?> method="post" enctype="multipart/form-data">

<div class="form-group">
  <label for="name">Your name</label>
  <input type="text" value="<?php if(isset($_SESSION["contact_form"]["name"])) echo $_SESSION["contact_form"]["name"]; ?>" name="name" class="form-control" placeholder="Your name">
</div>

<div class="form-group">
  <label for="email">Your email</label>
  <input type="email" value="<?php if(isset($_SESSION["contact_form"]["email"])) echo $_SESSION["contact_form"]["email"]; ?>" name="email" class="form-control" placeholder="Your email">
</div>

<div class="form-group">
  <label for="document">Your documnet</label>
  <input type="file" name="document" class="form-control" placeholder="Your file">
</div>



<div class="form-group">
  <label for="message">Message</label>
  <textarea name="message" class="form-control" rows="8" cols="80" ><?php if(isset($_SESSION["contact_form"]["message"])) echo $_SESSION["contact_form"]["message"]; ?></textarea>
</div>

<button class="btn btn-primary">Send</button>
</form>

<?php require_once 'template/footer.php';


 ?>
