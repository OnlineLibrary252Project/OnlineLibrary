<?php
$title = "messages";
$icon = "nc-layers-3";
include __DIR__.'/template/header.php';

$messages = $mysqli -> query("select * from messages order by id")-> fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $st = $mysqli->prepare("delete from messages where id = ?");
  $st -> bind_param("i", $IdToDelete);
  $IdToDelete = $_POST['Message_id'];
  $st -> execute();

  if($st->error) echo $st->error; else echo "<script>location.href='messages.php'</script>";

}

?>

<div class="card">
  <div class="card-body">
    <div class="content">

      <p>messages: <?php echo count($messages); ?></p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>From:</th>
              <th>Email:</th>
              <th>Content</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($messages as $Message): ?>
              <tr>
                  <td><?php echo $Message["name"]; ?></td>
                  <td><?php echo $Message["email"]; ?></td>
                  <td><?php echo $Message["message"]; ?></td>
                  <td>
                    <form action="" method="post" style="display: inline">
                      <input type="hidden" name="Message_id" value="<?php echo $Message["id"]; ?>">
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
include  __DIR__.'/template/footer.php'; ?>
