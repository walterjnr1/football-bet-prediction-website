     
     <div class="main-content">
            <div class="cards-row" id ="loading-cards">
                <div class="card"><div class="loader"></div></div>
                <div class="card"><div class="loader"></div></div>
            </div>
            <p><a href="../index">View Today's Tips</a></p>
            <div class="past-label">
                Past successful fixed matches
            </div>
            <div class="past-matches">
                <!-- Day 1 -->
                <div class="past-matches-day">
                    <div class="match-label">Monday, 07/21</div>
                    <div class="match-day-cards">
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Premier League</div>
                            <div class="time"><i class="fas fa-clock"></i> 18:00</div>
                            <div class="teams">Chelsea vs Arsenal</div>
                            <div class="odds"><i class="fas fa-coins"></i> 8.50</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">2 - 1</div>
                        </div>
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> La Liga</div>
                            <div class="time"><i class="fas fa-clock"></i> 20:30</div>
                            <div class="teams">Real Madrid vs Barcelona</div>
                            <div class="odds"><i class="fas fa-coins"></i> 10.00</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">1 - 0</div>
                        </div>
                    </div>
                </div>
                <!-- Day 2 -->
                <div class="past-matches-day">
                    <div class="match-label">Sunday, 07/20</div>
                    <div class="match-day-cards">
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Serie A</div>
                            <div class="time"><i class="fas fa-clock"></i> 17:00</div>
                            <div class="teams">Juventus vs Inter</div>
                            <div class="odds"><i class="fas fa-coins"></i> 7.20</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">3 - 2</div>
                        </div>
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Bundesliga</div>
                            <div class="time"><i class="fas fa-clock"></i> 19:45</div>
                            <div class="teams">Bayern vs Dortmund</div>
                            <div class="odds"><i class="fas fa-coins"></i> 9.00</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">2 - 0</div>
                        </div>
                    </div>
                </div>
                <!-- Day 3 -->
                <div class="past-matches-day">
                    <div class="match-label">Saturday, 07/19</div>
                    <div class="match-day-cards">
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Ligue 1</div>
                            <div class="time"><i class="fas fa-clock"></i> 16:00</div>
                            <div class="teams">PSG vs Lyon</div>
                            <div class="odds"><i class="fas fa-coins"></i> 6.80</div>
                            <div class="result lose"><i class="fas fa-times"></i> Lose</div>
                            <div class="score">1 - 2</div>
                        </div>
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Eredivisie</div>
                            <div class="time"><i class="fas fa-clock"></i> 21:00</div>
                            <div class="teams">Ajax vs PSV</div>
                            <div class="odds"><i class="fas fa-coins"></i> 7.50</div>
                            <div class="result lose"><i class="fas fa-times"></i> Lose</div>
                            <div class="score">0 - 1</div>
                        </div>
                    </div>
                </div>
                <!-- Day 4 -->
                <div class="past-matches-day">
                    <div class="match-label">Friday, 07/18</div>
                    <div class="match-day-cards">
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Championship</div>
                            <div class="time"><i class="fas fa-clock"></i> 18:30</div>
                            <div class="teams">Norwich vs Leeds</div>
                            <div class="odds"><i class="fas fa-coins"></i> 8.10</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">2 - 0</div>
                        </div>
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Primeira Liga</div>
                            <div class="time"><i class="fas fa-clock"></i> 20:00</div>
                            <div class="teams">Porto vs Benfica</div>
                            <div class="odds"><i class="fas fa-coins"></i> 9.30</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">1 - 0</div>
                        </div>
                    </div>
                </div>
                <!-- Day 5 -->
                <div class="past-matches-day">
                    <div class="match-label">Thursday, 07/17</div>
                    <div class="match-day-cards">
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> Super Lig</div>
                            <div class="time"><i class="fas fa-clock"></i> 17:30</div>
                            <div class="teams">Galatasaray vs Fenerbahce</div>
                            <div class="odds"><i class="fas fa-coins"></i> 7.80</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">2 - 1</div>
                        </div>
                        <div class="match-card">
                            <div class="league"><i class="fas fa-trophy"></i> MLS</div>
                            <div class="time"><i class="fas fa-clock"></i> 22:00</div>
                            <div class="teams">LA Galaxy vs NY Red Bulls</div>
                            <div class="odds"><i class="fas fa-coins"></i> 8.60</div>
                            <div class="result"><i class="fas fa-check"></i> Win</div>
                            <div class="score">3 - 2</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="confirmation-link">
                <a href="confirm-ticket.html">Click here to confirm our winning tickets</a>
            </div>
        </div>
        <div class="sidebar">
            <div class="vip-results">
                <?php

$sql = "SELECT result_date, outcome FROM vip_results ORDER BY result_date DESC LIMIT 6";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to get day name from date
function getDayName($date) {
    return date('D', strtotime($date)); // e.g., Mon, Tue
}
?>

<h4>Recent VIP Results</h4>
<div class="result-grid">
    <?php foreach ($results as $row): ?>
        <div class="result-day <?= $row['outcome'] == 'won' ? 'win' : 'lose' ?>">
            <div class="icon-square">
                <i class="fas <?= $row['outcome'] == 'won' ? 'fa-check' : 'fa-times' ?>"></i>
            </div>
            <div class="day"><?= getDayName($row['result_date']) ?></div>
            <div class="date"><?= date('m/d', strtotime($row['result_date'])) ?></div>
        </div>
    <?php endforeach; ?>
</div>

            </div>
            <div class="review-section">
                <h4>Leave a Review</h4>
                <form class="review-form" method="POST">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo $row_user['full_name']; ?>" readonly>

                    <label for="comment">Comment</label>
                    <textarea id="comment" name="comment" placeholder="Write your comment" required></textarea>

                    <label for="rating">Select Rating</label>
                    <select id="rating" name="rating" required>
                        <option value="">Select Rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                        <option value="4">⭐⭐⭐⭐ - Very Good</option>
                        <option value="3">⭐⭐⭐ - Good</option>
                        <option value="2">⭐⭐ - Fair</option>
                        <option value="1">⭐ - Poor</option>
                    </select>

                    <button type="submit" name = "btnreview">Submit</button>
                </form>
            </div>
        </div>


         <a href="https://wa.me/<?php echo $row_website['whatsapp_phone']; ?>" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>