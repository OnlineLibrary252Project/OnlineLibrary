<?php
$title="Main Page";
require_once 'template/header.php';
require_once 'classes/User.php';

//setcookie('username','Nawaf',time()+30*24*60*60);


$errors=[];

$user = new User();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && ( isset($_POST["Addbook_copies"]) || isset($_POST["Minusbook_copies"]) ) ){

  if(isset( $_POST["Addbook_copies"] )){
    $copies =  $_POST["Addbook_copies"] + 1;
  }
  elseif (isset( $_POST["Minusbook_copies"] )){
    if(empty( $_POST["Minusbook_copies"] ))array_push($errors,"There is no copies to reduce");
    $copies = $_POST["Minusbook_copies"] - 1;
  }

  if(!count($errors)){
  $st = $mysqli->prepare("update books set copies = ? where id = ?");
  $st -> bind_param("ii", $copies, $IdToDelete);
  $IdToDelete = $_POST['book_id'];
  $st -> execute();

  if($st->error) echo $st->error; else echo "<script>location.href='index.php'</script>";

  }


}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST["borrow"] ) ){

  $user_id= $_SESSION['user_id'];
  $book_id = $_POST['book_id'];
  $NumberOfTotalPending = $mysqli -> query("select status from requests where user_id=$user_id  and status='pending' ")->fetch_all(MYSQLI_ASSOC);
  $NumberOfTotalPending = count($NumberOfTotalPending);
  $HasPendingSameBook = $mysqli -> query("select * from requests where user_id=$user_id and book_id=$book_id and status='pending' ")->fetch_all(MYSQLI_ASSOC);
  $NumberOfSamePending = count($HasPendingSameBook);

  #Check if the user has 3 pending request ( for three diffrent books)
  if($NumberOfTotalPending>=3){
    array_push($errors,"Can't Send a new request while you have 3 pending requests");
  }else if($NumberOfSamePending>0){   #Check if the user has pending request ( for the same book)
    array_push($errors,"Can't Send a new request while you a pending one to the same book");
  }else {

    $timenow  = date("Y-m-d  H:i:s", strtotime("+2 hours") );
    $st = $mysqli->prepare("insert into requests (user_id, book_id, requestDate) values(?, ?, ?) ");
    $st -> bind_param("dds", $user_id, $book_id,$timenow);
    $st -> execute();

    if($st->error) echo $st->error;
    else{
        $_SESSION['success_message'] = "Request sent successfully";
        echo "<script>location.href='index.php'</script>";
    }
  }

}

if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['search']) ){ ?>


  <?php
    $NameToSearch = $_GET['search'];
     $books = $mysqli->query("select * from books where name like '%$NameToSearch%' ");
     ?>

    <div class="row">
        <?php foreach ($books as $book) {
          $subject_id = (int)$book["subject_id"];
          $subject_name =  $mysqli->query("select subject_name from subjects where id = $subject_id")->fetch_assoc();
          $subject_name = $subject_name['subject_name'];
        ?>
      <div class="col-md4">
        <div class="card mb-3">
          <div class="card-body">
            <div class="custom-card-image" style="background-image: url('<?php echo $config['app_url'].$book['image']; ?> ')"></div>
            <div class="">Name: <?php echo $book["name"]; ?></div>
            <div class="">Subject: <?php echo $subject_name; ?></div>
            <div class="">Author: <?php echo $book["author"]; ?></div>

            <div class="">Number Of Available Copies:

               <?php echo $book["copies"]; ?>


               <?php // Request Button appears to the user only (Request to borrow a book)
                if($user->isLoggedIn() && $_SESSION['user_role']=='user' &&  $book["copies"]>0){ ?>
                <div class="">

                 <form action="" method="post" style="display: inline">
                   <input type="hidden" name="book_id" value="<?php echo $book["id"]; ?>">
                   <button onclick="confirm('Are you sure?')" class="btn btn-primary" type="submit" name="borrow">Request to Borrow</button>
                 </form>

                </div>

              <?php } ?>


           </div>

            <?php
            if ($book["link"]) {
                  if ($user->isLoggedIn()) { ?>
                    <div class=""> <a href="<?php echo $book["link"]; ?>" target="_blank">Link</a> </div>
                <?php } else { ?>
                        <div class=""> Log in to see the link</div>
                <?php }
              }else { ?>
                        <div class=""> Sorry, This book isn't available online</div>

                <?php }  ?>


          </div>
        </div>
      </div>
        <?php } ?>
    </div>

  <?php
  die();
}

