<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberLearningSeeder extends Seeder
{
    public function run(): void
    {
        $monthly = MembershipPlan::updateOrCreate(
            ['slug' => 'member-1-bulan'],
            [
                'name' => 'Member 1 Bulan',
                'description' => 'Akses seluruh video pembelajaran selama satu bulan.',
                'price' => 299000,
                'duration_days' => 30,
                'features' => [
                    'Akses seluruh kelas coding',
                    'Download source code materi',
                    'Progress belajar otomatis',
                    'Support konsultasi',
                ],
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        MembershipPlan::updateOrCreate(
            ['slug' => 'member-3-bulan'],
            [
                'name' => 'Member 3 Bulan',
                'description' => 'Paket intensif untuk menyelesaikan project website.',
                'price' => 699000,
                'duration_days' => 90,
                'features' => [
                    'Akses seluruh kelas coding',
                    'Download source code materi',
                    'Progress belajar otomatis',
                    'Support konsultasi',
                    'Review project',
                ],
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        MembershipPlan::updateOrCreate(
            ['slug' => 'member-lifetime'],
            [
                'name' => 'Member Lifetime',
                'description' => 'Akses kelas dan update materi tanpa batas waktu.',
                'price' => 1499000,
                'duration_days' => null,
                'features' => [
                    'Akses selamanya',
                    'Semua kelas baru',
                    'Download source code',
                    'Support prioritas',
                ],
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        $course = Course::updateOrCreate(
            ['slug' => 'laravel-dari-nol'],
            [
                'title' => 'Membuat Aplikasi Laravel dari Nol',
                'subtitle' => 'Belajar Laravel, Tailwind CSS, database, CRUD, autentikasi, dan deploy.',
                'description' => 'Kelas terstruktur untuk pemula yang ingin mampu membuat aplikasi website profesional dari awal sampai online.',
                'level' => 'pemula',
                'technology' => 'Laravel, PHP, MySQL, Tailwind CSS',
                'estimated_minutes' => 420,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ]
        );

        $modules = [
            [
                'title' => 'Persiapan Project',
                'sort_order' => 1,
                'lessons' => [
                    'Pengenalan Alur Belajar',
                    'Instalasi Laravel dan Composer',
                    'Mengenal Struktur Folder Laravel',
                ],
            ],
            [
                'title' => 'Database dan CRUD',
                'sort_order' => 2,
                'lessons' => [
                    'Konfigurasi Database MySQL',
                    'Membuat Migration dan Model',
                    'Membuat CRUD Data',
                ],
            ],
            [
                'title' => 'UI dan Deployment',
                'sort_order' => 3,
                'lessons' => [
                    'Membuat Dashboard dengan Tailwind',
                    'Validasi dan SweetAlert',
                    'Deploy Aplikasi ke Server',
                ],
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = $course->modules()->updateOrCreate(
                ['title' => $moduleData['title']],
                [
                    'description' => null,
                    'sort_order' => $moduleData['sort_order'],
                ]
            );

            foreach ($moduleData['lessons'] as $index => $title) {
                $module->lessons()->updateOrCreate(
                    ['slug' => Str::slug($course->slug . '-' . $title)],
                    [
                        'title' => $title,
                        'description' => 'Tambahkan video pembelajaran dan file materi melalui admin panel.',
                        'video_type' => 'upload',
                        'duration_minutes' => 30,
                        'is_preview' => $moduleData['sort_order'] === 1 && $index === 0,
                        'is_published' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }

        $user = User::firstOrNew(['email' => 'member@hilmidev.test']);

        $user->forceFill([
            'name' => 'Member Demo',
            'password' => Hash::make('password'),
            'role' => 'client',
        ])->save();

        $user->memberships()->updateOrCreate(
            ['membership_plan_id' => $monthly->id],
            [
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
                'activated_at' => now(),
                'notes' => 'Akun demo hasil seeder.',
            ]
        );
    }
}
