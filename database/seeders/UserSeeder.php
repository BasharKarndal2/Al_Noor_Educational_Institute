<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        User::create([
            'name' => 'مدير المدرسة',
            'email' => 'SchoolManager@com',
            'password' => Hash::make('!@#$%123456!@#$%'),
            'role_id' => $adminRole->id
        ]);
        User::create([
            'name' => 'مدير المدرسة',
            'email' => 'SchoolManager2@com',
            'password' => Hash::make('!@#$%0940146944!@#$%'),
            'role_id' => $adminRole->id
        ]);
        User::create([
            'name' => 'مدير المدرسة',
            'email' => 'MohammdJohar@com',
            'password' => Hash::make('M0h@mm@d02'),
            'role_id' => $adminRole->id
        ]);

        User::create([
            'name' => 'مدير المدرسة',
            'email' => 'HusseinAlameen@com',
            'password' => Hash::make('Hu$$e!n85'),
            'role_id' => $adminRole->id
        ]);
    }
}
