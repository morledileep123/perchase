<?php

use Illuminate\Database\Seeder;
use App\vendor;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VendorTableSeeder::class);
    }
}
