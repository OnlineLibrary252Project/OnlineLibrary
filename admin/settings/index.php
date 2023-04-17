<?php
$title = "Settings";
$icon = "nc-settings-gear-64";
include_once __DIR__.'/../template/header.php';

$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

  if(empty($_POST['app_name']))array_push($errors,"App Name is required");
  if(empty($_POST['admin_email']))array_push($errors,"Admin Email is required");

  // Not needed since we used $mysqli->error

  if ( !count($errors) ) {

    $st = $mysqli->prepare("update settings set app_name = ?, admin_email = ? where id = 1");
    $st->bind_param("ss", $dbapp_name , $dbadmin_email);

    $dbapp_name =mysqli_real_escape_string($mysqli,$_POST['app_name']);
    $dbadmin_email = mysqli_real_escape_string($mysqli,$_POST['admin_email']);

    $st->execute();
  }

  if ( !count($errors) ) {
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

    <h3>Update settings</h3>

    <?php include_once __DIR__ . "/../template/errors.php"; ?>

    <form  action="" method="post">

      <div class="form-group">
        <label for="app_name">App name:</label>
        <input type="text" name="app_name" value="<?php echo $config['app_name']; ?>" id="app_name" class="form-control">
      </div>

      <div class="form-group">
        <label for="admin_email">Admin email:</label>
        <input type="email" name="admin_email" value="<?php echo $config['admin_email']; ?>" id="admin_email" class="form-control">
      </div>

      <div class="form-group">
        <button class="btn btn-success" type="submit" name="button">Update !</button>
      </div>

    </form>

  </div>
</div>


<?php
include_once  __DIR__.'/../template/footer.php';
