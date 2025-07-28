<?php
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Blog Posts - <?php echo $app_name; ?></title>
  <?php include('partials/head.php'); ?>
</head>

<body>
  <div class="site-wrap">

    <!-- Navbar -->
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar py-4" role="banner">
      <div class="container">
        <div class="d-flex align-items-center">
          <div class="site-logo">
            <a href="index">
              <img src="<?php echo $app_logo; ?>" alt="Logo">
            </a>
          </div>
          <div class="ml-auto">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <?php include('partials/navbar.php'); ?>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right text-white"><span class="icon-menu h3 text-white"></span></a>
          </div>
        </div>
      </div>
    </header>

    <div class="hero overlay" style="background-image: url('images/bg_3.jpg');">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-9 mx-auto text-center">
            <h1 class="text-white">Our Blog Posts</h1>
<?php
  $post_date = date('F j, Y', strtotime($row['created_at']));
  $author = htmlspecialchars($row['author'] ?? 'Admin');
?>

<p>  <span><?= $post_date ?></span>  <span class="mx-3">&bullet;</span>  <span>By <?= $author ?></span></p>          
</div>
        </div>
      </div>
    </div>

    
    <div class="site-section first-section">
      <div class="container">
        <div class="row">
          <div class="col-md-8 blog-content">
            <p class="lead">The Passion, The Drama, The Game – Why Football Unites Us All.</p>
            <p><img src="images/img_1.jpg" alt="Image" class="img-fluid"></p>

            <blockquote><p>Whether it's a packed stadium roaring with chants or a quiet room with fans glued to their screens, football never fails to ignite emotions. It's more than just a sport—it's a global language spoken in goals, tackles, and unforgettable moments.</p></blockquote>

            <p>From last-minute winners to stunning upsets, football is unpredictable and thrilling. The 2024/25 season has already given us goosebumps—from shock wins in underdog leagues to historic milestones in top-tier competitions. No matter the team you support, there’s always a moment that stays with you forever.</p>
            <p><img src="images/img_2.jpg" alt="Image" class="img-fluid"></p>
            <p>One of the most beautiful things about football is how it brings people together. From Lagos to London, Buenos Aires to Bangkok, fans chant the same songs and feel the same heartbreaks. With major tournaments like the UEFA Champions League, AFCON, Copa América, and the Premier League, football continues to unite cultures and continents.</p>
            <p>As the summer transfer window heats up, all eyes are on key moves that could change the course of next season. We'll be covering top stories, tactical breakdowns, and exclusive fan content. Don't miss out—subscribe to our newsletter and follow us on social media.</p>
            


            <div class="pt-5">
              <h3 class="mb-5 text-white">2 Comments</h3>
              <ul class="comment-list">
                <li class="comment">
                  <div class="vcard bio">
                    <img src="images/person_1.jpg" alt="Image placeholder">
                  </div>
                  <div class="comment-body">
                    <h3>Bassey Inyang</h3>
                    <div class="meta">Febuary 18, 2025 at 8:21pm</div>
                    <p>One of the most beautiful things about football is how it brings people together. From Lagos to London, Buenos Aires to Bangkok, fans chant the same songs and feel the same heartbreaks</p>
                    <p><a href="#" class="reply">Reply</a></p>
                  </div>
                </li>

                <li class="comment">
                  <div class="vcard bio">
                    <img src="images/person_1.jpg" alt="Image placeholder">
                  </div>
                  <div class="comment-body">
                    <h3>Mary Ekpo</h3>
                    <div class="meta">January 9, 2025 at 2:21pm</div>
                    <p>One of the most beautiful things about football is how it brings people together. From Lagos to London, Buenos Aires to Bangkok, fans chant the same songs and feel the same heartbreaks</p>
                    <p><a href="#" class="reply">Reply</a></p>
                  </div>

              </ul>
              <!-- END comment-list -->
              
              <div class="comment-form-wrap pt-5">
                <h3 class="mb-5">Leave a comment</h3>
                <form action="#" class="p-5 bg-light">
                  <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" class="form-control" id="name">
                  </div>
                  <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control" id="email">
                  </div>
                  <div class="form-group">
                    <label for="website">Website</label>
                    <input type="url" class="form-control" id="website">
                  </div>

                  <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="" id="message" cols="30" rows="10" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Post Comment" class="btn btn-primary py-3 px-4 text-white">
                  </div>

                </form>
              </div>
            </div>

          </div>
          <div class="col-md-4 sidebar">
            <div class="sidebar-box">
              <form action="#" class="search-form">
                <div class="form-group">
                  <span class="icon fa fa-search"></span>
                  <input type="text" class="form-control" placeholder="Type a keyword and hit enter">
                </div>
              </form>
            </div>
            <div class="sidebar-box">
              <div class="categories">
                <h3 class="text-uppercase">Categories</h3>
                <li><a href="#">Football<span>(12)</span></a></li>
                <li><a href="#">LiveScore <span>(22)</span></a></li>
                <li><a href="#">Transfers <span>(37)</span></a></li>
                <li><a href="#">Matches <span>(42)</span></a></li>
              </div>
            </div>
            <div class="sidebar-box">
              <img src="images/person_1.jpg" alt="Image placeholder" class="img-fluid mb-4">
              <h3 class="text-uppercase">About The Author</h3>
              <p>Smart Anwana is a passionate football analyst, digital creator, and lifelong fan of the beautiful game. With a sharp eye for tactical trends and a love for storytelling, Emediong brings football to life beyond the final whistle.</p>
              <p><a href="#" class="btn btn-primary text-white">Read More</a></p>
            </div>

            <div class="sidebar-box">
              <h3 class="text-uppercase">Paragraph</h3>
              <p>Whether it's breaking down last-minute drama, exploring behind-the-scenes transfers, or celebrating the culture of the sport, his writing reflects a deep respect for football's power to unite fans around the world.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

       <!-- Floating WhatsApp Button -->
    <?php include('partials/whatsapp.php'); ?>

    <!-- Footer -->
    <footer class="footer-section">
      <?php include('partials/footer.php'); ?>
    </footer>
  </div>

  <!-- Scripts -->
  <?php include('partials/sweetalert.php'); ?>
  <?php include('partials/script.php'); ?>

  
</body>
</html>