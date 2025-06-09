<?php
include 'db_connection.php';

if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Database connection failed.";
}
?>
