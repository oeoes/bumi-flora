<?php

use App\Model\MasterData\PaymentMethod;
use App\Model\MasterData\PaymentType;
use Illuminate\Database\Seeder;

class SecondaryDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $method = PaymentMethod::create(['method_name' => 'Cash']);
        PaymentType::create(['payment_method_id' => $method->id, 'type_name' => 'Tunai']);
    }
}
