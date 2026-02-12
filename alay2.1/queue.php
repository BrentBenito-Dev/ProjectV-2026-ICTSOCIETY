<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

$sql = "SELECT * FROM orders WHERE Status = 'Approved' 
        ORDER BY FIELD(Setlist_Type, '1st Set', '2nd Set', '3rd Set', 'Final Set') ASC, Timestamp ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Playlist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="15">
</head>
<body class="bg-zinc-950 text-zinc-300 min-h-screen p-8">
    <div class="max-w-2xl mx-auto">
        <header class="flex justify-between items-end mb-10 border-b border-rose-900/30 pb-6">
            <div>
                <h1 class="text-4xl font-black text-white italic tracking-tighter uppercase">Musikilig</h1>
                <p class="text-rose-500 text-xs font-bold tracking-widest mt-1 uppercase">Live Queue</p>
            </div>
            <span class="text-[10px] bg-zinc-900 px-3 py-1 rounded-full border border-zinc-800">In Queue: <?php echo $result->num_rows; ?></span>
        </header>

        <div class="space-y-4">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="relative group flex items-center justify-between p-6 bg-zinc-900 border border-zinc-800 rounded-3xl transition-all hover:border-rose-500/50 hover:-translate-y-1">
                    
                    <div class="absolute -top-2 left-6 bg-zinc-800 text-[9px] text-rose-400 font-black px-2 py-0.5 rounded border border-rose-900/50 uppercase tracking-tighter">
                        <?php echo htmlspecialchars($row['Setlist_Type']); ?> 
                    </div>

                    <?php if($row['Is_Secret']): ?>
                        <div class="absolute -top-2 -right-2 bg-rose-600 text-[10px] text-white font-bold px-2 py-1 rounded-lg shadow-lg uppercase">Secret Admirer 🕵️</div>
                    <?php endif; ?>
                    
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-zinc-800 rounded-xl flex items-center justify-center text-xl group-hover:bg-rose-600 transition-colors">🎵</div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-rose-400">"<?php echo htmlspecialchars($row['Song_Name']); ?>"</h3>
                            <p class="text-sm text-zinc-500 italic font-medium">Dedicated to: <?php echo htmlspecialchars($row['Target_Person']); ?></p>
                        </div>
                    </div>
                    <div class="text-[24px] text-zinc-700 font-mono font-bold">#<?php echo $row['OrderID']; ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
