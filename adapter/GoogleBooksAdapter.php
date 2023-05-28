<?php
class GoogleBooksAdapter
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function searchBooks($query)
    {
        $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query) . "&key=" . $this->apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $books = array();
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $book = array();
                $book['title'] = isset($item['volumeInfo']['title']) ? $item['volumeInfo']['title'] : '';
                $book['author'] = isset($item['volumeInfo']['authors'][0]) ? $item['volumeInfo']['authors'][0] : '';
                $book['description'] = isset($item['volumeInfo']['description']) ? $item['volumeInfo']['description'] : '';
                $books[] = $book;
            }
        }

        return $books;
    }
}
