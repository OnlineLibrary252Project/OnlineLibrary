<?php
$title = "Edit Subjects";
$icon = "nc-layers-3";
include __DIR__.'/../template/header.php';


if(!isset($_GET["id"]) || !$_GET["id"]){
  die("Missing parameter");
}



$errors=[];

$subject_id = $_GET["id"];
$st = $mysqli -> prepare("select * from subjects where id = ?");
$st -> bind_param("i", $subject_id);
$st->execute();

$subject = $st->get_result()->fetch_assoc();

$subject_name= $subject['subject_name'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

  if(empty($_POST['subject_name']))array_push($errors,"Name is required");

  // Not needed since we used $mysqli->error

  if ( !count($errors) ) {

    $st = $mysqli->prepare("update subjects set subject_name = ? where id=$subject_id ");
    $st -> bind_param("s", $dbname );

    $dbname = $_POST['subject_name'];

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
        <label for="name">Name:</label>
        <input class="form-control" type="text" name="subject_name" placeholder="Subject Name" value="<?php echo $subject_name; ?>" id="name">
      </div>

      <div class="form-groupd">
        <button class="btn btn-success">Update</button>
      </div>

    </form>

  </div>
</div>



<?php
include  __DIR__.'/../template/footer.php'; ?>
