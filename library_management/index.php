<!DOCTYPE html>
<html>
<head>

    <title>Library Management System</title>

    <style>

        body{
            font-family:Arial;
            background:#f4f6f9;
            padding:30px;
        }

        .container{
            width:900px;
            margin:auto;
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0px 0px 10px gray;
        }

        h2{
            text-align:center;
            color:#333;
        }

        input{
            width:100%;
            padding:10px;
            margin-top:10px;
        }

        button{
            padding:10px 20px;
            background:#007bff;
            color:white;
            border:none;
            margin-top:15px;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid #ccc;
        }

        th{
            background:#007bff;
            color:white;
            padding:10px;
        }

        td{
            padding:10px;
            text-align:center;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Library Management System</h2>

    <input type="text" id="title" placeholder="Book Title">

    <input type="text" id="author" placeholder="Author Name">

    <input type="text" id="category" placeholder="Category">

    <input type="text" id="status" placeholder="Availability Status">

    <button onclick="addBook()">
        Add Book
    </button>

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody id="bookData"></tbody>

    </table>

</div>

<script>

loadBooks();

function loadBooks(){

    let xhr = new XMLHttpRequest();

    xhr.open("POST","ajax.php",true);

    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.onload = function(){

        document.getElementById("bookData").innerHTML = this.responseText;
    }

    xhr.send("action=fetch");
}

function addBook(){

    let title = document.getElementById("title").value;
    let author = document.getElementById("author").value;
    let category = document.getElementById("category").value;
    let status = document.getElementById("status").value;

    let xhr = new XMLHttpRequest();

    xhr.open("POST","ajax.php",true);

    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.onload = function(){

        loadBooks();

        document.getElementById("title").value = "";
        document.getElementById("author").value = "";
        document.getElementById("category").value = "";
        document.getElementById("status").value = "";
    }

    xhr.send(
        "action=add"
        + "&title=" + title
        + "&author=" + author
        + "&category=" + category
        + "&status=" + status
    );
}

function deleteBook(id){

    let xhr = new XMLHttpRequest();

    xhr.open("POST","ajax.php",true);

    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.onload = function(){

        loadBooks();
    }

    xhr.send("action=delete&id=" + id);
}

</script>

</body>
</html>