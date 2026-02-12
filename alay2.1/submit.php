<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkID = $conn->query("SELECT MAX(OrderID) as lastID FROM orders");
    $rowID = $checkID->fetch_assoc();
    $nextID = ($rowID['lastID'] < 406002) ? 406002 : $rowID['lastID'] + 1;

    // Auto-Setlist based on hour
    $hour = (int)date("H");
    $setlist = "1st Set";
    if ($hour >= 19 && $hour < 20) $setlist = "2nd Set";
    elseif ($hour >= 20 && $hour < 21) $setlist = "3rd Set";
    elseif ($hour >= 21) $setlist = "Final Set";

    $name = $_POST['first_name'] . " " . $_POST['last_name'];
    $song = $_POST['song_name'];
    $cat  = $_POST['order_category'];
    $to   = $_POST['target_person'];
    $secret = isset($_POST['is_secret']) ? $_POST['is_secret'] : 0;

    $stmt = $conn->prepare("INSERT INTO orders (OrderID, Customer_Name, Song_Name, Target_Person, Order_Category, Is_Secret, Status, Setlist_Type) VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?)");
    $stmt->bind_param("issssis", $nextID, $name, $song, $to, $cat, $secret, $setlist);

    if ($stmt->execute()) {
        $displayTarget = $to;
        $displayTicket = $nextID;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dedication Sent!</title>
    <meta http-equiv="refresh" content="5;url=index.php">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-rose-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-full max-w-lg text-center border-b-8 border-rose-200">
        <div class="mb-6"><span class="text-6xl animate-bounce inline-block">💌</span></div>
        <h1 class="text-4xl font-bold text-rose-600 mb-4 cursive" style="font-family: 'Dancing Script';">Seal with a Kiss!</h1>
        <p class="text-zinc-600 mb-8">Your note for <span class="font-bold text-rose-500"><?php echo htmlspecialchars($displayTarget ?? 'Valentine'); ?></span> is in!</p>
        
        <div class="flex flex-col gap-3">
            <a href="queue.php" class="block w-full bg-rose-500 text-white font-bold py-4 rounded-2xl hover:bg-rose-600 shadow-lg">
                View Queue
            </a>
            <a href="index.php" class="block w-full bg-zinc-100 text-zinc-600 font-bold py-4 rounded-2xl hover:bg-zinc-200 transition-colors">
                Return to Home
            </a>
        </div>

        <p class="text-[10px] text-zinc-300 uppercase mt-6">Ticket ID: #<?php echo $displayTicket; ?></p>
        <p class="text-[9px] text-zinc-400 italic mt-2 text-center">Redirecting you back in 5 seconds...</p>
    </div>
</body>
</html>
