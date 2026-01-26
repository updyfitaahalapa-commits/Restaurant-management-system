<?php
include("config/db.php");

// Change 'admin' to the actual username of the admin you want to fix
$username = 'admin'; 
$new_password = '123'; // The new password you want to set

// Hash the password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the database
$query = "UPDATE users SET password = '$hashed_password', role = 'admin' WHERE username = '$username'";

if (mysqli_query($conn, $query)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "Success! Password for user '$username' has been updated to '$new_password'.<br>";
        echo "You can now <a href='auth/login.php'>Login here</a>";
    } else {
        echo "Error: User '$username' not found! <br>";
        echo "Please make sure the admin user exists in the database.";
        // Optional: Attempt to insert if not exists
        echo "<br>Attempting to create admin user...<br>";
        $insert = "INSERT INTO users (fullname, username, password, role) VALUES ('Administrator', '$username', '$hashed_password', 'admin')";
        if (mysqli_query($conn, $insert)) {
             echo "Admin user created successfully! Password is '$new_password'. <a href='auth/login.php'>Login here</a>";
        } else {
            echo "Failed to create admin user: " . mysqli_error($conn);
        }
    }
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
