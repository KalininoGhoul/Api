<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'user_id' => '1',
            'create_at' => '2023-04-15 08:00:00',
            'table_id' => 1,
            'order_status_id' => 1,
            'work_shift_id' => 1,
            'price' => 10,
        ]);
    }
}
