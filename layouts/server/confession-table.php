<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-[#fc034e]">Confessions Data</h1>
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-[#fc034e]">
            <thead class="bg-[#fc034e] text-white">
                <tr>
                    <th class="border border-[#fc034e] px-4 py-2 text-left">ConfessionID</th>
                    <th class="border border-[#fc034e] px-4 py-2 text-left">Confess_Name</th>
                    <th class="border border-[#fc034e] px-4 py-2 text-left">Custom_Clue</th>
                    <th class="border border-[#fc034e] px-4 py-2 text-left">Message</th>
                    <th class="border border-[#fc034e] px-4 py-2 text-left">Recipient_Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr class="hover:bg-[#fc034e]/10">
                    <td class="border border-[#fc034e] px-4 py-2"><?php echo htmlspecialchars($row['id']); ?></td>
                    <td class="border border-[#fc034e] px-4 py-2"><?php echo htmlspecialchars($row['Confess_Name']); ?></td>
                    <td class="border border-[#fc034e] px-4 py-2"><?php echo htmlspecialchars($row['Custom_Clue']); ?></td>
                    <td class="border border-[#fc034e] px-4 py-2"><?php echo htmlspecialchars($row['Message']); ?></td>
                    <td class="border border-[#fc034e] px-4 py-2"><?php echo htmlspecialchars($row['Recipient_Name']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>