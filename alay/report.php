<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");
$all_data = $conn->query("SELECT * FROM orders ORDER BY Timestamp DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Alay kanta Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-zinc-900 p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center border-b-2 border-zinc-100 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold">Master Orders Report</h1>
                <p class="text-zinc-500 text-sm">Generated on: <?php echo date('F j, Y, g:i a'); ?></p>
            </div>
            <button onclick="window.print()" class="bg-zinc-900 text-white px-6 py-2 rounded-lg font-bold">Print PDF</button>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="p-6 bg-rose-50 rounded-2xl border border-rose-100">
                <p class="text-xs uppercase font-bold text-rose-400">Total Requests</p>
                <p class="text-3xl font-bold"><?php echo $all_data->num_rows; ?></p>
            </div>
            </div>

        <table class="w-full text-sm text-left border-collapse border border-zinc-200">
            <thead>
                <tr class="bg-zinc-50 border-b border-zinc-200">
                    <th class="p-4 border-r border-zinc-200">Date/Time</th>
                    <th class="p-4 border-r border-zinc-200">Customer</th>
                    <th class="p-4 border-r border-zinc-200">Dedicated To</th>
                    <th class="p-4 border-r border-zinc-200">Song Request</th>
                    <th class="p-4 border-r border-zinc-200">Category</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $all_data->fetch_assoc()): ?>
                <tr class="border-b border-zinc-100">
                    <td class="p-4 border-r border-zinc-200"><?php echo $row['Timestamp']; ?></td>
                    <td class="p-4 border-r border-zinc-200 font-bold"><?php echo $row['Customer_Name']; ?></td>
                    <td class="p-5 border-r border-zinc-200 text-rose-600"><?php echo $row['Target_Person']; ?></td>
                    <td class="p-4 border-r border-zinc-200 italic"><?php echo $row['Song_Name']; ?></td>
                    <td class="p-4 border-r border-zinc-200"><?php echo $row['Order_Category']; ?></td>
                    <td class="p-4 font-bold <?php echo $row['Status'] == 'Finished' ? 'text-green-500' : 'text-amber-500'; ?>">
                        <?php echo $row['Status']; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>