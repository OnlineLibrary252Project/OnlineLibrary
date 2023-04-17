<?php
$title = "Add subjects";
$icon = "nc-layers-3";
include __DIR__.'/../template/header.php';

$errors=[];
$subject_name ='';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $subject_name = mysqli_real_escape_string($mysqli,$_POST['subject_name']);

  if(empty($_POST['subject_name']))array_push($errors,"Name is required");

  // Not needed since we used $mysqli->error
  if ( !count($errors) ) {

    $subjectExist = $mysqli -> query("select subject_name from subjects where subject_name='$subject_name' limit 1");

    if ($subjectExist -> num_rows) array_push($errors,"This subject is already registered") ;

  }

  if ( !count($errors) ) {

    $st = $mysqli->prepare("insert into subjects (subject_name) values(?) ");
    $st -> bind_param("s", $dbname);

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
        <input class="form-control" type="text" name="subject_name" placeholder="Subject Name" value="<?php echo $subject_name; ?>" id="subject_name">
      </div>

      <div class="form-groupd">
        <button class="btn btn-success">Create</button>
      </div>

    </form>

  </div>
</div>


<?php
include  __DIR__.'/../template/footer.php'; ?>
