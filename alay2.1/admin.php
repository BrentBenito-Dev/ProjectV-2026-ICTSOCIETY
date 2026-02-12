<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

$rate_prices = [
    "Acoustic Live Band" => 89,
    "Acoustic Sundo" => 49,
    "Harana Accompaniment" => 19,
    "Live Karaoke Jam" => 15
];

// --- HANDLERS ---
if(isset($_POST['update_ticket_id'])) {
    $old_id = $_POST['old_id'];
    $new_id = $_POST['new_id'];
    $conn->query("UPDATE orders SET OrderID='$new_id' WHERE OrderID='$old_id'");
    header("Location: admin.php"); exit();
}

if(isset($_GET['complete_id'])) {
    $oid = $_GET['complete_id'];
    $conn->query("UPDATE orders SET Status='COMPLETED' WHERE OrderID='$oid'");
    header("Location: admin.php"); exit();
}

if(isset($_GET['void_id'])) {
    $oid = $_GET['void_id'];
    $conn->query("UPDATE orders SET Status='Voided' WHERE OrderID='$oid'");
    header("Location: admin.php"); exit();
}

if(isset($_POST['approve_request'])) {
    $oid = $_POST['order_id'];
    $rtype = $_POST['rate_type'];
    $setlist = $_POST['setlist_type']; 
    $price = $rate_prices[$rtype] ?? 0;
    $conn->query("UPDATE orders SET Status='Approved', Rate_Type='$rtype', Price='$price', Setlist_Type='$setlist' WHERE OrderID='$oid'");
    header("Location: admin.php"); exit();
}

// --- FILTER LOGIC ---
$set_filter = $_GET['filter_set'] ?? 'All';
$filter_query = ($set_filter != 'All') ? " AND Setlist_Type = '$set_filter'" : "";

// --- REPORT CALCULATIONS ---
$rev_res = $conn->query("SELECT SUM(Price) as total FROM orders WHERE Status IN ('Approved', 'COMPLETED', 'Voided')");
$total_revenue = $rev_res->fetch_assoc()['total'] ?? 0;

$status_counts = ['Pending' => 0, 'Approved' => 0, 'COMPLETED' => 0, 'Voided' => 0];
$status_res = $conn->query("SELECT Status, COUNT(*) as count FROM orders GROUP BY Status");
while($s = $status_res->fetch_assoc()) { $status_counts[$s['Status']] = $s['count']; }

