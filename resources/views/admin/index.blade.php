<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SMK 7 Pekanbaru</title>
    <x-addons />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-[#002147] text-white px-4 py-3">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">Admin Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm hover:underline">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 px-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-6">Artikel Management</h2>
                <!-- Article management interface will go here -->
            </div>
        </main>
    </div>
</body>
</html>