<?php
$title = "Add books";
$icon = "nc-layers-3";
include_once __DIR__.'/../template/header.php';
include_once __DIR__.'/../../classes/Upload.php';

$errors=[];
$author ='';
$name ='';
$price = '';
$link='';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $name = mysqli_real_escape_string($mysqli,$_POST['name']);
  $author = mysqli_real_escape_string($mysqli,$_POST['author']);
  $link = mysqli_real_escape_string($mysqli,$_POST['link']);



  if(empty($_POST['name']))array_push($errors,"Name is required");
  if(empty($_FILES['image']['name']))array_push($errors,"Image is required");

  // Not needed since we used $mysqli->error
/*  if ( !count($errors) ) {

    $bookExist = $mysqli -> query("select id,name from books where name='$name' limit 1");

    if ($bookExist -> num_rows) array_push($errors,"This name is already registered") ;

  }
*/
if(!count($errors) &&  $_FILES['image']){
    $upload = new Upload('uploads/books');
    $upload->file = $_FILES['image'];
    $errors = $upload->upload();
}



  if ( !count($errors) ) {

    $st = $mysqli->prepare("insert into books (name, author, copies, subject_id, image,link) values(?, ?, ?, ?, ?, ?) ");
    $st -> bind_param("ssddss", $dbname, $dbauthor, $dbcopies, $dbsubject_id, $dbimage,$dblink);

    $dbname = $_POST['name'];
    $_POST['author'] ? $dbauthor = $_POST['author'] : $dbauthor = NULL;
    $dbimage = $upload->filePath;
    $dbcopies = $_POST['copies'];
    $dblink= $_POST['link'];
    $dbsubject_id= $_POST['subject_id'];

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
    <form action="" method="post" enctype="multipart/form-data">

      <div class="form-group">
        <label for="name">Name:</label>
        <input class="form-control" type="text" name="name" placeholder="Your name" value="<?php echo $name; ?>" id="name">
      </div>

      <div class="form-group">
        <label for="author">author:</label>
        <textarea class="form-control" name="author" rows="8" cols="80"><?php echo $author; ?></textarea>
      </div>

      <div class="form-group">
        <label for="name">Number Of Copies:</label>
        <input class="form-control" type="number" name="copies" min="0" placeholder="Copies" value="<?php echo $copies; ?>" id="copies">
      </div>

      <div class="form-group">
        <label for="subject">Subjects:</label>
        <select class="form-control" name="subject_id" id="subject">
          <?php
          $subjects = $mysqli->query("select * from subjects order by id");

           foreach ($subjects as $subject) { ?>
            <option value="<?php echo $subject['id']; ?>">
              <?php echo $subject['subject_name']  ?>
            </option>
          <?php } ?>
        </select>
      </div>


      <div class="form-group">
        <label for="image">Image:</label>
        <input class="form-control" type="file" name="image">
      </div>

      <div class="form-group">
        <label for="price">Link:</label>
        <input class="form-control" type="text" name="link" placeholder="Link" value="<?php echo $link; ?>" id="link">
      </div>

      <div class="form-groupd">
        <button class="btn btn-success">Create</button>
      </div>

    </form>

  </div>
</div>


<?php
include  __DIR__.'/../template/footer.php'; ?>
