<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->createAdmin();
    }

    private function createAdmin()
    {
        $user = new Admin();
        $user->name = "admin";
        $user->email = "admin@admin.com";
        $user->password = bcrypt("123123123");
        $user->save();
    }
}
