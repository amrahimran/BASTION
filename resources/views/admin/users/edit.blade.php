<!-- resources/views/admin/users/edit.blade.php -->

<x-app-layout>
    <div class="max-w-lg mx-auto mt-12 bg-[#0b1d2a] text-white p-8 rounded-xl shadow-lg">

        <h1 class="text-3xl font-bold text-[#00c3b3] mb-6">Edit User</h1>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <label class="block mb-2">Name</label>
            <input name="name" class="w-full p-3 bg-[#102635] rounded-lg mb-4"
                   value="{{ $user->name }}" required>

            <label class="block mb-2">Email</label>
            <input name="email" type="email" class="w-full p-3 bg-[#102635] rounded-lg mb-4"
                   value="{{ $user->email }}" required>

            <label class="block mb-2">Role</label>
            <select name="role" class="w-full p-3 bg-[#102635] rounded-lg mb-6" required>
                <option value="user" @if($user->role=="user") selected @endif>User</option>
                <option value="admin" @if($user->role=="admin") selected @endif>Admin</option>
            </select>

            <button class="bg-[#00c3b3] text-black px-6 py-3 rounded-lg w-full font-semibold">
                Update User
            </button>
        </form>
    </div>
</x-app-layout>
