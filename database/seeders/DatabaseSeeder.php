<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN

        User::create([
            'name' => 'Admin HilmiDev',
            'email' => 'admin@hilmidev.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // CLIENT

        User::create([
            'name' => 'Client Demo',
            'email' => 'client@hilmidev.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // CATEGORY

        $laravel = Category::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'icon' => '🚀',
            'description' => 'Source code Laravel premium',
            'is_active' => true,
        ]);

        $flutter = Category::create([
            'name' => 'Flutter',
            'slug' => 'flutter',
            'icon' => '📱',
            'description' => 'Aplikasi Flutter',
            'is_active' => true,
        ]);

        // PRODUCTS

        Product::create([
            'category_id' => $laravel->id,
            'name' => 'POS Laravel Premium',
            'slug' => 'pos-laravel-premium',
            'short_description' => 'Aplikasi kasir modern Laravel.',
            'description' => 'Source code POS lengkap dengan dashboard admin.',
            'price' => 299000,
            'technology' => 'Laravel 12',
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $laravel->id,
            'name' => 'Inventory Management',
            'slug' => 'inventory-management',
            'short_description' => 'Manajemen stok barang.',
            'description' => 'Sistem inventory Laravel modern.',
            'price' => 399000,
            'technology' => 'Laravel 12',
            'is_active' => true,
            'is_featured' => true,
        ]);

        // SERVICES

        Service::create([
            'title' => 'Jasa Website Company Profile',
            'slug' => 'jasa-company-profile',
            'icon' => '💻',
            'short_description' => 'Website bisnis profesional.',
            'description' => 'Pembuatan website company profile modern.',
            'starting_price' => 2500000,
            'features' => [
                'Desain Premium',
                'Mobile First',
                'SEO Friendly',
                'Admin Panel',
            ],
            'is_active' => true,
            'is_featured' => true,
        ]);

        Service::create([
            'title' => 'Jasa Aplikasi Laravel Custom',
            'slug' => 'aplikasi-laravel-custom',
            'icon' => '⚙️',
            'short_description' => 'Dashboard dan sistem informasi.',
            'description' => 'Pembuatan aplikasi Laravel custom.',
            'starting_price' => 5000000,
            'features' => [
                'Custom Fitur',
                'Dashboard Admin',
                'Database Design',
                'Support',
            ],
            'is_active' => true,
            'is_featured' => true,
        ]);
        // TESTIMONIAL

        Testimonial::create([
            'name' => 'Budi Santoso',
            'position' => 'CEO',
            'company' => 'PT Digital Nusantara',
            'review' => 'Pelayanan sangat profesional dan cepat. Website terlihat premium, responsive, dan sesuai kebutuhan bisnis.',
            'rating' => 5,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Testimonial::create([
            'name' => 'Siti Rahma',
            'position' => 'Owner',
            'company' => 'Toko Online Jaya',
            'review' => 'Source code rapi, mudah dikembangkan, dan support HilmiDev sangat membantu.',
            'rating' => 5,
            'is_active' => true,
            'is_featured' => true,
        ]);

        // PORTFOLIO

        Portfolio::create([
            'title' => 'Sistem Informasi Sekolah',
            'slug' => 'sistem-informasi-sekolah',
            'client_name' => 'SMK Digital',
            'description' => 'Website sekolah modern berbasis Laravel.',
            'is_featured' => true,
            'is_active' => true,
        ]);

        Portfolio::create([
            'title' => 'Dashboard Inventory',
            'slug' => 'dashboard-inventory',
            'client_name' => 'PT Maju Bersama',
            'description' => 'Dashboard stok dan laporan realtime.',
            'is_featured' => true,
            'is_active' => true,
        ]);

        // BLOG

        Post::create([
            'user_id' => 1,
            'title' => 'Jasa Website Laravel Profesional',
            'slug' => 'jasa-website-laravel-profesional',
            'excerpt' => 'HilmiDev menyediakan jasa website Laravel premium.',
            'content' => '
                <h2>Mengapa Laravel?</h2>
                <p>Laravel adalah framework PHP modern yang powerful dan scalable.</p>

                <h2>Keunggulan HilmiDev</h2>
                <p>Kami fokus pada performa, keamanan, dan desain premium.</p>
            ',
            'meta_title' => 'Jasa Website Laravel Profesional',
            'meta_description' => 'Jasa website Laravel profesional dan modern.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Post::create([
            'user_id' => 1,
            'title' => 'Cara Memilih Source Code Laravel Berkualitas',
            'slug' => 'cara-memilih-source-code-laravel',
            'excerpt' => 'Tips memilih source code Laravel sebelum membeli.',
            'content' => '
                <h2>Perhatikan Struktur Project</h2>
                <p>Pastikan source code menggunakan standar Laravel terbaru.</p>
            ',
            'meta_title' => 'Cara Memilih Source Code Laravel',
            'meta_description' => 'Tips memilih source code Laravel berkualitas.',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}