<div class="footer-flex">
            <div class="footer-col">
                <h3><?php echo $row_website['site_name']; ?></h3>
                <p>Reliable betting tips across all sports.<br>
                📍 <?php echo $row_website['address']; ?></p>
                <p>📞 <?php echo $row_website['phone1']; ?><br>📧 <?php echo $row_website['site_email']; ?></p>
            </div>
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index#news">All News</a></li>
                    <li><a href="terms">Terms &amp; Conditions</a></li>
                    <li><a href="disclaimer">Disclaimer</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Follow Us</h3>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Newsletter</h3>
                <form class="footer-newsletter">
                    <input type="email" placeholder="Enter your email" required />
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> <?php echo $row_website['site_name']; ?>. All rights reserved.
        </div>