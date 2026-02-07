<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

$rate_prices = [
    'Acoustic Request' => 89.00, //
    'Acoustic Sundo'   => 49.00, //
    'Harana Accomp.'   => 19.00, //
    'Karaoke Jam'      => 15.00  //
];

// Handle Approval
if (isset($_POST['approve_request'])) {
    $id = intval($_POST['order_id']);
    $rate = $_POST['rate_type'];
    $price = $rate_prices[$rate];
    $conn->query("UPDATE orders SET Status = 'Approved', Rate_Type = '$rate', Price = $price WHERE OrderID = $id");
    header("Location: admin.php");
}

// FIXED: Handle Marking as Finished (Removes from Live Queue)
if (isset($_GET['complete_id'])) {
    $id = intval($_GET['complete_id']);
    $conn->query("UPDATE orders SET Status = 'Finished' WHERE OrderID = $id");
    header("Location: admin.php");
}

// Handle Voiding (No Refund Policy)
if (isset($_GET['void_id'])) {
    $id = intval($_GET['void_id']);
    $conn->query("UPDATE orders SET Status = 'Voided' WHERE OrderID = $id");
    header("Location: admin.php");
}

// Fetch Pending and Approved orders
$all_active = $conn->query("SELECT * FROM orders WHERE Status IN ('Pending', 'Approved') ORDER BY OrderID ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Alay Kanta Rates</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-300 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-rose-500 mb-8 italic">Rate Management Dashboard</h1>

        <div class="bg-zinc-900 rounded-3xl border border-zinc-800 overflow-hidden shadow-2xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-800/50 text-rose-400 uppercase font-black text-[10px] tracking-widest">
                    <tr>
                        <th class="p-5">Ticket</th>
                        <th class="p-5">Customer & Song</th>
                        <th class="p-5">Select Rate</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    <?php while($row = $all_active->fetch_assoc()): ?>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="p-5 font-bold text-rose-500">#<?php echo $row['OrderID']; ?></td>
                        <td class="p-5">
                            <div class="text-white font-bold"><?php echo $row['Customer_Name']; ?></div>
                            <div class="text-zinc-500 italic">"<?php echo $row['Song_Name']; ?>"</div>
                        </td>
                        <td class="p-5">
                            <?php if($row['Status'] == 'Pending'): ?>
                                <form method="POST" class="flex gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $row['OrderID']; ?>">
                                    <select name="rate_type" class="bg-zinc-800 border border-zinc-700 rounded px-2 py-1 text-xs outline-none focus:border-rose-500">
                                        <?php foreach($rate_prices as $name => $val): ?>
                                            <option value="<?php echo $name; ?>"><?php echo "$name (₱$val)"; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="approve_request" class="bg-blue-600 px-3 py-1 rounded text-[10px] font-bold uppercase hover:bg-blue-500">Validate</button>
                                </form>
                            <?php else: ?>
                                <div class="flex flex-col">
                                    <span class="text-green-500 font-bold uppercase text-[10px]"><?php echo $row['Rate_Type']; ?></span>
                                    <span class="text-zinc-500 text-[10px]">Paid: ₱<?php echo number_format($row['Price'], 2); ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-right space-x-2">
                            <?php if($row['Status'] == 'Approved'): ?>
                                <a href="?complete_id=<?php echo $row['OrderID']; ?>" class="bg-rose-600 text-white px-3 py-1 rounded text-[10px] font-bold uppercase">Finish</a>
                            <?php endif; ?>
                            <a href="?void_id=<?php echo $row['OrderID']; ?>" onclick="return confirm('Void? Note: No refund policy.')" class="text-zinc-600 hover:text-red-400 text-[10px] uppercase font-bold">Void</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>