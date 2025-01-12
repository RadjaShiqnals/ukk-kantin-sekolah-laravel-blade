<x-layout title="Admin Dashboard">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
        
        <div class="mb-4 flex justify-between items-center">
            <button type="button" onclick="openCreateModal()" class="bg-green-500 text-white px-4 py-2 rounded">
                Create User
            </button>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Logout</button>
            </form>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <div class="flex items-center">
                <select id="per-page" class="border rounded px-2 py-1 mr-2">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries per page</span>
            </div>
            <div class="flex items-center">
                <input type="text" id="search" class="border rounded px-2 py-1 mr-2" placeholder="Search...">
                <button onclick="performSearch()" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Email</th>
                        <th class="px-4 py-2 border">Role</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody id="users-table">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-2 border">{{ $user->name }}</td>
                        <td class="px-4 py-2 border">{{ $user->email }}</td>
                        <td class="px-4 py-2 border">{{ $user->role }}</td>
                        <td class="px-4 py-2 border">
                            <button onclick="openEditModal({{ $user->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                            <button onclick="deleteUser({{ $user->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="pagination" class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto">
        <div class="bg-white p-4 max-w-lg mx-auto my-8 rounded shadow-lg relative">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Create User</h2>
            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="userId">
                <div class="mb-4">
                    <label>Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" class="border rounded px-2 py-1 w-full">
                    <span class="text-sm text-gray-500">Required, maximum 255 characters</span>
                </div>
                <div class="mb-4">
                    <label>Username <span class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" class="border rounded px-2 py-1 w-full">
                    <span class="text-sm text-gray-500">Required, 5-20 characters, letters, numbers, dashes and underscores only</span>
                </div>
                <div class="mb-4">
                    <label>Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="border rounded px-2 py-1 w-full">
                    <span class="text-sm text-gray-500">Required, valid email format</span>
                </div>
                <div class="mb-4">
                    <label>Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" class="border rounded px-2 py-1 w-full">
                    <span class="text-sm text-gray-500">Required, minimum 8 characters</span>
                </div>
                <div class="mb-4">
                    <label>Role <span class="text-red-500">*</span></label>
                    <select id="role" name="role" class="border rounded px-2 py-1 w-full" onchange="toggleFields()">
                        <option value="siswa">Siswa</option>
                        <option value="admin_stan">Admin Stan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Conditional fields -->
                <div id="siswaFields" class="hidden">
                    <div class="mb-4">
                        <label>Foto</label>
                        <div class="flex items-center space-x-4">
                            <input type="file" id="foto" name="foto" class="border rounded px-2 py-1" accept="image/*" onchange="previewImage(this)">
                            <img id="fotoPreview" src="{{ asset('user/picture/default-profile.png') }}" alt="Preview" class="w-20 h-20 object-cover rounded">
                        </div>
                        <span class="text-sm text-gray-500">Optional, JPG/JPEG/PNG, max 2MB</span>
                    </div>
                    <div class="mb-4">
                        <label>Alamat <span class="text-red-500">*</span></label>
                        <input type="text" id="alamat" name="alamat" class="border rounded px-2 py-1 w-full">
                        <span class="text-sm text-gray-500">Required for students</span>
                    </div>
                    <div class="mb-4">
                        <label>Telp <span class="text-red-500">*</span></label>
                        <input type="text" id="telp_siswa" name="telp_siswa" class="border rounded px-2 py-1 w-full" placeholder="6281234567890">
                        <span class="text-sm text-gray-500">Required, format: 62 followed by 9-15 digits (e.g., 6281234567890)</span>
                    </div>
                </div>

                <div id="stanFields" class="hidden">
                    <div class="mb-4">
                        <label>Nama Stan <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_stan" name="nama_stan" class="border rounded px-2 py-1 w-full">
                        <span class="text-sm text-gray-500">Required for stan admin</span>
                    </div>
                    <div class="mb-4">
                        <label>Nama Pemilik <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_pemilik" name="nama_pemilik" class="border rounded px-2 py-1 w-full">
                        <span class="text-sm text-gray-500">Required for stan admin</span>
                    </div>
                    <div class="mb-4">
                        <label>Telp <span class="text-red-500">*</span></label>
                        <input type="text" id="telp_stan" name="telp_stan" class="border rounded px-2 py-1 w-full" placeholder="6281234567890">
                        <span class="text-sm text-gray-500">Required, format: 62 followed by 9-15 digits (e.g., 6281234567890)</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logout form handler
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        this.submit();
                    }
                });
            }

            // User form handler
            const userForm = document.getElementById('userForm');
            if (userForm) {
                userForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const userId = document.getElementById('userId').value;
                    const formData = new FormData(this);
                    const role = formData.get('role');
                    
                    // Remove fields that aren't relevant to the selected role
                    if (role === 'siswa') {
                        formData.delete('nama_stan');
                        formData.delete('nama_pemilik');
                        formData.delete('telp_stan');
                        // Rename siswa telp field
                        const telpSiswa = formData.get('telp_siswa');
                        formData.set('telp', telpSiswa);
                        formData.delete('telp_siswa');
                    } else if (role === 'admin_stan') {
                        formData.delete('foto');
                        formData.delete('alamat');
                        formData.delete('telp_siswa');
                        // Rename stan telp field
                        const telpStan = formData.get('telp_stan');
                        formData.set('telp', telpStan);
                        formData.delete('telp_stan');
                    } else {
                        // For admin role, remove all role-specific fields
                        formData.delete('foto');
                        formData.delete('alamat');
                        formData.delete('telp_siswa');
                        formData.delete('nama_stan');
                        formData.delete('nama_pemilik');
                        formData.delete('telp_stan');
                        formData.delete('telp');
                    }
                    
                    const url = userId 
                        ? `/admin/users/${userId}`
                        : '/admin/users';
                    
                    const method = userId ? 'PUT' : 'POST';
                    
                    fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            closeModal();
                            loadUsers();
                        } else {
                            alert(data.message || 'An error occurred');
                        }
                    })
                    .catch(error => {
                        if (error.errors) {
                            // Handle validation errors
                            const messages = Object.values(error.errors).flat().join('\n');
                            alert('Validation errors:\n' + messages);
                        } else {
                            alert(error.message || 'An error occurred');
                        }
                    });
                });
            }

            // Per page handler
            const perPageSelect = document.getElementById('per-page');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    performSearch();
                });
            }
        });

        // Functions that don't need DOM loaded
        function toggleFields() {
            const role = document.getElementById('role').value;
            const siswaFields = document.getElementById('siswaFields');
            const stanFields = document.getElementById('stanFields');
            
            // Hide/show appropriate sections
            siswaFields.style.display = role === 'siswa' ? 'block' : 'none';
            stanFields.style.display = role === 'admin_stan' ? 'block' : 'none';
            
            // Clear fields that are hidden
            if (role !== 'siswa') {
                siswaFields.querySelectorAll('input').forEach(input => input.value = '');
                document.getElementById('fotoPreview').src = "{{ asset('user/picture/default-profile.png') }}";
            }
            if (role !== 'admin_stan') {
                stanFields.querySelectorAll('input').forEach(input => input.value = '');
            }
        }

        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Create User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('userModal').classList.remove('hidden');
            toggleFields();
        }

        function openEditModal(userId) {
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = userId;
            document.getElementById('userModal').classList.remove('hidden');

            // Fetch user data
            fetch(`/admin/users/${userId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                // Fill form with user data
                document.getElementById('name').value = data.name;
                document.getElementById('username').value = data.username;
                document.getElementById('email').value = data.email;
                document.getElementById('role').value = data.role;
                
                // Handle role-specific fields
                if (data.role === 'siswa' && data.siswa) {
                    document.getElementById('alamat').value = data.siswa.alamat || '';
                    document.getElementById('telp_siswa').value = data.siswa.telp || '';
                    if (data.siswa.foto) {
                        document.getElementById('fotoPreview').src = '/storage/' + data.siswa.foto;
                    }
                } else if (data.role === 'admin_stan' && data.stan) {
                    document.getElementById('nama_stan').value = data.stan.nama_stan || '';
                    document.getElementById('nama_pemilik').value = data.stan.nama_pemilik || '';
                    document.getElementById('telp_stan').value = data.stan.telp || '';
                }
                
                toggleFields();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading user data');
                closeModal();
            });
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function performSearch() {
            const search = document.getElementById('search').value;
            const perPage = document.getElementById('per-page').value;
            loadUsers(1, perPage, search);
        }

        function loadUsers(page = 1, perPage = 10, search = '') {
            fetch(`/admin/dashboard?page=${page}&per_page=${perPage}&search=${search}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.users) {
                    document.getElementById('users-table').innerHTML = data.users;
                }
                if (data.pagination) {
                    document.getElementById('pagination').innerHTML = data.pagination;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading users. Please refresh the page.');
            });
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        loadUsers();
                    } else {
                        alert(data.message || 'Error deleting user');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting user');
                });
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('fotoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "{{ asset('user/picture/default-profile.png') }}";
            }
        }
    </script>
</x-layout>