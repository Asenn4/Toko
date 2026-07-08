<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $date = date('Y-m-d');
        $nominals = [100000, 150000, 200000, 250000, 300000];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'tanggal'    => date('Y-m-d', strtotime($date . ' + ' . $i . ' days')),
                'nominal'    => $nominals[array_rand($nominals)],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        $this->db->table('discounts')->insertBatch($data);
    }
}
