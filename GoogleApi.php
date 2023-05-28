<?php
require_once 'adapter/GoogleBooksAdapter.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Search</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-weight: normal;
            color: #333;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-input {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .book-list {
            list-style: none;
            padding: 0;
            text-align: left;
        }

        .book-list-item {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 3px;
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .book-title {
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }

        .book-author {
            color: #888;
            margin-top: 5px;
        }

        .book-description {
            margin-top: 10px;
            color: #555;
        }

        .no-results {
            color: #555;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php require_once 'template/header.php'; ?>

    <div class="container">
        <h2>Book Search</h2>
        <form class="search-form" action="" method="GET">
            <input class="search-input" type="text" name="query" placeholder="Enter a book title or author" required>
            <button class="search-button" type="submit">Search</button>
        </form>

        <?php
        if (isset($_GET['query'])) {
            $query = mysqli_real_escape_string($mysqli,$_GET['query']);
            $apiKey = 'AIzaSyAfFnJ0npfO5l-PD0Sua9sl_r-xFedgg5I';
            $adapter = new GoogleBooksAdapter($apiKey);
            $books = $adapter->searchBooks($query);
            if (!empty($books)) {
                echo '<ul class="book-list">';
                foreach ($books as $book) {
                    echo '<li class="book-list-item">';
                    echo '<h3 class="book-title">' . $book['title'] . '</h3>';
                    echo '<p class="book-author">by ' . $book['author'] . '</p>';
                    echo '<p class="book-description">' . $book['description'] . '</p>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="no-results">No books found.</p>';
            }
        }
        ?>
    </div>

    <?php require_once 'template/footer.php'; ?>
</body>
</html>
