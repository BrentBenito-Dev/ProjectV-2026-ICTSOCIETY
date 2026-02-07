<?php
$conn = new mysqli("localhost", "root", "", "alaykanta");

// 1. Calculate Grand Total Revenue (Gross - Includes Voided due to No Refund policy)
// Total includes all Price values assigned during validation
$total_query = $conn->query("SELECT SUM(Price) as total FROM orders WHERE Status != 'Pending'");
$grand_total = $total_query->fetch_assoc()['total'] ?? 0;

// 2. Fetch all data for the audit trail starting from ticket #406002
$all_data = $conn->query("SELECT * FROM orders ORDER BY OrderID ASC");

// 3. Define the official rates for category calculation
$categories = [
    'Acoustic Request' => 89.00,
    'Acoustic Sundo'   => 49.00,
    'Harana Accomp.'   => 19.00,
    'Karaoke Jam'      => 15.00
];

// 4. Helper function for status counts
function getStatusCount($conn, $status) {
    $res = $conn->query("SELECT COUNT(*) as count FROM orders WHERE Status = '$status'");
    return $res->fetch_assoc()['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Musikilig - Ticket #406002 to #407000</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background-color: white; padding: 0; }
            .report-container { box-shadow: none; border: none; width: 100%; max-width: 100%; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-zinc-100 min-h-screen p-4 md:p-10 font-sans">

    <div class="report-container bg-white max-w-6xl mx-auto p-8 shadow-2xl rounded-sm border-t-8 border-rose-600">
        
        <div class="no-print flex justify-end mb-6 space-x-4">
            <button onclick="window.print()" class="bg-rose-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-rose-700 transition-all shadow-md flex items-center gap-2">
                <span>⎙</span> Print or Save as PDF
            </button>
        </div>

        <header class="flex flex-col md:flex-row justify-between items-start md:items-end border-b-2 border-zinc-100 pb-8 mb-10">
            <div>
                <h1 class="text-4xl font-black uppercase italic tracking-tighter text-zinc-900">Alay Kanta Audit</h1>
                <p class="text-zinc-500 font-bold text-sm tracking-widest mt-1">Official Event Records: Ticket #406002 - #407000</p>
            </div>
            <div class="text-left md:text-right mt-6 md:mt-0">
                <p class="text-zinc-400 text-[10px] font-bold uppercase tracking-widest">Total Revenue (Gross)</p>
                <p class="text-5xl font-black text-rose-600">₱<?php echo number_format($grand_total, 2); ?></p>
            </div>
        </header>

        <div class="mb-10">
            <h2 class="text-xs font-black text-zinc-400 uppercase mb-4 tracking-[0.2em]">Financial Breakdown by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach($categories as $name => $price): 
                    $res = $conn->query("SELECT COUNT(*) as qty, SUM(Price) as subtotal FROM orders WHERE Rate_Type = '$name' AND Status != 'Pending'");
                    $data = $res->fetch_assoc();
                ?>
                    <div class="p-4 bg-zinc-50 border border-zinc-100 rounded-xl">
                        <p class="text-[9px] font-bold text-zinc-500 uppercase truncate"><?php echo $name; ?></p>
                        <p class="text-xl font-black text-zinc-800">₱<?php echo number_format($data['subtotal'] ?? 0, 2); ?></p>
                        <p class="text-[9px] text-rose-500 font-bold mt-1">Total Qty: <?php echo $data['qty']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-12">
            <h2 class="text-xs font-black text-zinc-400 uppercase mb-4 tracking-[0.2em]">Ticket Status Summary</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 border-l-4 border-amber-400 bg-amber-50 rounded-r-xl">
                    <p class="text-[9px] font-bold text-amber-600 uppercase">Pending</p>
                    <p class="text-2xl font-black text-amber-700"><?php echo getStatusCount($conn, 'Pending'); ?></p>
                </div>
                <div class="p-4 border-l-4 border-blue-400 bg-blue-50 rounded-r-xl">
                    <p class="text-[9px] font-bold text-blue-600 uppercase">Approved (Live)</p>
                    <p class="text-2xl font-black text-blue-700"><?php echo getStatusCount($conn, 'Approved'); ?></p>
                </div>
                <div class="p-4 border-l-4 border-green-400 bg-green-50 rounded-r-xl">
                    <p class="text-[9px] font-bold text-green-600 uppercase">Completed</p>
                    <p class="text-2xl font-black text-green-700"><?php echo getStatusCount($conn, 'Finished'); ?></p>
                </div>
                <div class="p-4 border-l-4 border-red-400 bg-red-50 rounded-r-xl">
                    <p class="text-[9px] font-bold text-red-600 uppercase">Voided</p>
                    <p class="text-2xl font-black text-red-700"><?php echo getStatusCount($conn, 'Voided'); ?></p>
                </div>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-800 text-white text-[10px] uppercase font-bold tracking-widest">
                    <th class="p-3 border border-zinc-700">Ticket ID</th>
                    <th class="p-3 border border-zinc-700">Customer</th>
                    <th class="p-3 border border-zinc-700">Song & Target</th>
                    <th class="p-3 border border-zinc-700">Service Rate</th>
                    <th class="p-3 border border-zinc-700">Price</th>
                    <th class="p-3 border border-zinc-700 text-center">Final Status</th>
                </tr>
            </thead>
            <tbody class="text-[11px]">
                <?php while($row = $all_data->fetch_assoc()): ?>
                <tr class="border-b border-zinc-100 hover:bg-zinc-50 transition-colors">
                    <td class="p-3 border border-zinc-100 font-mono font-bold text-rose-600">#<?php echo $row['OrderID']; ?></td>
                    <td class="p-3 border border-zinc-100 font-bold text-zinc-800"><?php echo $row['Customer_Name']; ?></td>
                    <td class="p-3 border border-zinc-100 text-zinc-500 italic">"<?php echo $row['Song_Name']; ?>" for <?php echo $row['Target_Person']; ?></td>
                    <td class="p-3 border border-zinc-100"><?php echo $row['Rate_Type'] ?: '<span class="text-zinc-300">Unassigned</span>'; ?></td>
                    <td class="p-3 border border-zinc-100 font-mono font-bold italic">₱<?php echo number_format($row['Price'], 2); ?></td>
                    <td class="p-3 border border-zinc-100 text-center">
                        <?php 
                            $status = $row['Status'];
                            $badgeStyles = [
                                'Pending'  => 'text-amber-600 border border-amber-200 bg-amber-50',
                                'Approved' => 'text-blue-600 border border-blue-200 bg-blue-50',
                                'Finished' => 'text-green-600 border border-green-200 bg-green-50',
                                'Voided'   => 'text-red-600 border border-red-200 bg-red-50'
                            ];
                            $style = $badgeStyles[$status] ?? 'text-zinc-400 bg-zinc-50';
                            echo "<span class='$style px-2 py-1 rounded text-[8px] font-black uppercase'>$status</span>";
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <footer class="mt-16 pt-8 border-t border-zinc-100 flex justify-between items-center text-[10px] text-zinc-400 uppercase tracking-widest font-bold">
            <p>End of Event Report</p>
            <p>Date Generated: <?php echo date('Y-m-d H:i:s'); ?></p>
            <p>Page 1 of 1</p>
        </footer>
    </div>

</body>
</html>