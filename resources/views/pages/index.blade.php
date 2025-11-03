<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SMK 7 Pekanbaru</title>
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <!-- Hero Section with Carousel -->
    <section class="relative h-[300px] md:h-[400px] lg:h-[500px] mb-8">
        <div class="relative h-full">
            <img src="assets/image/halaman.png" alt="Hero image" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 lg:p-8 bg-gradient-to-t from-black/70 to-transparent">
                <div class="max-w-6xl mx-auto">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-2">Welcome to SMK 7 Pekanbaru</h1>
                    <p class="text-sm md:text-base text-white/90">Discover your potential with us</p>
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-6xl mx-auto px-4">
        <!-- News & Events Section -->
        <div class="flex flex-col lg:flex-row gap-8 mb-12">
            <!-- News Section -->
            <div class="lg:w-2/3">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">NEWS</h2>
                    <a href="#" class="text-blue-600 hover:underline text-sm">All News</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- News Card -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <img src="https://placehold.co/400x300?font=source-sans-pro&text=Placeholder%20Example%20Bruh" alt="News image" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold mb-2 line-clamp-2">Prestasi Siswa dalam Kompetisi Robotik Nasional</h3>
                            <p class="text-sm text-gray-600 mb-2">12 Oktober 2023</p>
                            <p class="text-sm text-gray-500 line-clamp-3">Tim robotik SMK 7 Pekanbaru berhasil meraih juara pertama dalam kompetisi...</p>
                        </div>
                    </div>
                    <!-- Repeat news cards -->
                </div>
            </div>

            <!-- Events Section -->
            <div class="lg:w-1/3">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">EVENTS</h2>
                    <a href="#" class="text-blue-600 hover:underline text-sm">All Events</a>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-4">
                        <!-- Event Item -->
                        <div class="border-b border-gray-100 pb-4">
                            <div class="flex gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">15</div>
                                    <div class="text-sm text-gray-500">OCT</div>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Open House 2023</h3>
                                    <p class="text-sm text-gray-500">Aula SMK 7 Pekanbaru</p>
                                </div>
                            </div>
                        </div>
                        <!-- Repeat event items -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Discover Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">DISCOVER</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="#" class="group relative overflow-hidden rounded-lg aspect-[4/3]">
                    <img src="https://placehold.co/890?font=source-sans-pro&text=Placeholder%20Example%20Bruh" alt="Discover" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent group-hover:from-black/80 transition-all">
                        <div class="absolute bottom-0 p-4">
                            <h3 class="text-white font-semibold">Fasilitas Sekolah</h3>
                        </div>
                    </div>
                </a>
                <!-- Repeat discover items -->
            </div>
        </section>

        <!-- Study Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">STUDYING AT SMK 7 PKU</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="#" class="group relative overflow-hidden rounded-lg aspect-[4/3]">
                    <img src="https://placehold.co/890?font=source-sans-pro&text=Placeholder%20Example%20Bruh" alt="Study" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent group-hover:from-black/80 transition-all">
                        <div class="absolute bottom-0 p-4">
                            <h3 class="text-white font-semibold">Jurusan RPL</h3>
                        </div>
                    </div>
                </a>
                <!-- Repeat study items -->
            </div>
        </section>
    </main>
</body>
</html>
<zero-md src="https://raw.githubusercontent.com/Xynocode/xynocode.github.io/master/devtest.md"></zero-md>