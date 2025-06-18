<?php
$pageTitle = "How to Remove Backgrounds for eCommerce, Resumes, and More – CleanPix";
$pageDescription = "Learn how to instantly remove image backgrounds using CleanPix for eCommerce, resumes, social media, and more. Try free today – no login required.";
include("components/header.php");
?>

<head>
    <title>Home</title>
    <?php include 'head-main.html'; ?>

    <style>
        /* Base styling for blog container */
        .container {
            font-family: 'Segoe UI', sans-serif;
            font-size: 1.05rem;
            line-height: 1.7;
            color: #333;
        }

        /* Headings */
        .container h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #222;
            font-weight: 700;
        }

        .container h2 {
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #111;
            font-weight: 600;
            border-left: 4px solid #3b82f6;
            padding-left: 10px;
            background: #f0f8ff;
        }

        /* Paragraphs */
        .container p {
            margin-bottom: 1rem;
        }

        /* Lists */
        .container ul,
        .container ol {
            padding-left: 1.4rem;
            margin-bottom: 1rem;
        }

        .container ul li,
        .container ol li {
            margin-bottom: 0.5rem;
        }

        /* Link styling */
        .container a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }

        /* Call to action link */
        .container a.call-to-action {
            display: inline-block;
            background-color: #2563eb;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 6px;
            margin-top: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .container a.call-to-action:hover {
            background-color: #1e40af;
        }

        /* Tip & highlight box */
        .container .tip-box {
            background-color: #f0f9ff;
            padding: 15px;
            border-left: 4px solid #0ea5e9;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <main class="container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">
    <h1>Privacy Policy</h1>
    <p>Last updated: <?= date("F d, Y") ?></p>

    <p>At <strong>CleanPix</strong> (https://cleanpix.readernook.com), we are committed to protecting your privacy and
        handling your data transparently and securely. This Privacy Policy outlines how we collect, use, and safeguard
        your information.</p>

    <h2>1. Information We Collect</h2>
    <p>We collect the following types of data:</p>
    <ul>
        <li><strong>Uploaded Images:</strong> Images you upload are processed and temporarily stored for background
            removal. They are deleted shortly after processing unless otherwise stated.</li>
        <li><strong>User Accounts:</strong> When you register, we collect your name, email address, and password
            (encrypted).</li>
        <li><strong>Payment Information:</strong> We do not store credit card details. Payments are processed securely
            via <strong>Stripe</strong>.</li>
        <li><strong>Analytics:</strong> We may use tools like Google Analytics to understand how users interact with the
            site (e.g., browser type, pages visited).</li>
    </ul>

    <h2>2. How We Use Your Information</h2>
    <p>Your data is used for the following purposes:</p>
    <ul>
        <li>To process and return edited images</li>
        <li>To manage user accounts and credit balances</li>
        <li>To respond to customer support inquiries</li>
        <li>To improve site performance and user experience</li>
        <li>To send service-related communications (e.g., password resets)</li>
    </ul>

    <h2>3. Image Privacy</h2>
    <p>Your uploaded images are used solely for background removal. We do not use, share, or retain your images after
        processing unless you explicitly request persistent storage (e.g., in your account).</p>

    <h2>4. Cookies</h2>
    <p>We use cookies to maintain session state and improve your experience. You can choose to disable cookies in your
        browser settings, though some features may not function properly.</p>

    <h2>5. Sharing Your Data</h2>
    <p>We do not sell or rent your personal information. We may share data with third parties only when:</p>
    <ul>
        <li>It’s required to process payments or deliver services (e.g., Stripe)</li>
        <li>We’re legally required to do so (e.g., law enforcement requests)</li>
    </ul>

    <h2>6. Data Security</h2>
    <p>We implement strong security measures to protect your information, including SSL encryption, database access
        control, and regular server updates.</p>

    <h2>7. Your Rights</h2>
    <p>You have the right to:</p>
    <ul>
        <li>Access or update your personal data</li>
        <li>Request deletion of your account and associated data</li>
        <li>Opt out of marketing emails (if enabled)</li>
    </ul>

    <h2>8. Children's Privacy</h2>
    <p>CleanPix is not intended for children under the age of 13. We do not knowingly collect data from children.</p>

    <h2>9. Changes to This Policy</h2>
    <p>We may update this Privacy Policy occasionally. We’ll notify users by posting an updated version on this page
        with the revised date.</p>

    <h2>10. Contact Us</h2>
    <p>If you have any questions about this Privacy Policy, please<a href="/bgremover/contact.php" style="margin: 0 10px; color: #2563eb; text-decoration: none;">contact us</a></p>
    </main>

    <?php include("footer.php"); ?>

</body>