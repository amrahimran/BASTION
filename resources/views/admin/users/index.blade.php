<!-- resources/views/admin/users/index.blade.php -->

<x-app-layout>
    <div class="max-w-6xl mx-auto mt-12 bg-[#0b1d2a] text-white p-8 rounded-xl shadow-lg">

        <h1 class="text-3xl font-bold text-[#00c3b3] mb-6">Manage Users</h1>

        <a href="{{ route('admin.users.create') }}"
           class="bg-[#00c3b3] text-black font-semibold px-6 py-3 rounded-lg hover:bg-[#00a79e] transition">
            + Add New User
        </a>

        <table class="w-full mt-6 bg-[#102635] rounded-xl overflow-hidden">
            <thead class="bg-[#0f2a33] text-[#00c3b3]">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Role</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>

            <tbody class="text-gray-300">
                @foreach($users as $user)
                <tr class="border-b border-gray-700">
                    <td class="p-4">{{ $user->name }}</td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4 capitalize">{{ $user->role }}</td>
                    <td class="p-4 flex gap-3">

                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="text-yellow-400 hover:text-yellow-300 font-semibold">
                           Edit
                        </a>

                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 font-semibold"
                                    onclick="return confirm('Delete this user?')">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
