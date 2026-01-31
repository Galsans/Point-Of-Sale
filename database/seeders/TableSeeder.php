<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $totalMeja = 25; // jumlah meja

        // for ($i = 1; $i <= $totalMeja; $i++) {

        //     $kodeTable = 'T-' . str_pad($i, 3, '0', STR_PAD_LEFT);

        //     $floor = match (true) {
        //         $i >= 1 && $i <= 5  => 1,
        //         $i >= 6 && $i <= 15 => 2,
        //         default                            => 3,
        //     };

        //     Table::updateOrCreate(
        //         ['kode_table' => $kodeTable],
        //         [
        //             'qr_code' => 'qr-' . Str::uuid() . '.png',
        //             'status'  => 'available',
        //             'floor'   => $floor,
        //         ]
        //     );
        // }
        $totalMeja = 25;
        $qrDir = storage_path('app/public/qrcodes');

        if (!File::exists($qrDir)) {
            File::makeDirectory($qrDir, 0755, true);
        }

        foreach (range(1, $totalMeja) as $i) {
            $kodeTable = 'T-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            $floor = match (true) {
                $i >= 1 && $i <= 5  => 1,
                $i >= 6 && $i <= 15 => 2,
                default             => 3,
            };

            $table = Table::updateOrCreate(
                ['kode_table' => $kodeTable],
                [
                    'status' => 'available',
                    'floor'  => $floor,
                ]
            );

            // $encryptedTable = Crypt::encryptString($table->id);
            // $orderUrl = route('order.menu', $encryptedTable);
            $encryptedTable = Crypt::encryptString($table->id);

            $orderUrl = route('order.menu', [
                'table' => $encryptedTable
            ]);


            // 🔹 Generate SVG sebagai string
            //     $qrSvg = QrCode::format('svg')
            //         ->size(300)
            //         ->margin(2)
            //         ->generate($orderUrl);

            //     // 🔹 Tambahkan teks di bawah QR
            //     $label = "MEJA {$kodeTable}";

            //     $customSvg = str_replace(
            //         '</svg>',
            //         '
            // <text x="50%" y="340"
            //       text-anchor="middle"
            //       font-size="18"
            //       font-family="Arial, sans-serif"
            //       fill="#000">
            //     ' . $label . '
            // </text>
            // </svg>',
            //         $qrSvg
            //     );

            $qrSvg = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($orderUrl);

            $label = "MEJA {$kodeTable}";

            // ✅ Replace height
            $qrSvg = str_replace(
                'height="300"',
                'height="340"',
                $qrSvg
            );

            // ✅ Replace viewBox
            $qrSvg = str_replace(
                'viewBox="0 0 300 300"',
                'viewBox="0 0 300 340"',
                $qrSvg
            );

            // ✅ Inject text
            $customSvg = str_replace(
                '</svg>',
                '
    <text x="150" y="325"
          text-anchor="middle"
          font-size="18"
          font-family="Arial, sans-serif"
          font-weight="bold"
          fill="#000">
        ' . $label . '
    </text>
</svg>',
                $qrSvg
            );

            $fileName = 'table-' . $encryptedTable . '.svg';
            File::put($qrDir . '/' . $fileName, $customSvg);

            $table->update([
                'qr_code' => 'qrcodes/' . $fileName
            ]);
        }
    }
}