?>



  <h1>Welcome to our website</h1>
  <?php  include 'template/errors.php'; ?>

  <!-- Search Form -->
  <form action="" method="GET">
    <input id="search" type="text" name="search" placeholder="Book Name">
    <input id="submit" type="submit" value="Search">
  </form>


  <?php $books = $mysqli->query("select * from books"); ?>

  <div class="row">
      <?php foreach ($books as $book) {
        $subject_id = (int)$book["subject_id"];
        $subject_name =  $mysqli->query("select subject_name from subjects where id = $subject_id")->fetch_assoc();
        $subject_name = $subject_name['subject_name'];
      ?>
    <div class="col-md4">
      <div class="card mb-3">
        <div class="card-body">
          <div class="custom-card-image" style="background-image: url('<?php echo $config['app_url'].$book['image']; ?> ')"></div>
          <div class="">Name: <?php echo $book["name"]; ?></div>
          <div class="">Subject: <?php echo $subject_name; ?></div>
          <div class="">Author: <?php echo $book["author"]; ?></div>

          <div class="">Number Of Available Copies:

            <?php
            // Reduce Button appears to the admin only (copies)
            if($user->isLoggedIn() && $_SESSION['user_role']=='admin'){ ?>
              <form action="" method="post" style="display: inline" >
                <input type="hidden" name="book_id" value="<?php echo $book["id"]; ?>">
                <input type="hidden" name="Minusbook_copies" value="<?php echo $book["copies"]; ?>">
                <button onclick='return confirm("Are you sure?")' class="btn btn-danger" type="submit">-</button>
              </form>
            <?php } ?>


             <?php echo $book["copies"]; ?>


             <?php // Request Button appears to the user only (Request to borrow a book)
              if($user->isLoggedIn() && $_SESSION['user_role']=='user' &&  $book["copies"]>0){ ?>
              <div class="">

               <form action="" method="post" style="display: inline">
                 <input type="hidden" name="book_id" value="<?php echo $book["id"]; ?>">
                 <button onclick="confirm('Are you sure?')" class="btn btn-primary" type="submit" name="borrow">Request to Borrow</button>
               </form>

              </div>

            <?php } ?>

           <?php
           // Add Button appears to the admin only  (copies)
           if($user->isLoggedIn() && $_SESSION['user_role']=='admin'){ ?>
             <form action="" method="post" style="display: inline" >
               <input type="hidden" name="book_id" value="<?php echo $book["id"]; ?>">
               <input type="hidden" name="Addbook_copies" value="<?php if($book["copies"])echo $book["copies"];else echo 0; ?>">
               <button onclick='return confirm("Are you sure?")' class="btn btn-success" type="submit">+</button>
             </form>
           <?php } ?>

         </div>

          <?php
          if ($book["link"]) {
                if ($user->isLoggedIn()) { ?>
                  <div class=""> <a href="<?php echo $book["link"]; ?>" target="_blank">Link</a> </div>
              <?php } else { ?>
                      <div class=""> Log in to see the link</div>
              <?php }
            }else { ?>
                      <div class=""> Sorry, This book isn't available online</div>

              <?php }  ?>


        </div>
      </div>
    </div>
      <?php } ?>
  </div>
  <br><br>
<?php

 echo "<br><br><br><br>";
 unset($Service); // call the destructor to close the class
 ?>

<?php
$mysqli->close();
 require_once 'template/footer.php'; ?>
