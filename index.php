<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Home</title>
  <?php include 'head-main.html'; ?>
</head>

<body>
  <?php include("components/header.php"); ?>
  <main>
    <section class='hero'>
      <h1>Remove Backgrounds with 1 Click</h1>
      <p>Upload your image and get a transparent background in seconds.</p>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a class='cta-button' href='dashboard.php'>Remove Background Now</a>
      <?php else: ?>
        <a class='cta-button' href='register.php'>Get Started Free</a>
      <?php endif; ?>

    </section>

    <section class='features'>
      <h2>Why Choose Us?</h2>
      <ul>
        <li>⚡ Fast & Accurate Background Removal</li>
        <li>🧠 Powered by AI</li>
        <li>💾 Download in High Resolution</li>
        <li>🔒 Secure & Private</li>
      </ul>
    </section>
  </main>
  <section style="background-color: #f0f8ff; padding: 60px 20px;">
    <div style="max-width: 1000px; margin: 0 auto; text-align: center;">
      <h2 style="font-size: 2rem; font-weight: bold; margin-bottom: 20px;">Join Thousands of Happy Users</h2>
      <p style="font-size: 1.1rem; color: #444; margin-bottom: 40px;">
        We're proud to be helping people around the world create clean, professional images.
      </p>

      <div style="display: flex; justify-content: center;">
        <div style="background: white; padding: 30px 40px; border-radius: 10px; box-shadow: 0 0 12px rgba(0,0,0,0.06);">
          <h3 style="font-size: 2.2rem; color: #2563eb; margin: 0;">10,000+</h3>
          <p style="margin: 8px 0 0;">Images Processed</p>
        </div>
      </div>
    </div>
  </section>

  <section style="background-color: #fff; padding: 60px 20px;">
  <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
    <h2 style="font-size: 2rem; font-weight: bold; margin-bottom: 30px;">How CleanPix Helps You Succeed</h2>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">

      <!-- Video 1 -->
      <iframe width="360" height="215" src="https://www.youtube.com/embed/Sdp22LC-czc" 
              title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; 
              clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
              allowfullscreen></iframe>

      <!-- Video 2 -->
      <iframe width="360" height="215" src="https://www.youtube.com/embed/PDrCLnmudbs" 
              frameborder="0" allowfullscreen></iframe>

      <!-- Video 3 -->
      <iframe width="360" height="215" src="https://www.youtube.com/embed/s3e9rOwseGw" 
              frameborder="0" allowfullscreen></iframe>

      <!-- Video 4 -->
      <iframe width="360" height="215" src="https://www.youtube.com/embed/CZng5NghXDg" 
              frameborder="0" allowfullscreen></iframe>


      <!-- Video 5 -->
      <iframe width="360" height="215" src="https://www.youtube.com/embed/ZQrIYGhDRnc" 
              frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
</section>

  <section style="background-color: #f9fafb; padding: 60px 20px;">
    <div style="max-width: 1000px; margin: 0 auto; text-align: center;">
      <h2 style="font-size: 2rem; font-weight: bold; margin-bottom: 20px;">What Our Users Say</h2>
      <p style="font-size: 1.1rem; color: #444; margin-bottom: 40px;">
        Trusted by creators, sellers, and professionals around the world.
      </p>

      <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
        <!-- Testimonial 1 -->
        <div
          style="flex: 1 1 250px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 12px rgba(0,0,0,0.06); text-align: left;">
          <p style="font-style: italic;">“Super easy to use. I cleaned up product photos in seconds. Love that credits
            never expire!”</p>
          <div style="margin-top: 10px; font-weight: bold;">— Priya S.</div>
          <div style="color: gold;">★★★★★</div>
        </div>

        <!-- Testimonial 2 -->
        <div
          style="flex: 1 1 250px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 12px rgba(0,0,0,0.06); text-align: left;">
          <p style="font-style: italic;">“No more subscriptions! CleanPix gives me exactly what I need—fast and
            private.”</p>
          <div style="margin-top: 10px; font-weight: bold;">— Mark T.</div>
          <div style="color: gold;">★★★★★</div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>
  <script src="header.js"></script>
</body>

</html>