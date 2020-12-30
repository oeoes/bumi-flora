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
        $types = [
            ['Tunai'],
            ['BCA', 'Mandiri', 'BRI', 'BNI'],
            ['BCA', 'Mandiri', 'BRI', 'BNI'],
            ['OVO', 'GoPay', 'LinkAja', 'ShopeePay'],
            ['Kredit']
        ];
        $methods = ['Cash', 'Debit', 'Transfer', 'E-wallet', 'Kartu Kredit'];
        foreach($methods as $key => $method) {
            $mt = PaymentMethod::create(['method_name' => $method]);
            foreach ($types[$key] as $type) {
                PaymentType::create(['payment_method_id' => $mt->id, 'type_name' => $type]);
            }
            
        }        
    }
}
