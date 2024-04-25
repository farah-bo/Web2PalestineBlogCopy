<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Handle sending message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message']) && isset($_POST['sender_id'])) {
    $message = $_POST['message'];
    $sender_id = $_POST['sender_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, message) VALUES (:sender_id, :message)");
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Retrieve messages
try {
    $stmt = $pdo->prepare("SELECT * FROM messages ORDER BY created_at DESC");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <style>
         body { 
            background-image: url("back.jpeg") ;
            background-size: cover;
            background-attachment: fixed;
         }
        /* Custom CSS styles */
        .message-container {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Chat Space</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile: <?php echo $_SESSION['nom']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home.html">Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="write.php">Return</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Chat Messages</div>
                <div class="card-body">
                    <?php foreach ($messages as $message) : ?>
                        <div class="message-container">
                            <strong>User <?php echo $message['sender_id']; ?>:</strong>
                            <?php echo $message['message']; ?>
                            <span style="color: gray; font-size: 0.8em;">
                                (<?php echo getTimeDifference($message['created_at']); ?> ago)
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">Send Message</div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="sender_id" value="<?php echo $_SESSION['nom']; ?>">
                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="Type your message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
function getTimeDifference($created_at) {
    date_default_timezone_set('Africa/Tunis');
    $currentTime = time();
    $messageTime = strtotime($created_at);
    $difference = $currentTime - $messageTime;

    if ($difference < 60) {
        return $difference . " seconds";
    } elseif ($difference < 3600) {
        return round($difference / 60) . " minutes";
    } elseif ($difference < 86400) {
        return round($difference / 3600) . " hours";
    } else {
        return round($difference / 86400) . " days";
    }
}
?>
