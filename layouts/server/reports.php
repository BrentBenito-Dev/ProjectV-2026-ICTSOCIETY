<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4 text-[#ee6983]">Reports</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="p-4 bg-gray-100 rounded">
            <h2 class="text-lg font-semibold">Total Sales</h2>
            <p class="text-2xl font-bold text-[#ee6983]">PHP <?php echo htmlspecialchars($totalSales); ?></p>
            <p class="text-sm text-gray-600">(Total orders × 15)</p>
        </div>
        <div class="p-4 bg-gray-100 rounded">
            <h2 class="text-lg font-semibold">Average Tries per Confession</h2>
            <p class="text-2xl font-bold text-[#ee6983]"><?php echo htmlspecialchars($avgTries); ?></p>
            <p class="text-sm text-gray-600">(Based on confessions data)</p>
        </div>
    </div>
    
    <h2 class="text-xl font-bold mb-4 text-[#ee6983]">Confession Orders</h2>
    
    <!-- Create Form -->
    <div class="mb-4 p-4 bg-gray-50 rounded">
        <h3 class="text-lg font-semibold mb-2">Add New Order</h3>
        <form id="createForm" class="flex space-x-2">
            <input type="hidden" name="action" value="create">
            <input type="text" name="ticket_number" placeholder="Ticket Number" required class="flex-1 p-2 border border-gray-300 rounded">
            <input type="text" name="section" placeholder="Section" required class="flex-1 p-2 border border-gray-300 rounded">
            <button type="submit" class="bg-[#ee6983] text-white px-4 py-2 rounded hover:bg-[#ee6983]/80">Add</button>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-[#ee6983] text-white">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">ORDER_ID</th>
                    <th class="border border-gray-300 px-4 py-2">Ticket_Number</th>
                    <th class="border border-gray-300 px-4 py-2">Section</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="ordersTable">
                <?php foreach ($orders as $order): ?>
                <tr data-id="<?php echo htmlspecialchars($order['ORDER_ID']); ?>">
                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($order['ORDER_ID']); ?></td>
                    <td class="border border-gray-300 px-4 py-2 ticket-number"><?php echo htmlspecialchars($order['Ticket_Number']); ?></td>
                    <td class="border border-gray-300 px-4 py-2 section"><?php echo htmlspecialchars($order['Section']); ?></td>
                    <td class="border border-gray-300 px-4 py-2 space-x-2">
                        <button class="edit-btn bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</button>
                        <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
            <h3 class="text-lg font-bold mb-4 text-[#ee6983]">Edit Order</h3>
            <form id="editForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="order_id" id="editOrderId">
                <label class="block mb-2 text-gray-700">Ticket Number:</label>
                <input type="text" name="ticket_number" id="editTicketNumber" required class="block w-full mb-4 p-2 border border-gray-300 rounded">
                <label class="block mb-2 text-gray-700">Section:</label>
                <input type="text" name="section" id="editSection" required class="block w-full mb-4 p-2 border border-gray-300 rounded">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-[#ee6983] text-white px-4 py-2 rounded hover:bg-[#ee6983]/80">Update</button>
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
