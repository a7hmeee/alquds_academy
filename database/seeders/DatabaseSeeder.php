<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء الأدوار والصلاحيات أولاً
        $this->call(RoleAndPermissionSeeder::class);

        // إنشاء المستخدم Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@alquds.com'],
            [
                'name' => 'ahmed alassoud',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->assignRole('super admin');

        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // تحميل بيانات القرآن
        $this->call(QuranSeeder::class);
    }
}
