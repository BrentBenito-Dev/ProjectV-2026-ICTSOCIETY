<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Alay Kanta Rates</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen p-8"
      style="background: radial-gradient(circle at top, #FFB8E8 0%, #660F24 55%, #280013 100%); color:#FFE9F2;">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold italic mb-8"
            style="color:#FFB8E8;">
            Rate Management Dashboard
        </h1>

        <div class="rounded-3xl overflow-hidden shadow-2xl border"
             style="background: linear-gradient(135deg,#280013,#660F24); border-color:rgba(255,216,232,0.15);">
            <table class="w-full text-left text-sm">
                <thead class="uppercase font-black text-[10px] tracking-widest"
                       style="background: linear-gradient(90deg,#FFD8E8,#FF94B2,#F24455); color:#660F24;">
                    <tr>
                        <th class="p-5">Ticket</th>
                        <th class="p-5">Customer &amp; Song</th>
                        <th class="p-5">Select Rate</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-900/40">
                    <?php while($row = $all_active->fetch_assoc()): ?>
                    <tr class="transition-colors"
                        style="background: linear-gradient(90deg,rgba(40,0,19,0.8),rgba(102,15,36,0.9));"
                        onmouseover="this.style.background='linear-gradient(90deg,rgba(255,148,178,0.14),rgba(242,68,85,0.22))'"
                        onmouseout="this.style.background='linear-gradient(90deg,rgba(40,0,19,0.8),rgba(102,15,36,0.9))'">
                        <td class="p-5 font-bold"
                            style="color:#F24455;">
                            #<?php echo $row['OrderID']; ?>
                        </td>
                        <td class="p-5">
                            <div class="font-bold"
                                 style="color:#FFE9F2;">
                                <?php echo $row['Customer_Name']; ?>
                            </div>
                            <div class="italic text-xs"
                                 style="color:#FFB8E8;">
                                "<?php echo $row['Song_Name']; ?>"
                            </div>
                        </td>
                        <td class="p-5">
                            <?php if($row['Status'] == 'Pending'): ?>
                                <form method="POST" class="flex flex-wrap gap-2 items-center">
                                    <input type="hidden" name="order_id" value="<?php echo $row['OrderID']; ?>">
                                    <select name="rate_type"
                                            class="text-xs rounded px-2 py-1 outline-none"
                                            style="background:#280013; border:1px solid #F24455; color:#FFD8E8;">
                                        <?php foreach($rate_prices as $name => $val): ?>
                                            <option value="<?php echo $name; ?>"
                                                    style="background:#280013; color:#FFD8E8;">
                                                <?php echo "$name (₱$val)"; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="approve_request"
                                            class="text-[10px] font-bold uppercase px-3 py-1 rounded"
                                            style="background:linear-gradient(90deg,#FFB8E8,#F24455); color:#660F24;">
                                        Validate
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="flex flex-col">
                                    <span class="font-bold uppercase text-[10px]"
                                          style="color:#FFB8E8;">
                                        <?php echo $row['Rate_Type']; ?>
                                    </span>
                                    <span class="text-[10px]"
                                          style="color:#FFD8E8;">
                                        Paid: ₱<?php echo number_format($row['Price'], 2); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-right space-x-2">
                            <?php if($row['Status'] == 'Approved'): ?>
                                <a href="?complete_id=<?php echo $row['OrderID']; ?>"
                                   class="text-[10px] font-bold uppercase px-3 py-1 rounded"
                                   style="background:linear-gradient(90deg,#F24455,#E5203A); color:#FFD8E8;">
                                    Finish
                                </a>
                            <?php endif; ?>
                            <a href="?void_id=<?php echo $row['OrderID']; ?>"
                               onclick="return confirm('Void? Note: No refund policy.')"
                               class="text-[10px] font-bold uppercase"
                               style="color:#FF9482;">
                               Void
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
