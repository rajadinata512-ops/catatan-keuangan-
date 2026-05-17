<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow w-96">
    <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-2">Email</label>
            <input
                type="email"
                name="email"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <div class="mb-4">
            <label class="block mb-2">Password</label>
            <input
                type="password"
                name="password"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <button
            type="submit"
            class="w-full bg-blue-500 text-white py-2 rounded"
        >
            Login
        </button>
    </form>
</div>

</body>
</html>