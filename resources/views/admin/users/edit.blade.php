<!-- resources/views/admin/users/edit.blade.php -->

<x-app-layout>

    <!-- TOP LEFT BACK BUTTON -->
    <div class="max-w-lg mx-auto mt-10 mb-4">
        <a
            href="{{ route('admin.users.index') }}"
            class="inline-flex items-center bg-[#00c3b3] text-black px-5 py-2 rounded-lg font-semibold hover:opacity-90 transition"
        >
            <svg
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Manage Users
        </a>
    </div>

    <!-- EDIT CARD -->
    <div class="max-w-lg mx-auto bg-[#0b1d2a] text-white p-8 rounded-xl shadow-lg">

        <h1 class="text-3xl font-bold text-[#00c3b3] mb-6">
            Edit User
        </h1>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <label class="block mb-2">Name</label>
            <input
                name="name"
                class="w-full p-3 bg-[#102635] rounded-lg mb-4"
                value="{{ $user->name }}"
                required
            >

            <label class="block mb-2">Email</label>
            <input
                name="email"
                type="email"
                class="w-full p-3 bg-[#102635] rounded-lg mb-4"
                value="{{ $user->email }}"
                required
            >

            <label class="block mb-2">Role</label>
            <select
                name="role"
                class="w-full p-3 bg-[#102635] rounded-lg mb-6"
                required
            >
                <option value="user" @if($user->role=="user") selected @endif>User</option>
                <option value="admin" @if($user->role=="admin") selected @endif>Admin</option>
            </select>

            <button
                class="bg-[#00c3b3] text-black px-6 py-3 rounded-lg w-full font-semibold hover:opacity-90 transition"
            >
                Update User
            </button>
        </form>
    </div>

</x-app-layout>
