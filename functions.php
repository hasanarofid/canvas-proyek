<?php
date_default_timezone_set('Asia/Jakarta');
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "canvas");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    };
    return $rows;
};
