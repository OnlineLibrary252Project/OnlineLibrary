<?php
$title = "Requests";
$icon = "nc-layers-3";
include __DIR__.'/template/header.php';
$timeNow = date("Y-m-d  H:i:s");

$expires_at = date("Y-m-d  H:i:s" , strtotime("+1 day"));
?>

<div class="card">
  <div class="card-body">
    <div class="content">

      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Request ID:</th>
              <th>Book Name:</th>
              <th>Book ID:</th>
              <th>Request Date:</th>
              <th>Response Date:</th>
              <th>Return Date:</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            <?php
              $user_id=$_SESSION['user_id'];
              $requests = $mysqli->query("select * from requests where user_id = $user_id ");
              foreach ($requests as $request):
                $book_id = $request["book_id"];
                $books = $mysqli -> query("select name from books where id = $book_id")-> fetch_assoc(); ?>
              <tr>
                  <td><?php echo $request["request_id"]; ?></td>
                  <td><?php echo $books["name"]; ?></td>
                  <td><?php echo $request["book_id"]; ?></td>
                  <td><?php echo $request["requestDate"]; ?></td>
                  <td><?php echo $request["responseDate"]; ?></td>
                  <td><?php echo $request["returnDate"]; ?></td>
                  <td><?php echo $request["status"]; ?></td>
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
