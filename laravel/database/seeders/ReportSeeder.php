<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Report::insert([
            [
                'report_date' => '2021-11-26',
                'report_content' => "2021年11月26日（金）\n出演者：Yu\n\n人数：100名\nプレート被り：0\nテーブル逃し：0\nバースデーカード逃し：0\nお断り：0\nオーダー：0\n食事提供：0\n\n【本日の目標】\n本日の目標本日の目標本日の目標\n\n【本日の反省】\n本日の反省本日の反省本日の反省"
            ],
            [
                'report_date' => '2021-11-27',
                'report_content' => "2021年11月26日（金）\n出演者：Yu\n\n人数：100名\nプレート被り：0\nテーブル逃し：0\nバースデーカード逃し：0\nお断り：0\nオーダー：0\n食事提供：0\n\n【本日の目標】\n本日の目標本日の目標本日の目標\n\n【本日の反省】\n本日の反省本日の反省本日の反省"

            ],
            [
                'report_date' => '2021-11-28',
                'report_content' => "2021年11月26日（金）\n出演者：Yu\n\n人数：100名\nプレート被り：0\nテーブル逃し：0\nバースデーカード逃し：0\nお断り：0\nオーダー：0\n食事提供：0\n\n【本日の目標】\n本日の目標本日の目標本日の目標\n\n【本日の反省】\n本日の反省本日の反省本日の反省"
            ],
        ]);
    }
}
