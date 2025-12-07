<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryNavbar;
use App\Models\SubcategoryNavbar;
use Illuminate\Support\Str;

class NavbarCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (delete instead of truncate due to foreign key)
        SubcategoryNavbar::query()->delete();
        CategoryNavbar::query()->delete();

        // Consistent idpath format with UI-created records: YYYY-MM-DD-slug
        $datePrefix = date('Y-m-d');
        $makeId = function (string $name) use ($datePrefix) {
            return $datePrefix . '-' . Str::slug($name);
        };

        // 1. Beranda (no subcategories)
        CategoryNavbar::create([
            'display_name' => 'Beranda',
            'idpath' => $makeId('Beranda'),
            'subcategories' => false,
            'path' => '/',
            'order' => 1,
            'is_active' => true
        ]);

        // 2. Profil (with subcategories)
        $profil = CategoryNavbar::create([
            'display_name' => 'Profil',
            'idpath' => $makeId('Profil'),
            'subcategories' => true,
            'path' => null,
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Sejarah Sekolah',
            'idpath' => $makeId('Sejarah Sekolah'),
            'path' => '/profil/sejarah',
            'order' => 1,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Visi & Misi',
            'idpath' => $makeId('Visi & Misi'),
            'path' => '/profil/visi-misi',
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Kepala Sekolah',
            'idpath' => $makeId('Kepala Sekolah'),
            'path' => '/profil/kepala-sekolah',
            'order' => 3,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Struktur Organisasi',
            'idpath' => $makeId('Struktur Organisasi'),
            'path' => '/profil/struktur-organisasi',
            'order' => 4,
            'is_active' => true
        ]);

        // 3. Akademik (with subcategories)
        $akademik = CategoryNavbar::create([
            'display_name' => 'Akademik',
            'idpath' => $makeId('Akademik'),
            'subcategories' => true,
            'path' => null,
            'order' => 3,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $akademik->id,
            'display_name' => 'Program Keahlian',
            'idpath' => $makeId('Program Keahlian'),
            'path' => '/akademik/program-keahlian',
            'order' => 1,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $akademik->id,
            'display_name' => 'Kurikulum',
            'idpath' => $makeId('Kurikulum'),
            'path' => '/akademik/kurikulum',
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $akademik->id,
            'display_name' => 'Kalender Akademik',
            'idpath' => $makeId('Kalender Akademik'),
            'path' => '/akademik/kalender',
            'order' => 3,
            'is_active' => true
        ]);

        // 4. Kegiatan (with subcategories)
        $kegiatan = CategoryNavbar::create([
            'display_name' => 'Kegiatan',
            'idpath' => $makeId('Kegiatan'),
            'subcategories' => true,
            'path' => null,
            'order' => 4,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $kegiatan->id,
            'display_name' => 'Ekstrakurikuler',
            'idpath' => $makeId('Ekstrakurikuler'),
            'path' => '/kegiatan/ekstrakurikuler',
            'order' => 1,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $kegiatan->id,
            'display_name' => 'Berita & Pengumuman',
            'idpath' => $makeId('Berita & Pengumuman'),
            'path' => '/kegiatan/berita',
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $kegiatan->id,
            'display_name' => 'Galeri Foto',
            'idpath' => $makeId('Galeri Foto'),
            'path' => '/kegiatan/galeri',
            'order' => 3,
            'is_active' => true
        ]);

        // 5. Artikel (no subcategories)
        CategoryNavbar::create([
            'display_name' => 'Artikel',
            'idpath' => $makeId('Artikel'),
            'subcategories' => false,
            'path' => '/articles',
            'order' => 5,
            'is_active' => true
        ]);

        $this->command->info('Navbar categories seeded successfully!');
    }
}