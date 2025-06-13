<header>
  <div class="logo">BG Remover</div>
  <button class="menu-toggle" id="menu-toggle">&#9776;</button>
  <nav>
    <ul id="nav-menu">
      <li><a href="index.php">Home</a></li>
      <li><a href="pricing.php">Pricing</a></li>
      
      <?php if (!isset($_SESSION['user_id'])): ?>
        <li><a href="register.php">Sign Up</a></li>
        <li><a href="login.php">Login</a></li>
      <?php else: ?>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li>
          <a href="profile.php" >
            <?= htmlspecialchars($_SESSION['first_name']) ?>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

