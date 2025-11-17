<x-app-layout>
    <div class="max-w-lg mx-auto mt-12 bg-[#0b1d2a] text-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-[#00c3b3] mb-6">Add New User</h1>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <label class="block mb-2">Name</label>
            <input name="name" class="w-full p-3 bg-[#102635] rounded-lg mb-4" required>

            <label class="block mb-2">Email</label>
            <input type="email" name="email" class="w-full p-3 bg-[#102635] rounded-lg mb-4" required>

            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full p-3 bg-[#102635] rounded-lg mb-4" required>

            <label class="block mb-2">Role</label>
            <select name="role" class="w-full p-3 bg-[#102635] rounded-lg mb-6" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button class="bg-[#00c3b3] text-black px-6 py-3 rounded-lg w-full font-semibold">
                Create User
            </button>
        </form>
    </div>
</x-app-layout>