$all_active = $conn->query("SELECT * FROM orders WHERE Status NOT IN ('COMPLETED', 'Voided') $filter_query ORDER BY OrderID ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Musikilig</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen p-8" style="background: radial-gradient(circle at top, #FFB8E8 0%, #660F24 55%, #280013 100%); color:#FFE9F2;">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold italic" style="color:#FFB8E8;">Event Management</h1>
            <div class="flex gap-2">
                <a href="report.php" target="_blank" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-xs font-bold border border-pink-500/30">AUDIT REPORT</a>
            </div>
        </div>

        <div class="mb-6 flex items-center gap-4 bg-black/20 p-4 rounded-2xl border border-pink-500/10">
            <span class="text-xs font-bold uppercase tracking-widest text-pink-400">Filter Queue:</span>
            <div class="flex gap-2">
                <?php foreach(['All', '1st Set', '2nd Set', '3rd Set', 'Final Set'] as $set): ?>
                    <a href="?filter_set=<?php echo $set; ?>" 
                       class="px-3 py-1 rounded-full text-[10px] font-bold transition-all <?php echo ($set_filter == $set) ? 'bg-pink-500 text-white' : 'bg-white/5 text-pink-300 hover:bg-white/10'; ?>">
                        <?php echo $set; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-6 rounded-3xl bg-black/30 border border-pink-500/20">
                <p class="text-[10px] uppercase font-bold tracking-widest text-pink-400">Total Gross</p>
                <p class="text-4xl font-black">₱<?php echo number_format($total_revenue, 2); ?></p>
            </div>
            <div class="p-6 rounded-3xl bg-black/30 border border-pink-500/20 col-span-2">
                <p class="text-[10px] uppercase font-bold tracking-widest text-pink-400 mb-2">Statuses</p>
                <div class="flex gap-4">
                    <span class="bg-yellow-500/20 p-2 rounded text-xs">Pending: <?php echo $status_counts['Pending']; ?></span>
                    <span class="bg-blue-500/20 p-2 rounded text-xs">Approved: <?php echo $status_counts['Approved']; ?></span>
                    <span class="bg-green-500/20 p-2 rounded text-xs">Done: <?php echo $status_counts['COMPLETED']; ?></span>
                    <span class="bg-red-500/20 p-2 rounded text-xs">Void: <?php echo $status_counts['Voided']; ?></span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl overflow-hidden shadow-2xl bg-black/20 border border-pink-500/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-pink-500 text-white text-[10px] tracking-widest uppercase">
                    <tr>
                        <th class="p-5">Ticket ID</th>
                        <th class="p-5">Details</th>
                        <th class="p-5">Validation (Rate & Set)</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-900/40">
                    <?php if($all_active->num_rows == 0): ?>
                        <tr><td colspan="4" class="p-10 text-center text-pink-300/50 italic">No requests found in this set.</td></tr>
                    <?php endif; ?>
                    <?php while($row = $all_active->fetch_assoc()): ?>
                    <tr class="hover:bg-white/5">
                        <td class="p-5">
                            <form method="POST" class="flex gap-2 group">
                                <input type="hidden" name="old_id" value="<?php echo $row['OrderID']; ?>">
                                <input type="number" name="new_id" value="<?php echo $row['OrderID']; ?>" class="bg-transparent border-b border-pink-500/30 w-24 font-bold text-rose-500 outline-none">
                                <button type="submit" name="update_ticket_id" class="text-[8px] bg-pink-500 px-1 rounded opacity-0 group-hover:opacity-100">FIX</button>
                            </form>
                        </td>
                        <td class="p-5">
                            <div class="font-bold text-white"><?php echo $row['Customer_Name']; ?></div>
                            <div class="text-xs text-pink-300 italic">"<?php echo $row['Song_Name']; ?>"</div>
                        </td>
                        <td class="p-5">
                            <?php if($row['Status'] == 'Pending'): ?>
                                <form method="POST" class="flex flex-col gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $row['OrderID']; ?>">
                                    <select name="rate_type" class="bg-black text-[10px] p-1 rounded border border-pink-900">
                                        <?php foreach($rate_prices as $name => $price): ?>
                                            <option value="<?php echo $name; ?>"><?php echo "$name (₱$price)"; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="setlist_type" class="bg-zinc-800 text-[10px] p-1 rounded border border-zinc-700">
                                        <option value="1st Set" <?php if($row['Setlist_Type'] == '1st Set') echo 'selected'; ?>>1st Set</option>
                                        <option value="2nd Set" <?php if($row['Setlist_Type'] == '2nd Set') echo 'selected'; ?>>2nd Set</option>
                                        <option value="3rd Set" <?php if($row['Setlist_Type'] == '3rd Set') echo 'selected'; ?>>3rd Set</option>
                                        <option value="Final Set" <?php if($row['Setlist_Type'] == 'Final Set') echo 'selected'; ?>>Final Set</option>
                                    </select>
                                    <button type="submit" name="approve_request" class="bg-pink-500 text-[9px] px-2 py-1 rounded font-bold">VALIDATE</button>
                                </form>
                            <?php elseif($row['Status'] == 'Approved'): ?>
                                <div class="text-[10px]">
                                    <span class="block text-pink-400 font-bold mb-1"><?php echo $row['Rate_Type']; ?></span>
                                    <form method="POST" class="flex gap-1">
                                        <input type="hidden" name="order_id" value="<?php echo $row['OrderID']; ?>">
                                        <input type="hidden" name="rate_type" value="<?php echo $row['Rate_Type']; ?>">
                                        <select name="setlist_type" onchange="this.form.submit()" class="bg-black/40 text-[9px] p-1 rounded border border-pink-500/30 text-zinc-300">
                                            <option value="1st Set" <?php if($row['Setlist_Type'] == '1st Set') echo 'selected'; ?>>1st Set</option>
                                            <option value="2nd Set" <?php if($row['Setlist_Type'] == '2nd Set') echo 'selected'; ?>>2nd Set</option>
                                            <option value="3rd Set" <?php if($row['Setlist_Type'] == '3rd Set') echo 'selected'; ?>>3rd Set</option>
                                            <option value="Final Set" <?php if($row['Setlist_Type'] == 'Final Set') echo 'selected'; ?>>Final Set</option>
                                        </select>
                                        <input type="hidden" name="approve_request" value="1">
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="text-[10px]">
                                    <span class="block text-pink-400 font-bold"><?php echo $row['Rate_Type']; ?></span>
                                    <span class="block text-zinc-400 italic"><?php echo $row['Setlist_Type']; ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-right space-x-2">
                            <?php if($row['Status'] == 'Approved'): ?>
                                <a href="?complete_id=<?php echo $row['OrderID']; ?>" class="bg-green-600 px-3 py-1 rounded-full text-[9px] font-bold">FINISH</a>
                            <?php endif; ?>
                            <a href="?void_id=<?php echo $row['OrderID']; ?>" class="text-red-400 text-[9px] font-bold">VOID</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
