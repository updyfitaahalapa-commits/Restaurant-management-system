<?php
include("config/db.php");

// Set the username you want to fix
$username = 'staff'; 
$new_password = '123'; 

// Hash the password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update
$query = "UPDATE users SET password = '$hashed_password', role = 'staff' WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_affected_rows($conn) > 0) {
    echo "<h1>Success!</h1>";
    echo "<p>Password for user <b>'$username'</b> has been reset to: <b>$new_password</b></p>";
    echo "<a href='auth/login.php'>Go to Login Page</a>";
} else {
    echo "<h1>User Not Found</h1>";
    echo "<p>Could not find a user named '$username'.</p>";
    echo "<p>Attempting to create it...</p>";
    
    $insert = "INSERT INTO users (fullname, username, password, role) VALUES ('Staff Member', '$username', '$hashed_password', 'staff')";
    if(mysqli_query($conn, $insert)){
        echo "<p style='color:green'>Created new user '$username' with password '$new_password'!</p>";
        echo "<a href='auth/login.php'>Go to Login Page</a>";
    } else {
        echo "<p style='color:red'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
