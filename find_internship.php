<?php
session_start();
require_once "database/config.php";

$radius = $_GET['radius'] ?? 50;

function haversine($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userLat = $_GET['lat'] ?? 0;
$userLon = $_GET['lon'] ?? 0;

$sql = "SELECT id, company_name, internship, address, created_at, lat, lon FROM internship WHERE isApprove = TRUE ORDER BY created_at DESC";
$result = $conn->query($sql);

require_once "navigator.php";
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <div class="card-container">
            <?php while ($row = $result->fetch_assoc()):
                $distance = ($userLat && $userLon) ? haversine($userLat, $userLon, $row['lat'], $row['lon']) : 0;

                if ($distance <= $radius || $radius == 0): ?>
                    <div class="card internship-card" data-lat="<?php echo $row['lat']; ?>" data-lon="<?php echo $row['lon']; ?>">
                        <h3><?php echo htmlspecialchars($row['internship']); ?> –
                            <?php echo htmlspecialchars($row['company_name']); ?>
                        </h3>
                        <p><?php echo htmlspecialchars($row['address']); ?></p>
                        <div class="info">
                            <span class="icon">💼</span>
                            <span>Internship Program</span>
                            <span class="icon">⏰</span>
                            <span>
                                <?php
                                $createdTime = new DateTime($row['created_at']);
                                $now = new DateTime();
                                $interval = $createdTime->diff($now);
                                if ($interval->d > 0) {
                                    echo $interval->d . " days ago";
                                } elseif ($interval->h > 0) {
                                    echo $interval->h . " hours ago";
                                } else {
                                    echo $interval->i . " minutes ago";
                                }
                                ?>
                            </span>
                        </div>
                        <a href="/displaypostinternshippage.php?id=<?php echo $row['id']; ?>"
                            style="text-decoration: none; color: inherit;">
                            <button>View more details</button>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <center>
            <p class="no-internships">No internship programs available.</p>
        </center>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const userLat = position.coords.latitude;
            const userLon = position.coords.longitude;

            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('lat', userLat);
            urlParams.set('lon', userLon);
            window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
</script>