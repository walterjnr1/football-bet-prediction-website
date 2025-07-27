<?php
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Live Scores - <?php echo htmlspecialchars($app_name); ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
  <link rel="shortcut icon" href="../<?php echo $app_logo; ?>" type="image/x-icon" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f4f4f4;
      color: #333;
    }
    .container-live {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    h2 {
      text-align: center;
      color: #2d8cf0;
      margin-bottom: 20px;
    }
    .tabs {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .tab-button {
      background: #2d8cf0;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      margin: 5px;
      border-radius: 5px;
    }
    .tab-button.active {
      background: #1a6fc2;
    }
    .league-header {
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 10px;
      font-size: 18px;
      color: #2d8cf0;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
    }
    .match-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fafafa;
      padding: 15px 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .teams {
      font-weight: 600;
      color: #444;
    }
    .score {
      font-size: 20px;
      font-weight: bold;
      color: #111;
    }
    .status {
      font-size: 13px;
      color: #888;
    }
    .pagination {
      text-align: center;
      margin-top: 30px;
    }
    .pagination button {
      background: #2d8cf0;
      color: white;
      border: none;
      margin: 0 5px;
      padding: 8px 14px;
      border-radius: 5px;
      cursor: pointer;
    }
    .pagination button:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    footer {
      background: #000;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 60px;
    }
    @media (max-width: 600px) {
      .match-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
    }
  </style>
</head>
<body>
  

  <div class="container-live">
    <h2><i class="fas fa-futbol"></i> Live Sports Scores</h2>
    <div class="tabs">
      <button class="tab-button active" onclick="switchTab('football', event)">Football</button>
      <button class="tab-button" onclick="switchTab('basketball', event)">Basketball</button>
      <button class="tab-button" onclick="switchTab('baseball', event)">Baseball</button>
    </div>

    <div id="liveScores">Loading scores...</div>
    <div class="pagination" id="pagination"></div>
  </div>

  <footer>
    <?php include 'partials/footer.php'; ?>
  </footer>

  <script>
    let currentTab = 'football';
    let currentPage = 1;
    const itemsPerPage = 20;
    let allScores = {
      football: [],
      basketball: [],
      baseball: []
    };

    function switchTab(tab, event) {
      currentTab = tab;
      currentPage = 1;
      document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
      renderMatches();
      renderPagination();
    }

    async function fetchAllScores() {
      try {
        const footballRes = await fetch("https://v3.football.api-sports.io/fixtures?live=all", {
          headers: { "x-apisports-key": "927c9f5d66e1ddd44bae5d9772237178" }
        });
        const footballData = await footballRes.json();
        allScores.football = footballData.response;

        // Placeholder data for demo purposes
        allScores.basketball = [];
        allScores.baseball = [];

        renderMatches();
        renderPagination();
      } catch (error) {
        document.getElementById("liveScores").innerHTML = "<p>Error loading scores.</p>";
        console.error(error);
      }
    }

    function groupByLeague(matches) {
      const leagueGroups = {};
      matches.forEach(match => {
        const league = match.league?.name ?? "Unknown League";
        if (!leagueGroups[league]) {
          leagueGroups[league] = [];
        }
        leagueGroups[league].push(match);
      });
      return leagueGroups;
    }

    function renderMatches() {
      const container = document.getElementById("liveScores");
      container.innerHTML = "";
      const matches = allScores[currentTab];

      if (!matches || matches.length === 0) {
        container.innerHTML = "<p>No live matches at the moment.</p>";
        return;
      }

      const start = (currentPage - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      const currentMatches = matches.slice(start, end);
      const leagueGroups = groupByLeague(currentMatches);

      for (const [leagueName, groupMatches] of Object.entries(leagueGroups)) {
        const leagueHeader = document.createElement("div");
        leagueHeader.className = "league-header";
        leagueHeader.textContent = leagueName;
        container.appendChild(leagueHeader);

        groupMatches.forEach(match => {
          const home = match.teams?.home?.name || "";
          const away = match.teams?.away?.name || "";
          const scoreHome = match.goals?.home ?? 0;
          const scoreAway = match.goals?.away ?? 0;
          const time = match.fixture?.status?.elapsed ?? "--";
          const status = match.fixture?.status?.short ?? "LIVE";

          const card = document.createElement('div');
          card.className = 'match-card';
          card.innerHTML = `
            <div class="teams">${home} vs ${away}</div>
            <div class="score">${scoreHome} - ${scoreAway}</div>
            <div class="status">${status} (${time}') </div>
          `;
          container.appendChild(card);
        });
      }
    }

    function renderPagination() {
      const matches = allScores[currentTab];
      const totalPages = Math.ceil(matches.length / itemsPerPage);
      const pagination = document.getElementById("pagination");
      pagination.innerHTML = "";

      if (totalPages <= 1) return;

      const prevBtn = document.createElement("button");
      prevBtn.textContent = "Previous";
      prevBtn.disabled = currentPage === 1;
      prevBtn.onclick = () => {
        currentPage--;
        renderMatches();
        renderPagination();
      };

      const nextBtn = document.createElement("button");
      nextBtn.textContent = "Next";
      nextBtn.disabled = currentPage === totalPages;
      nextBtn.onclick = () => {
        currentPage++;
        renderMatches();
        renderPagination();
      };

      pagination.appendChild(prevBtn);
      pagination.appendChild(document.createTextNode(` Page ${currentPage} of ${totalPages} `));
      pagination.appendChild(nextBtn);
    }

    fetchAllScores();
    setInterval(fetchAllScores, 60000); // auto refresh every 60 seconds
  </script>
    <!-- SweetAlert Toast Notification -->
  <?php include 'partials/sweetalert.php'; ?>
</body>
</html>
