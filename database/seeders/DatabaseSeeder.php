<?php

namespace Database\Seeders;

use App\Models\FamilyRelation;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::insert([
            [
                'name' => 'Moheb Azer',
                'email' => 'admin@admin.com',
                'is_admin' => '1',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'test',
                'email' => 'test@admin.com',
                'is_admin' => '0',
                'password' => Hash::make('12345678'),
            ]
        ]);
        
        Member::insert([
            'name' => 'test',
            'dob' => '1990-01-01',
            'gender' => 'm',
            'phone1' => '01234567890',
            'whatsapp' => '01234567890',
            'image' => '01JRD739Z8D47CPR8VP0YRKXCC.png',
            'hasLogin' => '2',
            'user_id' => '2',
        ]);
        FamilyRelation::insert([
            ['name' => 'Father'],
            ['name' => 'Mother'],
            ['name' => 'Brother'],
            ['name' => 'Sister'],
            ['name' => 'Husband'],
            ['name' => 'Wife'],
            ['name' => 'Son'],
            ['name' => 'Daughter'],
            ['name' => 'Friend'],
            ['name' => 'Other'],
        ]);
    }
}
