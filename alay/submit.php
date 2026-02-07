<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dedication Sent! ❤️</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
</head>
<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['first_name'] . " " . $_POST['last_name'];
    $song = $_POST['song_name'];
    $cat  = $_POST['order_category'];
    $to   = $_POST['target_person'];

    // We explicitly set 'Pending' so the queue query finds it
    $sql = "INSERT INTO orders (Customer_Name, Song_Name, Target_Person, Order_Category, Status) 
            VALUES ('$name', '$song', '$to', '$cat', 'Pending')";

    if ($conn->query($sql)) {
        
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<body class="bg-rose-50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-full max-w-lg text-center border-b-8 border-rose-200">
        <div class="mb-6">
            <span class="text-6xl animate-bounce inline-block">💌</span>
        </div>
        
        <h1 class="text-4xl font-bold text-rose-600 mb-4" style="font-family: 'Dancing Script', cursive;">
            Seal with a Kiss!
        </h1>
        
        <p class="text-zinc-600 mb-8 leading-relaxed">
            Your musical love note for <span class="font-bold text-rose-500"><?php echo htmlspecialchars($_GET['target'] ?? 'your Valentine'); ?></span> has been added to the queue.
        </p>

        <div class="space-y-3">
            <a href="queue.php" class="block w-full bg-rose-500 text-white font-bold py-4 rounded-2xl hover:bg-rose-600 transition-all shadow-lg">
                View the Queue 🎵
            </a>
            <a href="index.php" class="block w-full text-rose-400 font-medium py-2 hover:text-rose-600 transition-all text-sm">
                Send another one?
            </a>
        </div>
        
        <p class="mt-8 text-[10px] text-zinc-300 uppercase tracking-[0.2em]">MusicOrders ID: #<?php echo rand(1000, 9999); ?></p>
    </div>

</body>
</html>