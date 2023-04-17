<?php
$title = "Edit books";
$icon = "nc-layers-3";
include __DIR__.'/../template/header.php';
include_once __DIR__.'/../../classes/Upload.php';


if(!isset($_GET["id"]) || !$_GET["id"]){
  die("Missing parameter");
}



$errors=[];

$book_id = $_GET["id"];
$st = $mysqli -> prepare("select * from books where id = ?");
$st -> bind_param("i", $book_id);
$st->execute();

$book = $st->get_result()->fetch_assoc();

$name= $book['name'];
$author = $book['author'];
$copies = $book['copies'];
$image = $book['image'];
$link = $book['link'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

  if(empty($name))array_push($errors,"Name is required");
  if(empty($_POST['copies']))$copies=0;

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

      $upload = new Upload('uploads/books');
      $upload->file = $_FILES['image'];
      $errors = $upload->upload();

      if (!count($errors)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "/OnlineLibrary/".$image) ;
        $image = $upload -> filePath;
      }

    }




  if ( !count($errors) ) {

    $st = $mysqli->prepare("update books set name = ?, author =?, copies=?, subject_id = ? ,image = ?, link = ? where id=$book_id ");
    $st -> bind_param("ssddss", $dbname, $dbauthor, $dbcopies, $subject_id, $dbimage, $dblink);

    $dbname = $_POST['name'];
    $_POST['author'] ? $dbauthor = $_POST['author'] : $dbauthor = NULL;
    $dbimage = $image;
    $dbcopies = $_POST['copies'];
    $dblink = $_POST['link'];
    $subject_id = $_POST['subject_id'];
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
        <img src="<?php echo $config["app_url"]."/".$image ?>" alt="" width="150">
        <input class="form-control" type="file" name="image">
      </div>

      <div class="form-group">
        <label for="link">Link:</label>
        <input class="form-control" type="text" name="link" placeholder="Link" value="<?php echo $link; ?>" id="link">
      </div>

      <div class="form-groupd">
        <button class="btn btn-success">Edit</button>
      </div>

    </form>

  </div>
</div>


<?php
include  __DIR__.'/../template/footer.php'; ?>
