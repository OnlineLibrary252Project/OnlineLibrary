<?php
$title = "Create User";
$icon = "nc-badge";
include __DIR__.'/../template/header.php';

$errors=[];
$email ='';
$name ='';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $email = mysqli_real_escape_string($mysqli,$_POST['email']);
  $name = mysqli_real_escape_string($mysqli,$_POST['name']);
  $password = mysqli_real_escape_string($mysqli,$_POST['password']);
  $role = mysqli_real_escape_string($mysqli,$_POST['role']);

  if(empty($email))array_push($errors,"Email is required");
  if(empty($name))array_push($errors,"Name is required");
  if(empty($password))array_push($errors,"Password is required");
  if(empty($role))array_push($errors,"Role is required");

  // Not needed since we used $mysqli->error
  if ( !count($errors) ) {

    $userExist = $mysqli -> query("select id,email from users where email='$email' limit 1");

    if ($userExist -> num_rows) array_push($errors,"Email already registered") ;

  }

  if ( !count($errors) ) {

    $password=password_hash($password,PASSWORD_DEFAULT);

    $InsertQuery = "insert into users(email,name,password ,role) values ('$email','$name','$password' , '$role')";
    $mysqli ->query($InsertQuery);

    if($mysqli->error){
      array_push($errors, $mysqli->error);
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
          <option value="user">User</option>
          <option value="admin">Admin</option>

        </select>
      </div>

      <div class="form-groupd">
        <button class="btn btn-success">Create Account!</button>
      </div>

    </form>

  </div>
</div>


<?php
include  __DIR__.'/../template/footer.php'; ?>
