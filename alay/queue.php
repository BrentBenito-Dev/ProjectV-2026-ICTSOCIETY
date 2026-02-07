<?php
// 1. Establish Database Connection
$conn = new mysqli("localhost", "root", "", "alaykanta");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Fetch only Pending requests, oldest first (First-in, First-out)
$sql = "SELECT Customer_Name, Song_Name, Target_Person, Order_Category, Timestamp 
        FROM orders 
        WHERE Status = 'Pending' 
        ORDER BY Timestamp ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alay kanta live queue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="15">
</head>
<body class="bg-zinc-950 text-zinc-300 min-h-screen p-8">

    <div class="max-w-2xl mx-auto">
        <header class="flex justify-between items-end mb-10 border-b border-rose-900/30 pb-6">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Alay Kanta</h1>
                <p class="text-rose-500 text-xs font-bold tracking-[0.3em] mt-1">LIVE SUBMISSIONS</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-zinc-500 bg-zinc-900 px-3 py-1 rounded-full border border-zinc-800">
                    Active Requests: <?php echo $result->num_rows; ?>
                </span>
            </div>
        </header>

        <div class="space-y-4">
            <?php 
            if ($result->num_rows > 0): 
                while($row = $result->fetch_assoc()): 
                    // Logic to determine icon based on category
                    $icon = "🎵";
                    if($row['Order_Category'] == 'Acoustic') $icon = "🎸";
                    if($row['Order_Category'] == 'Pop') $icon = "🍭";
                    if($row['Order_Category'] == 'Classic') $icon = "🍷";
            ?>
                <div class="group relative flex items-center justify-between p-6 bg-zinc-900/50 border border-zinc-800 rounded-3xl transition-all duration-300 hover:border-rose-500/50 hover:bg-zinc-900 hover:shadow-[0_0_30px_rgba(244,63,94,0.15)] hover:-translate-y-1">
                    
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-zinc-800 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-500">
                            <?php echo $icon; ?>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white group-hover:text-rose-400 transition-colors">
                                <?php echo $row['Song_Name']; ?>
                            </h3>
                            <p class="text-sm text-zinc-500">
                                Dedicated to <span class="text-zinc-300 font-semibold"><?php echo $row['Target_Person']; ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] text-zinc-600 uppercase font-bold tracking-widest group-hover:text-rose-500 transition-colors">
                            <?php echo $row['Order_Category']; ?>
                        </div>
                </div>
            <?php 
                endwhile; 
            else: 
            ?>
                <div class="text-center py-20 border-2 border-dashed border-zinc-800 rounded-3xl">
                    <p class="text-zinc-600 italic">No requests yet. Be the first to send some love!</p>
                </div>
            <?php endif; ?>
        </div>

        <footer class="mt-12 text-center">
            <p class="text-[10px] text-zinc-700 uppercase tracking-widest animate-pulse">
                Queue updates automatically every 15 seconds
            </p>
        </footer>
    </div>

</body>
</html>
<?php $conn->close(); ?>