
<head>
    <title>Terms of Service – CleanPix</title>
    <meta name="description" content="Read the Terms of Service for CleanPix, the AI-powered background removal service. Understand your rights and responsibilities when using our platform.">
    <meta name="keywords" content="terms of service, CleanPix, background removal, AI service, user agreement">
    <meta name="author" content="CleanPix Team">
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
    <?php include("components/header.php"); ?>
    <main class="container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">
        <h1>Terms of Service</h1>
        <p>Last updated: <?= date("F d, Y") ?></p>

        <p>Welcome to <strong>CleanPix</strong> (https://cleanpix.readernook.com), a service that provides instant
            background removal from images using AI. By using our website, services, or purchasing credits, you agree to
            the following terms and conditions.</p>

        <h2>1. Acceptance of Terms</h2>
        <p>By accessing or using CleanPix, you agree to be bound by these Terms of Service and our <a
                href="/bgremover/privacy.php">Privacy Policy</a>. If you do not agree with any part of these terms, you may not
            use our services.</p>

        <h2>2. Description of Service</h2>
        <p>CleanPix allows users to upload images and receive background-removed versions. The service may include both
            free and paid usage options with limitations described below.</p>

        <h2>3. Account Registration</h2>
        <p>Some features require registration. You are responsible for maintaining the confidentiality of your account
            credentials and for all activities under your account.</p>

        <h2>4. Usage Limits</h2>
        <ul>
            <li>Free users receive limited daily image credits.</li>
            <li>Paid users can purchase one-time credits that never expire.</li>
            <li>Excessive or automated use may result in throttling or suspension.</li>
        </ul>

        <h2>5. User Conduct</h2>
        <p>By using CleanPix, you agree not to:</p>
        <ul>
            <li>Upload illegal, offensive, or harmful content</li>
            <li>Reverse-engineer, copy, or misuse the service</li>
            <li>Use bots or scripts to exploit free usage</li>
        </ul>

        <h2>6. Image Processing & Storage</h2>
        <p>Uploaded images are temporarily stored and deleted shortly after processing unless otherwise stated. We do
            not use your images for training or analytics without consent.</p>

        <h2>7. Payments & Refunds</h2>
        <ul>
            <li>All payments are processed securely via <strong>Stripe</strong>.</li>
            <li>Credits purchased are non-refundable once used.</li>
            <li>Refunds for unused credits may be requested within 7 days, at our discretion.</li>
        </ul>

        <h2>8. Intellectual Property</h2>
        <p>The CleanPix platform, branding, and all underlying technology are owned by CleanPix. Users retain rights to
            the images they upload and download.</p>

        <h2>9. Disclaimer of Warranty</h2>
        <p>CleanPix is provided "as is" without warranties of any kind. We do not guarantee uninterrupted availability
            or accuracy of results for all images.</p>

        <h2>10. Limitation of Liability</h2>
        <p>CleanPix shall not be held liable for any direct, indirect, incidental, or consequential damages arising from
            use or inability to use the service.</p>

        <h2>11. Termination</h2>
        <p>We reserve the right to terminate or suspend your access if you violate these terms or misuse the service.
        </p>

        <h2>12. Modifications</h2>
        <p>We may update these Terms of Service at any time. Continued use after changes implies your acceptance of the
            new terms.</p>

        <h2>13. Contact Us</h2>
        <p>If you have any questions or concerns, please<a href="/bgremover/contact.php" style="margin: 0 10px; color: #2563eb; text-decoration: none;">contact us</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>