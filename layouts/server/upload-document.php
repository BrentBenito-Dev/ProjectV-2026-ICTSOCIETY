
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4 text-[#ee6983]">Upload Confessions Excel</h1>
    <form method="POST" enctype="multipart/form-data">
        <label class="block mb-2 text-gray-700">Select Excel File:</label>
        <input type="file" name="excel_file" accept=".xls,.xlsx" required class="block w-full mb-4 p-2 border border-gray-300 rounded">
        <button type="submit" class="bg-[#ee6983] text-white px-4 py-2 rounded hover:bg-[#ee6983]/80">Upload and Populate DB</button>
    </form>
    <?php if ($message): ?>
        <p class="mt-4 text-sm <?php echo $messageType === 'error' ? 'text-red-500' : ($messageType === 'success' ? 'text-green-500' : 'text-blue-500'); ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
</div>
