<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => 'Администратор',
            'slug' => 'admin',
        ]);
        DB::table('groups')->insert([
            'name' => 'Официант',
            'slug' => 'waiter',
        ]);
        DB::table('groups')->insert([
            'name' => 'Повар',
            'slug' => 'cook',
        ]);
    }
}
