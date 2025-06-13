<header>
  <div class="logo">BG Remover</div>
  <button class="menu-toggle" id="menu-toggle">&#9776;</button>
  <nav>
    <ul id="nav-menu">
      <li><a href="index.php">Home</a></li>
      <li><a href="pricing.php">Pricing</a></li>
      <li><a href="register.php">Sign Up</a></li>
      <li><a href="login.php">Login</a></li>

      <!-- Example user dropdown -->
      <?php if (isset($_SESSION['user_id'])): ?>
      <li>
        <a href="#"><?= $_SESSION['email'] ?></a>
        <div class="user-dropdown">
          <a href="dashboard.php">Dashboard</a>
          <a href="logout.php">Logout</a>
        </div>
      </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
