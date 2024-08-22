<?php
function check_login()
{
    // Start the session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Check if the session 'login' variable is set and not empty
    if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
        // Redirect to the login page
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "../admin.php";

        // Ensure session 'login' is empty
        $_SESSION["login"] = "";

        header("Location: http://$host$uri/$extra");
        exit(); // Ensure no further code is executed
    } else {
        echo "<script>alert('No Session Found');</script>";
    }
}

?>

