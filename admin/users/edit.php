<?php
$title = "Edit User";
$icon = "nc-badge";
include __DIR__.'/../template/header.php';


if(!isset($_GET["id"]) || !$_GET["id"]){
  die("Missing parameter");
}



$errors=[];

$user_id = $_GET["id"];
$st = $mysqli -> prepare("select * from users where id = ?");
$st -> bind_param("i", $user_id);
$st->execute();

$user = $st->get_result()->fetch_assoc();

$email= $user['email'];
$name = $user['name'];
$role = $user['role'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

  if(empty($_POST['email']))array_push($errors,"Email is required");
  if(empty($_POST['name']))array_push($errors,"Name is required");
  if(empty($_POST['role']))array_push($errors,"Role is required");

  // Not needed since we used $mysqli->error

  if ( !count($errors) ) {

    $st = $mysqli->prepare("update users set name = ?, email =?, password = ?, role = ? where id=$user_id ");
    $st -> bind_param("ssss", $dbname, $dbemail, $dbpassword, $dbrole );

    $dbemail= $_POST['email'];
    $dbname = $_POST['name'];
    $_POST['password'] ? $dbpassword = password_hash($_POST['password'],PASSWORD_DEFAULT) : $dbpassword = $user['password'];
    $dbrole = $_POST['role'];

    $st->execute();

    if($st -> error){
      array_push($errors,$st -> error);
    }else {
      echo "<script>location.href='index.php'</script>";
    }

  }


}

?>


<div class="card">
  <div class="content">

    <?php  include __DIR__.'/../template/errors.php'; ?>
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
        <label for="role">Role:</label>
        <select name="role" class="form-control">
          <option value="user"
          <?php if($role == 'user') echo "selected"; ?>
          >User</option>
          <option value="admin"
          <?php if($role == 'admin') echo "selected"; ?>
          >Admin</option>

        </select>
      </div>

      <div class="form-groupd">
        <button class="btn btn-success">Update</button>
      </div>

    </form>

  </div>
</div>



<?php
include  __DIR__.'/../template/footer.php'; ?>
