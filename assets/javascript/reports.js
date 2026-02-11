 async function loadOrders() {
        const response = await fetch('?action=get_orders');
        const orders = await response.json();
        const tbody = document.getElementById('ordersTable');
        tbody.innerHTML = orders.map(order => `
            <tr data-id="${order.ORDER_ID}">
                <td class="border border-gray-300 px-4 py-2">${order.ORDER_ID}</td>
                <td class="border border-gray-300 px-4 py-2 ticket-number">${order.Ticket_Number}</td>
                <td class="border border-gray-300 px-4 py-2 section">${order.Section}</td>
                <td class="border border-gray-300 px-4 py-2 space-x-2">
                    <button class="edit-btn bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</button>
                    <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                </td>
            </tr>
        `).join('');
        attachEventListeners();
    }

    function attachEventListeners() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.dataset.id;
                const ticket = row.querySelector('.ticket-number').textContent;
                const section = row.querySelector('.section').textContent;
                document.getElementById('editOrderId').value = id;
                document.getElementById('editTicketNumber').value = ticket;
                document.getElementById('editSection').value = section;
                document.getElementById('editModal').classList.remove('hidden');
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (confirm('Are you sure you want to delete this order?')) {
                    const id = this.closest('tr').dataset.id;
                    const response = await fetch('', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `action=delete&order_id=${id}`
                    });
                    const result = await response.json();
                    alert(result.message);
                    if (result.type === 'success') loadOrders();
                }
            });
        });
    }

    document.getElementById('createForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const response = await fetch('', {
            method: 'POST',
            body: new URLSearchParams(formData)
        });
        const result = await response.json();
        alert(result.message);
        if (result.type === 'success') {
            this.reset();
            loadOrders();
        }
    });

    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const response = await fetch('', {
            method: 'POST',
            body: new URLSearchParams(formData)
        });
        const result = await response.json();
        alert(result.message);
        if (result.type === 'success') {
            closeEditModal();
            loadOrders();
        }
    });

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Initial load
    loadOrders();