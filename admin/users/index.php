<?php
$title = "Users";
$icon = "nc-badge";
include __DIR__.'/../template/header.php';

$users = $mysqli -> query("select * from users order by id")-> fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $st = $mysqli->prepare("delete from users where id = ?");
  $st -> bind_param("i", $IdToDelete);
  $IdToDelete = $_POST['user_id'];
  $st -> execute();

  if($st->error) echo $st->error; else echo "<script>location.href='index.php'</script>";

}

?>

<div class="card">
  <div class="card-body">
    <div class="content">

      <a href="create.php" class="btn btn-success">Create a new User</a>
      <p>Users: <?php echo count($users); ?></p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th width="0">#</th>
              <th>Email</th>
              <th>Name</th>
              <th width="300">Role</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                  <td><?php echo $user["id"]; ?></td>
                  <td><?php echo $user["email"]; ?></td>
                  <td><?php echo $user["name"]; ?></td>
                  <td><?php echo $user["role"]; ?></td>
                  <td>
                    <a href="edit.php?id=<?php echo $user["id"]; ?>" class="btn btn-warning">Edit</a>
                    <form action="" method="post" style="display: inline">
                      <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
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
