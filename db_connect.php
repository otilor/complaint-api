<?php

/**
 * Connect to the database
 * 
 * @var  $conn
 * @return $conn
 */
function conn()
 {
     
    $conn = mysqli_connect("HOST", "USERNAME", "PASSWORD", "DB");
    if (!$conn)
    {
        return null;
    }
    return $conn;
 }