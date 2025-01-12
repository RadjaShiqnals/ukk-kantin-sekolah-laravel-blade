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