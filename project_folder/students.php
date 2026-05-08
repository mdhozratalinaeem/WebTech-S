<?php

$students = array(

    array(
        "name" => "MD Hozrat Ali Naeem",
        "id" => "23-54595-3",
        "department" => "CSE",
        "cgpa" => "3.89"
    ),

    array(
        "name" => "Rahim Ahmed",
        "id" => "23-54596-3",
        "department" => "CSE",
        "cgpa" => "3.75"
    ),

    array(
        "name" => "Karim Hasan",
        "id" => "23-54597-3",
        "department" => "EEE",
        "cgpa" => "3.90"
    )
);

echo json_encode($students);

?>