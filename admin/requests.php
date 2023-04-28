<?php
$title = "requests";
$icon = "nc-layers-3";
include __DIR__.'/template/header.php';

$errors=[] ;

$requests = $mysqli -> query("select * from requests order by request_id")-> fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $requestId = $_POST["request_id"];
  $timenow  = date("Y-m-d  H:i:s", strtotime("+1 hours") );

  if( isset($_POST["approve"]) || isset($_POST["decline"]) ){

    isset($_POST["approve"])? $status="approved" : $status = "declined";

    $st = $mysqli->prepare("update requests set status = ?, responseDate= ? where request_id = ?; ");
    $st -> bind_param("ssd" ,$status, $timenow, $requestId);
    $st -> execute();


  }elseif ( isset($_POST['return']) ) {

    $currentStatus = $mysqli -> query("select status from requests where request_id = $requestId")-> fetch_assoc();

    if($currentStatus['status'] == "approved" ){
      $status = "returned";

      $st = $mysqli->prepare("update requests set status = ?, returnDate= ? where request_id = ?; ");
      $st -> bind_param("ssd" ,$status, $timenow, $requestId);
      $st -> execute();
    }else {
      array_push($errors,"Can't choose return if the current state is not approved");
      include "../template/errors.php";
      die();
    }

  }

  #Change Number Of copies( 1hr in Gathering :) )
  if( isset($_POST["approve"]) || isset($_POST["return"]) ){
    $bookID = $_POST['book_id'];
    $bookCopies = $mysqli -> query("select copies from books where id= $bookID")-> fetch_assoc();

    if(isset($_POST["approve"]))
      $bookCopies = ( (int) $bookCopies['copies'] ) - 1;
    elseif (isset($_POST["return"]))
      $bookCopies = ( (int) $bookCopies['copies'] ) + 1;

    $st2 = $mysqli->prepare(" update books set copies = ? where id = ? ; ");
    $st2 -> bind_param("ii" ,$bookCopies, $bookID);
    $st2 -> execute();
  }

  $requestId = $_POST["request_id"];



  if($st->error) echo $st->error; else echo "<script>location.href='requests.php'</script>";

}

?>

<div class="card">
  <div class="card-body">
    <div class="content">
      <p>Requests: <?php echo count($requests); ?></p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Request ID:</th>
              <th>User Name:</th>
              <th>User ID:</th>
              <th>Book Name:</th>
              <th>Book ID:</th>
              <th>Request Date:</th>
              <th>Response Date:</th>
              <th>Return Date:</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($requests as $request):
              $user_id = $request["user_id"];
              $user_name = $mysqli -> query("select name from users where id = $user_id")-> fetch_assoc();

              $book_id = $request["book_id"];
              $books = $mysqli -> query("select name from books where id = $book_id")-> fetch_assoc(); ?>

              <tr>
                  <td><?php echo $request["request_id"]; ?></td>

                  <td><?php echo $user_name["name"]; ?></td>
                  <td><?php echo $request["user_id"]; ?></td>

                  <td><?php echo $books["name"]; ?></td>
                  <td><?php echo $request["book_id"]; ?></td>

                  <td><?php echo $request["requestDate"]; ?></td>
                  <td><?php echo $request["responseDate"]; ?></td>
                  <td><?php echo $request["returnDate"]; ?></td>

                  <td><?php echo $request["status"]; ?></td>
                  <td>
                    <form action="" method="post" style="display: inline" >
                      <input type="hidden" name="approve" >
                      <input type="hidden" name="request_id" value="<?php echo $request["request_id"]; ?>">
                      <input type="hidden" name="book_id" value="<?php echo $request["book_id"]; ?>">
                      <button onclick='return confirm("Are you sure? Number of copies will decrease by 1")' class="btn btn-success" type="submit">Aprrove</button>
                    </form>
                  </td>
                  <td>
                    <form action="" method="post" style="display: inline" >
                      <input type="hidden" name="decline" >
                      <input type="hidden" name="request_id" value="<?php echo $request["request_id"]; ?>">
                      <input type="hidden" name="book_id" value="<?php echo $request["book_id"]; ?>">
                      <button onclick='return confirm("Are you sure?")' class="btn btn-danger" type="submit">Decline</button>
                    </form>
                  </td>
                  <td>
                    <form action="" method="post" style="display: inline" >
                      <input type="hidden" name="return" >
                      <input type="hidden" name="request_id" value="<?php echo $request["request_id"]; ?>">
                      <input type="hidden" name="book_id" value="<?php echo $request["book_id"]; ?>">
                      <button onclick='return confirm("Are you sure? Number of copies will increase by 1")' class="btn btn-secondary" type="submit">Return</button>
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
