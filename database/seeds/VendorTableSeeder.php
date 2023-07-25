<?php

use Illuminate\Database\Seeder;
use App\vendor;

class VendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Vendor::class, 50)->create();
    }
}
