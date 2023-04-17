<?php
$title = "Subjects";
$icon = "nc-layers-3";
include __DIR__.'/../template/header.php';

$subjects = $mysqli -> query("select * from subjects order by id")-> fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $st = $mysqli->prepare("delete from subjects where id = ?");
  $st -> bind_param("i", $IdToDelete);
  $IdToDelete = $_POST['subject_id'];
  $st -> execute();

  if($st->error) echo $st->error; else echo "<script>location.href='index.php'</script>";

}

?>

<div class="card">
  <div class="card-body">
    <div class="content">

      <a href="create.php" class="btn btn-success">Create a new subject</a>
      <p>Subjects: <?php echo count($subjects); ?></p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($subjects as $subject): ?>
              <tr>
                  <td><?php echo $subject["subject_name"]; ?></td>
                  <td>
                    <a href="edit.php?id=<?php echo $subject["id"]; ?>" class="btn btn-warning">Edit</a>
                    <form action="" method="post" style="display: inline">
                      <input type="hidden" name="subject_id" value="<?php echo $subject["id"]; ?>">
                      <button onclick="confirm('Are you sure?')" class="btn btn-danger" type="submit" name="button">Delete</button>
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
