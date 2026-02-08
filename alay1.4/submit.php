<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate Ticket ID in range 406002 - 407000
    $checkID = $conn->query("SELECT MAX(OrderID) as lastID FROM orders");
    $rowID = $checkID->fetch_assoc();
    $nextID = ($rowID['lastID'] < 406002) ? 406002 : $rowID['lastID'] + 1;

    if($nextID > 407000) die("All slots for this event are filled!");

    $name = $_POST['first_name'] . " " . $_POST['last_name'];
    $song = $_POST['song_name'];
    $cat  = $_POST['order_category'];
    $to   = $_POST['target_person'];
    $secret = $_POST['is_secret'];

    $stmt = $conn->prepare("INSERT INTO orders (OrderID, Customer_Name, Song_Name, Target_Person, Order_Category, Is_Secret, Status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("issssi", $nextID, $name, $song, $to, $cat, $secret);

    if ($stmt->execute()) {
        $displayTarget = $to;
        $displayTicket = $nextID;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dedication Sent! ❤️</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-rose-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-full max-w-lg text-center border-b-8 border-rose-200">
        <div class="mb-6"><span class="text-6xl animate-bounce inline-block">💌</span></div>
        <h1 class="text-4xl font-bold text-rose-600 mb-4 cursive" style="font-family: 'Dancing Script';">Seal with a Kiss!</h1>
        <p class="text-zinc-600 mb-8 leading-relaxed">Your musical love note for <span class="font-bold text-rose-500"><?php echo htmlspecialchars($displayTarget ?? 'your Valentine'); ?></span> has been added!</p>
        <div class="space-y-3">
            <a href="queue.php" class="block w-full bg-rose-500 text-white font-bold py-4 rounded-2xl hover:bg-rose-600 shadow-lg">View the Queue 🎵</a>
            <a href="index.php" class="block w-full text-rose-400 font-medium py-2 hover:text-rose-600 text-sm">Send another one?</a>
        </div>
        <p class="mt-8 text-[10px] text-zinc-300 uppercase tracking-widest">Ticket ID: #<?php echo $displayTicket; ?></p>
    </div>
</body>
</html>