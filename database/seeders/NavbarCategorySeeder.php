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

        // 1. Beranda (no subcategories)
        CategoryNavbar::create([
            'display_name' => 'Beranda',
            'idpath' => 'beranda-' . date('YmdHis'),
            'subcategories' => false,
            'path' => '/',
            'order' => 1,
            'is_active' => true
        ]);

        // 2. Profil (with subcategories)
        $profil = CategoryNavbar::create([
            'display_name' => 'Profil',
            'idpath' => 'profil-' . date('YmdHis') . '1',
            'subcategories' => true,
            'path' => null,
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Sejarah Sekolah',
            'idpath' => 'sejarah-' . date('YmdHis'),
            'path' => '/profil/sejarah',
            'order' => 1,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Visi & Misi',
            'idpath' => 'visi-misi-' . date('YmdHis'),
            'path' => '/profil/visi-misi',
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Kepala Sekolah',
            'idpath' => 'kepala-sekolah-' . date('YmdHis'),
            'path' => '/profil/kepala-sekolah',
            'order' => 3,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $profil->id,
            'display_name' => 'Struktur Organisasi',
            'idpath' => 'struktur-' . date('YmdHis'),
            'path' => '/profil/struktur-organisasi',
            'order' => 4,
            'is_active' => true
        ]);

        // 3. Akademik (with subcategories)
        $akademik = CategoryNavbar::create([
            'display_name' => 'Akademik',
            'idpath' => 'akademik-' . date('YmdHis') . '2',
            'subcategories' => true,
            'path' => null,
            'order' => 3,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $akademik->id,
            'display_name' => 'Program Keahlian',
            'idpath' => 'program-keahlian-' . date('YmdHis'),
            'path' => '/akademik/program-keahlian',
            'order' => 1,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $akademik->id,
            'display_name' => 'Kurikulum',
            'idpath' => 'kurikulum-' . date('YmdHis'),
            'path' => '/akademik/kurikulum',
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $akademik->id,
            'display_name' => 'Kalender Akademik',
            'idpath' => 'kalender-' . date('YmdHis'),
            'path' => '/akademik/kalender',
            'order' => 3,
            'is_active' => true
        ]);

        // 4. Kegiatan (with subcategories)
        $kegiatan = CategoryNavbar::create([
            'display_name' => 'Kegiatan',
            'idpath' => 'kegiatan-' . date('YmdHis') . '3',
            'subcategories' => true,
            'path' => null,
            'order' => 4,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $kegiatan->id,
            'display_name' => 'Ekstrakurikuler',
            'idpath' => 'ekstrakurikuler-' . date('YmdHis'),
            'path' => '/kegiatan/ekstrakurikuler',
            'order' => 1,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $kegiatan->id,
            'display_name' => 'Berita & Pengumuman',
            'idpath' => 'berita-' . date('YmdHis'),
            'path' => '/kegiatan/berita',
            'order' => 2,
            'is_active' => true
        ]);

        SubcategoryNavbar::create([
            'parent_category_id' => $kegiatan->id,
            'display_name' => 'Galeri Foto',
            'idpath' => 'galeri-' . date('YmdHis'),
            'path' => '/kegiatan/galeri',
            'order' => 3,
            'is_active' => true
        ]);

        // 5. PPDB (no subcategories)
        CategoryNavbar::create([
            'display_name' => 'PPDB',
            'idpath' => 'ppdb-' . date('YmdHis'),
            'subcategories' => false,
            'path' => '/ppdb',
            'order' => 5,
            'is_active' => true
        ]);

        $this->command->info('Navbar categories seeded successfully!');
    }
}
