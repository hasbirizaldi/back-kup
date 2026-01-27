<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('artikels')->insert([
            [
                'user_id' => 6,
                'category' => 'Kesehatan',
                'title' => 'Pentingnya Menjaga Kesehatan Tulang',
                'slug' => Str::slug('Pentingnya Menjaga Kesehatan Tulang'),
                'excerpt' => 'Kesehatan tulang sangat penting untuk menunjang aktivitas sehari-hari.',
                'published_at' => now(),
                'image' => 'tulang.jpg',
                'image_alt' => 'Ilustrasi tulang manusia',
                'meta_title' => 'Menjaga Kesehatan Tulang',
                'meta_description' => 'Tips menjaga kesehatan tulang agar tetap kuat.',
                'meta_keywords' => 'kesehatan tulang, ortopedi',
                'canonical_url' => 'https://example.com/artikel/kesehatan-tulang',
                'content' => '<p>Tulang berfungsi sebagai penopang tubuh dan pelindung organ vital.</p>',
                'reading_time' => 5,
                'noindex' => 0,
                'status' => 1,
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'category' => 'Teknologi',
                'title' => 'Perkembangan AI di Dunia Pendidikan',
                'slug' => Str::slug('Perkembangan AI di Dunia Pendidikan'),
                'excerpt' => 'AI membawa perubahan besar dalam sistem pendidikan modern.',
                'published_at' => now(),
                'image' => 'ai.jpg',
                'image_alt' => 'AI dalam pendidikan',
                'meta_title' => 'AI Pendidikan',
                'meta_description' => 'Pengaruh AI terhadap dunia pendidikan.',
                'meta_keywords' => 'AI, pendidikan',
                'canonical_url' => 'https://example.com/artikel/ai-pendidikan',
                'content' => '<p>AI membantu proses belajar menjadi lebih personal dan efektif.</p>',
                'reading_time' => 6,
                'noindex' => 0,
                'status' => 1,
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'category' => 'Kesehatan',
                'title' => 'Cara Mencegah Nyeri Punggung Sejak Dini',
                'slug' => Str::slug('Cara Mencegah Nyeri Punggung Sejak Dini'),
                'excerpt' => 'Nyeri punggung dapat dicegah dengan kebiasaan hidup sehat.',
                'published_at' => now(),
                'image' => 'nyeri-punggung.jpg',
                'image_alt' => 'Ilustrasi nyeri punggung',
                'meta_title' => 'Mencegah Nyeri Punggung',
                'meta_description' => 'Langkah sederhana mencegah nyeri punggung.',
                'meta_keywords' => 'nyeri punggung, kesehatan tulang',
                'canonical_url' => 'https://example.com/artikel/nyeri-punggung',
                'content' => '<p>Postur tubuh yang baik dapat mencegah nyeri punggung.</p>',
                'reading_time' => 4,
                'noindex' => 0,
                'status' => 1,
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'category' => 'Teknologi',
                'title' => 'Manfaat Cloud Computing untuk Bisnis',
                'slug' => Str::slug('Manfaat Cloud Computing untuk Bisnis'),
                'excerpt' => 'Cloud computing membantu bisnis menjadi lebih efisien.',
                'published_at' => now(),
                'image' => 'cloud.jpg',
                'image_alt' => 'Ilustrasi cloud computing',
                'meta_title' => 'Cloud Computing Bisnis',
                'meta_description' => 'Keuntungan cloud computing bagi perusahaan.',
                'meta_keywords' => 'cloud computing, teknologi bisnis',
                'canonical_url' => 'https://example.com/artikel/cloud-computing',
                'content' => '<p>Cloud computing memungkinkan akses data kapan saja dan di mana saja.</p>',
                'reading_time' => 5,
                'noindex' => 0,
                'status' => 1,
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'category' => 'Lifestyle',
                'title' => 'Pola Hidup Sehat untuk Mahasiswa',
                'slug' => Str::slug('Pola Hidup Sehat untuk Mahasiswa'),
                'excerpt' => 'Mahasiswa perlu menjaga keseimbangan antara belajar dan kesehatan.',
                'published_at' => now(),
                'image' => 'hidup-sehat.jpg',
                'image_alt' => 'Gaya hidup sehat',
                'meta_title' => 'Hidup Sehat Mahasiswa',
                'meta_description' => 'Tips hidup sehat bagi mahasiswa.',
                'meta_keywords' => 'hidup sehat, mahasiswa',
                'canonical_url' => 'https://example.com/artikel/hidup-sehat-mahasiswa',
                'content' => '<p>Pola makan teratur dan olahraga ringan sangat dianjurkan.</p>',
                'reading_time' => 3,
                'noindex' => 0,
                'status' => 1,
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
