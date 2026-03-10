<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Service Request Management</h1>
            
            <!-- Filters -->
            <div class="mb-6 flex gap-4">
                <select id="statusFilter" class="border rounded px-4 py-2">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                
                <select id="typeFilter" class="border rounded px-4 py-2">
                    <option value="">All Types</option>
                    <option value="barangay_clearance">Barangay Clearance</option>
                    <option value="certificate_of_indigency">Certificate of Indigency</option>
                    <option value="certificate_of_residency">Certificate of Residency</option>
                    <option value="business_permit">Business Permit</option>
                </select>
                
                <input type="text" id="searchInput" placeholder="Search resident..." class="border rounded px-4 py-2 flex-1">
            </div>

            <!-- Requests Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Resident</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Staff Assigned</th>
                            <th class="px-4 py-2 text-left">Created</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer"></div>
        </div>
    </div>

    <!-- Modal for Request Details -->
    <div id="requestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
            <h2 class="text-xl font-bold mb-4">Request Details</h2>
            <div id="modalContent"></div>
            <div class="mt-6 flex gap-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal for Reassign Staff -->
    <div id="reassignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Reassign Request</h2>
            <select id="staffSelect" class="border rounded px-4 py-2 w-full mb-4">
                <!-- Staff options will be loaded here -->
            </select>
            <div class="flex gap-3">
                <button onclick="confirmReassign()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Confirm</button>
                <button onclick="closeReassignModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let allStaff = [];
        let currentRequestId = null;
        let currentFilters = {
            status: '',
            type: '',
            search: ''
        };

        // Fetch requests with filters and pagination
        async function fetchRequests(page = 1) {
            try {
                const params = new URLSearchParams({
                    page: page,
                    status: currentFilters.status,
                    type: currentFilters.type,
                    search: currentFilters.search
                });

                const response = await fetch(`/api/admin/service-requests?${params}`);
                const data = await response.json();
                
                renderRequests(data.data);
                renderPagination(data);
                currentPage = data.current_page;
            } catch (error) {
                console.error('Error fetching requests:', error);
            }
        }

        // Fetch all staff
        async function fetchStaff() {
            try {
                const response = await fetch('/api/admin/service-requests/staff');
                allStaff = await response.json();
            } catch (error) {
                console.error('Error fetching staff:', error);
            }
        }

        // Render requests table
        function renderRequests(requests) {
            const tbody = document.getElementById('requestsTableBody');
            tbody.innerHTML = '';

            if (requests.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">No requests found</td></tr>';
                return;
            }

            requests.forEach(request => {
                const row = document.createElement('tr');
                row.className = 'border-b hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-4 py-2">#${request.id}</td>
                    <td class="px-4 py-2">${request.resident_name}</td>
                    <td class="px-4 py-2">${formatType(request.type)}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs ${getStatusColor(request.status)}">
                            ${request.status}
                        </span>
                    </td>
                    <td class="px-4 py-2">${request.staff_name || 'Unassigned'}</td>
                    <td class="px-4 py-2">${formatDate(request.created_at)}</td>
                    <td class="px-4 py-2">
                        <button onclick="viewRequest(${request.id})" class="text-blue-500 hover:underline mr-2">View</button>
                        <button onclick="openReassignModal(${request.id})" class="text-purple-500 hover:underline mr-2">Reassign</button>
                        ${request.status !== 'completed' && request.status !== 'cancelled' ? `
                            <button onclick="approveRequest(${request.id})" class="text-green-500 hover:underline mr-2">Approve</button>
                            <button onclick="cancelRequest(${request.id})" class="text-red-500 hover:underline">Cancel</button>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Render pagination
        function renderPagination(data) {
            const container = document.getElementById('paginationContainer');
            container.innerHTML = '';

            if (data.last_page <= 1) return;

            const pagination = document.createElement('div');
            pagination.className = 'flex justify-center items-center gap-2 mt-4';

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.textContent = 'Previous';
            prevBtn.className = `px-4 py-2 rounded ${data.current_page === 1 ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600'}`;
            prevBtn.disabled = data.current_page === 1;
            prevBtn.onclick = () => data.current_page > 1 && fetchRequests(data.current_page - 1);
            pagination.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = `px-4 py-2 rounded ${i === data.current_page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
                pageBtn.onclick = () => fetchRequests(i);
                pagination.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Next';
            nextBtn.className = `px-4 py-2 rounded ${data.current_page === data.last_page ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600'}`;
            nextBtn.disabled = data.current_page === data.last_page;
            nextBtn.onclick = () => data.current_page < data.last_page && fetchRequests(data.current_page + 1);
            pagination.appendChild(nextBtn);

            container.appendChild(pagination);
        }

        // View request details
        async function viewRequest(id) {
            try {
                const response = await fetch(`/api/admin/service-requests/${id}`);
                const request = await response.json();
                
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-3">
                        <p><strong>ID:</strong> #${request.id}</p>
                        <p><strong>Resident:</strong> ${request.resident_name}</p>
                        <p><strong>Email:</strong> ${request.resident_email}</p>
                        <p><strong>Contact:</strong> ${request.resident_contact}</p>
                        <p><strong>Type:</strong> ${formatType(request.type)}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${getStatusColor(request.status)}">${request.status}</span></p>
                        <p><strong>Staff Assigned:</strong> ${request.staff_name || 'Unassigned'}</p>
                        <p><strong>Created:</strong> ${formatDate(request.created_at)}</p>
                        <p><strong>Updated:</strong> ${formatDate(request.updated_at)}</p>
                    </div>
                `;
                
                document.getElementById('requestModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error viewing request:', error);
            }
        }

        // Open reassign modal
        function openReassignModal(id) {
            currentRequestId = id;
            const select = document.getElementById('staffSelect');
            select.innerHTML = '<option value="">Select Staff...</option>';
            
            allStaff.forEach(staff => {
                const option = document.createElement('option');
                option.value = staff.id;
                option.textContent = staff.name;
                select.appendChild(option);
            });
            
            document.getElementById('reassignModal').classList.remove('hidden');
        }

        // Confirm reassign
        async function confirmReassign() {
            const staffId = document.getElementById('staffSelect').value;
            if (!staffId) {
                alert('Please select a staff member');
                return;
            }

            try {
                const response = await fetch(`/api/admin/service-requests/${currentRequestId}/reassign`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ staff_id: staffId })
                });

                if (response.ok) {
                    alert('Request reassigned successfully');
                    closeReassignModal();
                    fetchRequests(currentPage);
                }
            } catch (error) {
                console.error('Error reassigning request:', error);
            }
        }

        // Approve request
        async function approveRequest(id) {
            if (!confirm('Are you sure you want to approve this request?')) return;

            try {
                const response = await fetch(`/api/admin/service-requests/${id}/approve`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('Request approved successfully');
                    fetchRequests(currentPage);
                }
            } catch (error) {
                console.error('Error approving request:', error);
            }
        }

        // Cancel request
        async function cancelRequest(id) {
            if (!confirm('Are you sure you want to cancel this request?')) return;

            try {
                const response = await fetch(`/api/admin/service-requests/${id}/cancel`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('Request cancelled successfully');
                    fetchRequests(currentPage);
                }
            } catch (error) {
                console.error('Error cancelling request:', error);
            }
        }

        // Filter functions
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('typeFilter').addEventListener('change', applyFilters);
        document.getElementById('searchInput').addEventListener('input', applyFilters);

        function applyFilters() {
            currentFilters.status = document.getElementById('statusFilter').value;
            currentFilters.type = document.getElementById('typeFilter').value;
            currentFilters.search = document.getElementById('searchInput').value;
            fetchRequests(1); // Reset to page 1 when filtering
        }

        // Helper functions
        function formatType(type) {
            return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function getStatusColor(status) {
            const colors = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'processing': 'bg-blue-100 text-blue-800',
                'completed': 'bg-green-100 text-green-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }

        function closeModal() {
            document.getElementById('requestModal').classList.add('hidden');
        }

        function closeReassignModal() {
            document.getElementById('reassignModal').classList.add('hidden');
            currentRequestId = null;
        }

        // Initialize
        fetchRequests();
        fetchStaff();
    </script>
</body>
</html>