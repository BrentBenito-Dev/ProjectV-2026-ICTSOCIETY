<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

// Handle Marking as Finished (Removes from Live Queue)
if (isset($_GET['complete_id'])) {
    $id = intval($_GET['complete_id']);
    $conn->query("UPDATE orders SET Status = 'Finished' WHERE OrderID = $id");
    header("Location: admin.php");
}

// Handle Permanent Deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM orders WHERE OrderID = $id");
    header("Location: admin.php");
}

$pending = $conn->query("SELECT * FROM orders WHERE Status = 'Pending' ORDER BY Timestamp ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alay kanta for admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-300 p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-rose-500 italic">alay kanta admin</h1>
            <a href="report.php" class="bg-zinc-800 hover:bg-zinc-700 text-white px-6 py-2 rounded-xl text-sm font-bold transition-all border border-zinc-700">
                📊 Generate Full Report
            </a>
        </div>

        <div class="bg-zinc-900 rounded-3xl border border-zinc-800 overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-zinc-800/50 text-rose-400 text-xs uppercase tracking-widest">
                    <tr>
                        <th class="p-5">From</th>
                        <th class="p-5">To</th>
                        <th class="p-5">Song</th>
                        <th class="p-5">Category</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    <?php if($pending->num_rows > 0): while($row = $pending->fetch_assoc()): ?>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="p-5 font-medium text-white"><?php echo $row['Customer_Name']; ?></td>
                        <td class="p-5 text-rose-300"><?php echo $row['Target_Person']; ?></td>
                        <td class="p-5 italic text-zinc-400">"<?php echo $row['Song_Name']; ?>"</td>
                        <td class="p-5"><span class="px-2 py-1 bg-zinc-800 rounded text-[10px]"><?php echo $row['Order_Category']; ?></span></td>
                        <td class="p-5 text-right space-x-2">
                            <a href="?complete_id=<?php echo $row['OrderID']; ?>" class="bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg text-xs font-bold">Finish</a>
                            <a href="?delete_id=<?php echo $row['OrderID']; ?>" onclick="return confirm('Delete forever?')" class="text-zinc-600 hover:text-red-400 text-xs">Remove</a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="5" class="p-10 text-center text-zinc-600">No active requests in queue.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>