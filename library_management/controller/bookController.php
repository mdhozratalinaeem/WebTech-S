<?php

include __DIR__ . "/../model/bookModel.php";

if(isset($_POST['action'])){

    $action = $_POST['action'];

    if($action == "add"){

        addBook(
            $_POST['title'],
            $_POST['author'],
            $_POST['category'],
            $_POST['status']
        );
    }

    if($action == "fetch"){

        $result = getBooks();

        while($row = mysqli_fetch_assoc($result)){

            echo "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['author']}</td>
                <td>{$row['category']}</td>
                <td>{$row['status']}</td>

                <td>
                    <button onclick='deleteBook({$row['id']})'>
                        Delete
                    </button>
                </td>
            </tr>
            ";
        }
    }

    if($action == "delete"){

        deleteBook($_POST['id']);
    }

    if($action == "update"){

        updateBook(
            $_POST['id'],
            $_POST['title'],
            $_POST['author'],
            $_POST['category'],
            $_POST['status']
        );
    }
}

?>