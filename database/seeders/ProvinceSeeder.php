<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::withHeaders([
            //api key rajaongkir
            'key' => config('services.rajaongkir.key'),
        ])->get('https://api.rajaongkir.com/starter/province');

        $fetch = $response['rajaongkir']['results'];
        foreach ($fetch as $result) {
            Province::create([
                'province_id' => $result['province_id'],
                'name' => $result['province']
            ]);
        }
    }
}