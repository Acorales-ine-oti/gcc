<?php

if (isset($_COOKIE['numero_cedula'])) {
    $cedulaID = $_COOKIE['numero_cedula'];
    // Ahora puedes usar $cedula como necesites
} else {
    // La cookie no existe o expiró
}


// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the request method is POST and that all required fields exist
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['captcha_answer']) && isset($_POST['correct_code']) && isset($_POST['cedula'])) {

    $userAnswer = trim($_POST['captcha_answer']); // User's answer
    $correctCode = trim($_POST['correct_code']); // Correct code generated
    //$cedula = trim($_POST['cedula']);  Original user ID
    $cedula = $cedulaID;
    // Validate the ID again for security (although it was already done in check_user.php) ->if (!preg_match('/^[VEGJPvegjp]{1}-\d{7,9}$/', $cedula))
    
    if (!preg_match('/^\d+$/', $cedula)){
        // Redirect to home if ID is invalid
        header('Location: index.php?error=invalid_cedula');
        exit();
    }

    // Verify if the user's answer matches the correct code
    if ($userAnswer === $correctCode) {
        // CAPTCHA correct. Redirect to registration form.
        // Pass the ID so it can be pre-filled in the registration form.
        header("Location: formRegUsu.php?cedula=" . urlencode($cedula));
        exit();
    } else {
        // CAPTCHA incorrect. Redirect back to CAPTCHA page with an error message.
        header("Location: local_captcha.html?cedula=" . urlencode($cedula) . "&error=captcha_fail");
        exit();
    }
} else {
    // If it's not a valid POST request or data is missing
    header('Location: index.html?error=access_denied');
    exit();
}
?>