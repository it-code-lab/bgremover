<?php
session_start();
?>
<?php
$pageTitle = "Contact Us – CleanPix";
$pageDescription = "Reach out to CleanPix with your questions, feedback, or support requests.";

// Initialize variables
$name = $email = $message = $captcha = "";
$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");
    $captcha = trim($_POST["captcha"] ?? "");
    $storedCaptcha = $_SESSION["captcha_code"] ?? "";

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strcasecmp($captcha, $storedCaptcha) !== 0) {
        $error = "Invalid CAPTCHA. Please try again.";
    } else {
        // Send email
        $to = "mail2kimim@gmail.com"; // Replace with your support email
        $headers = "From: $email";

        if (sendContactEmail($to, $name, $email, $message)) {
            $success = "Thank you! Your message has been sent.";
            $name = $email = $message = "";
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    }
}
?>

<head>
    <title>Contact Us</title>
    <meta name="description" content="Reach out to CleanPix with your questions, feedback, or support requests.">
    <meta name="keywords" content="contact, support, feedback, CleanPix">
    <?php include 'head-main.html'; ?>
    <style>
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .contact-form h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .contact-form label {
            font-weight: bold;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form .captcha-box {
            display: flex;
            align-items: center;
        }

        .contact-form .captcha-box img {
            margin-right: 10px;
            border: 1px solid #ddd;
        }

        .contact-form button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #1e40af;
        }

        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include("components/header.php"); ?>
    <?php
    require_once 'mailer.php';
    ?>
    <main class="contact-form">
        <h1>Contact Us</h1>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            <label for="name">Name *</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>

            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

            <label for="message">Message *</label>
            <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>

            <label for="captcha">Enter CAPTCHA *</label>
            <div class="captcha-box">
                <img src="/bgremover/generate_captcha.php" alt="CAPTCHA">
                <input type="text" name="captcha" id="captcha" required>
            </div>

            <button type="submit">Send Message</button>
        </form>
    </main>

    <?php include("footer.php"); ?>
</body>