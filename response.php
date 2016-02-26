<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$db = new PDO('mysql:host=localhost;dbname=books;charset=utf8', 'root', 'azcim1479');

// Book Adding
if (isset($_POST["content_txt"]) && isset($_POST["year_txt"])) {
    if (!is_numeric($_POST["year_txt"]) || strlen($_POST["year_txt"]) != 4 ||
            $_POST["year_txt"] < 1 || $_POST["year_txt"] != round($_POST["year_txt"])) {
        $output3 = json_encode([
            "Error" => "PHP error",
        ]);
        echo $output3;
    } else {
        // dodawanie nazwy i roku ksiazki
        $insert_row = $db->query("INSERT INTO books(Title, Year) VALUES('"
                . $_POST["content_txt"] . "', " . $_POST["year_txt"] . ")");
        $book_inserted_id = $db->lastInsertId();
        // dodawanie autorow
        
        $author_arr = explode(',', $_POST["author_txt"]);
        $authors = array_map('trim', $author_arr);
        if ($authors[0] == ''){
            $authors_all = $_POST["author_txt_typed"];
        } else {
            $authors_all = array_merge($authors, $_POST["author_txt_typed"]);
        }
        foreach ($authors_all as $item) {
            $check_same = $db->query("SELECT id from authors where Name='" . $item . "'");
            $same = $check_same->fetch();
            if ($same) {
                $author_inserted_id = $same[0];
            } else {
                $db->query("INSERT INTO authors(Name) VALUES('" . $item . "')");
                $author_inserted_id = $db->lastInsertId();
            }
            $db->query("INSERT INTO authors_to_books(author_id, book_id) VALUES ("
                    . $author_inserted_id . "," . $book_inserted_id . ")");
        }
    }
    if ($insert_row) {
        $my_id = $book_inserted_id;
        $output = json_encode([
            "Id" => $my_id,
            "Name" => $_POST["content_txt"],
            "Year" => $_POST["year_txt"],
            "Author" => $authors_all
        ]);
        echo $output;
    } else {
        die('Blad');
    }

// Book Delete    
} elseif (isset($_POST["recordToDelete"]) && is_numeric($_POST["recordToDelete"])) {

    $idToDelete = filter_var($_POST["recordToDelete"], FILTER_SANITIZE_NUMBER_INT);
    $db->query("DELETE FROM books WHERE id = " . $idToDelete);
    $db->query("DELETE FROM authors_to_books WHERE book_id = " . $idToDelete);

// Book Edition
} elseif (isset($_POST["content_txt2"]) && isset($_POST["year_txt2"]) && isset($_POST["id_txt2"])) {

    // update books
    $insert_row3 = $db->query("UPDATE books SET Title = '" . $_POST["content_txt2"]
            . "', Year = " . $_POST["year_txt2"] . " WHERE id = " . $_POST["id_txt2"]);

    // update authors and authors_to_books
    $db->query("DELETE FROM authors_to_books WHERE book_id = " . $_POST["id_txt2"]);
    $author_arr2 = explode(',', $_POST["author_txt2"]);
    $authors2 = array_map('trim', $author_arr2);

    foreach ($authors2 as $item) {
        $check_same2 = $db->query("SELECT id from authors where Name='" . $item . "'");
        $same2 = $check_same2->fetch();

        if ($same2) {
            $author_inserted_id2 = $same2[0];
        } else {
            $db->query("INSERT INTO authors(Name) VALUES ('" . $item . "')");
            $author_inserted_id2 = $db->lastInsertId();
        }
        $db->query("INSERT INTO authors_to_books(author_id, book_id) VALUES ("
                . $author_inserted_id2 . "," . $_POST["id_txt2"] . ")");
    }

    if ($insert_row3) {
        
    }
    $output2 = json_encode([
        "Id2" => $_POST["id_txt2"],
        "Name2" => $_POST["content_txt2"],
        "Year2" => $_POST["year_txt2"],
        "Author2" => $authors2
    ]);
    echo $output2;
// Author Delete
} elseif (isset($_POST["authorToDelete"])) {

    $author_id_del = filter_var($_POST["authorToDelete"], FILTER_SANITIZE_NUMBER_INT);
    
    $author_books_id = $db->query("SELECT book_id FROM authors_to_books WHERE author_id=" . $author_id_del);
    
    $author_check = $author_books_id->fetch();
    
    if ($author_check) {
        $output3 = json_encode([
           "Error2" => 0
        ]);
    } else {
        $db->query("DELETE FROM authors WHERE id = " . $author_id_del);
        $output3 = json_encode([
           "Error2" => 1
        ]);
    
        
    }
    echo $output3;
    
// Author Edition
} elseif (isset($_POST["content_txt3"]) && isset($_POST["id_txt3"])) {
    $author_name = trim($_POST["content_txt3"]);
    $db->query("UPDATE authors SET Name = '" . $author_name
            . "' WHERE id = " . $_POST["id_txt3"]);
        $output4 = json_encode([
        "Id3" => $_POST["id_txt3"],
        "Name3" => $_POST["content_txt3"],

    ]);
    echo $output4;
 
// Author Adding
} elseif (isset($_POST["author_txt2"])) {
    $db->query("INSERT INTO authors(Name) VALUES ('" . $_POST["author_txt2"] . "')");
    $author_inserted_id4 = $db->lastInsertId();
    
    $output5 = json_encode([
        "Id5" => $author_inserted_id4,
        "Author5" => $_POST["author_txt2"]
    ]);
    echo $output5;
    
}
    





    