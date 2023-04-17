<?php
$title = "books";
$icon = "nc-layers-3";
include __DIR__.'/../template/header.php';

$books = $mysqli -> query("select * from books order by id")-> fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $st = $mysqli->prepare("delete from books where id = ?");
  $st -> bind_param("i", $IdToDelete);
  $IdToDelete = $_POST['book_id'];
  $st -> execute();

  if ($_POST['image']) {
    unlink($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "/OnlineLibrary/".$_POST['image']) ;
  }

  if($st->error) echo $st->error; else echo "<script>location.href='index.php'</script>";

}

?>

<div class="card">
  <div class="card-body">
    <div class="content">

      <a href="create.php" class="btn btn-success">Add a new book</a>
      <p>books: <?php echo count($books); ?></p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Author</th>
              <th>Copies</th>
              <th>Subject</th>
              <th>Link</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php
              foreach ($books as $book):
              $subject_number = (int)$book['subject_id'];
              $subject_name = $mysqli->query("select subject_name from subjects where id = $subject_number ")->fetch_assoc(); ?>
              <tr>
                  <td><?php echo $book["name"]; ?></td>
                  <td><?php echo $book["author"]; ?></td>
                  <td><?php echo $book["copies"]; ?></td>
                  <td><?php echo $subject_name["subject_name"]; ?></td>
                  <td><?php if($book["link"]) echo $book["link"];else echo "Unavailable";  ; ?></td>
                  <td> <img width="50" src="<?php echo $config["app_url"] . "/" . $book['image']; ?>" alt=""> </td>
                  <td>
                    <a href="edit.php?id=<?php echo $book["id"]; ?>" class="btn btn-warning">Edit</a>
                    <form action="" method="post" style="display: inline">
                      <input type="hidden" name="book_id" value="<?php echo $book["id"]; ?>">
                      <input type="hidden" name="image" value="<?php echo $book["image"]; ?>">
                      <button onclick='return confirm("Are you sure?")' class="btn btn-danger" type="submit" name="button">Delete</button>
                    </form>
                  </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>

<?php
include  __DIR__.'/../template/footer.php'; ?>
