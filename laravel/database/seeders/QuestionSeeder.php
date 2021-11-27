<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            ['question_content' => '18:30には準備が終わり、クロークを出ている'],
            ['question_content' => 'クロークを出る際は必ず電気を消している'],
            ['question_content' => '翌日の12:00までに日報を終えている'],
            ['question_content' => 'ミーティングやデータ提出、その他遅刻がない'],
            ['question_content' => 'BDを拾った際は以下３点を徹底している①名前をきく②タブレット確認③BD予約がない場合はスタッフに共有する'],
            // ['question_content' => '出勤したら必ずプレートの時間を確認し、被らないように気をつけている'],
            // ['question_content' => 'タブレットを確認しながら入店順通りにテーブルを回っている'],
            // ['question_content' => 'テーブルに入っていないときはストリートマジックを続けている'],
            // ['question_content' => '状況確認であってもお客様をジロジロ見ない'],
            // ['question_content' => '入店直後（フード注文前）のテーブルには入らない'],
            // ['question_content' => '混雑日、「伝票」または「アンケート」が出ているテーブルには入らない'],
            // ['question_content' => '温かい料理が出た直後5分間はテーブルに入らない'],
            // ['question_content' => '料理が出た直後1分間はテーブルに入らない'],
            // ['question_content' => 'プレート5分前には該当フロアでのパフォーマンスを中断している'],
            // ['question_content' => 'パフォーマンス前、最中にグラス等の安全確認ができている'],
            // ['question_content' => 'テーブル時間（挨拶＋パフォーマンス）5分を守れている'],
            // ['question_content' => '「後で来てください！」と言われたら「呼んでもらえた時にいつでも行きますね！」と返している'],
            // ['question_content' => '貸切の際はスタッフに入るかどうか確認している'],
            // ['question_content' => '男女テーブルでは女性の手には触れていない'],
            // ['question_content' => '混雑時アンコールの時は「特別に次が最後です」と念を押した上で2分以内1演目で納めている'],
            // ['question_content' => 'シフト交代をする時は必ず交代理由を添えている'],
            // ['question_content' => 'シフト交代をしてもらったら前日にリマインドが来るようにLINEの「イベント」で設定をしている'],
            // ['question_content' => 'シフトのカレンダーは必ず自分の日付を確認した上でスタンプを押している'],
            // ['question_content' => 'シフトを直前で代わってもらい、カレンダーに掲載されていない交代はノートに記載している'],
            // ['question_content' => '誕生日のお客様には必ずバースデーカードトリックをしている'],
            // ['question_content' => '全力でコールに参加している'],
            // ['question_content' => 'チップをいただいた際には原則、テーブルを離れるまではチップをしまわない（縦に折って胸ポケットに刺すetc）'],
            // ['question_content' => 'チップを煽る行為及び戦略的ルーティンを行なっていない'],
            // ['question_content' => 'お客様から勧められたお酒は例外無く、丁寧にお断りをしている'],
            // ['question_content' => '23:00以降にパフォーマンスをしていない'],
            // ['question_content' => 'アンケートに記入を促す行為をしない'],
            // ['question_content' => 'グループLINEで代演を探してる人がいて自分は出れない時は翌日23:59までに必ず「出れない」と返事をしている'],
            // ['question_content' => 'リーダーの許可なしにステージマジックを行なっていない'],
            // ['question_content' => 'お店に対し意見反論がある場合は直接議論せず、必ずリーダーに相談する'],
            // ['question_content' => 'スタッフに「あのテーブル入って」と依頼されたら速やかに入っている'],
            // ['question_content' => '料理の上空でパフォーマンスをしていない'],
            // ['question_content' => 'ダブパンを使用したら必ずオイルをペーパーで拭き取ってから戻している'],
            // ['question_content' => 'ノートが共有されたら確認のうえ翌日23:59までに必ずスタンプを押して'],
            // ['question_content' => 'モチベーションにムラがない'],
            // ['question_content' => 'しっかりと場を盛り上げることができる'],
            // ['question_content' => '報告連絡相談確認再確認が迅速で正確'],
            // ['question_content' => '出勤時、退勤時は自分から笑顔で挨拶ができる'],
            // ['question_content' => 'スタッフやお客様に正しい言葉遣いができる'],
            // ['question_content' => 'バックグラウンドを探りながらパフォーマンスできる'],
            // ['question_content' => '料理等とかぶった時はパフォーマンスの中で誘導できる'],
            // ['question_content' => 'テーブルに入っていない時間は短い'],
            // ['question_content' => '効率よくテーブルを回り、逃しを抑えることができる'],
            // ['question_content' => 'お客様の会話を遮らずにテーブルに入れる'],
            // ['question_content' => 'ファースト、バースデー、ウェルカムを優先して入ることができる'],
            // ['question_content' => 'スタッフ、お客様の動線を塞いでいない'],
            // ['question_content' => 'スタッフとコミュニケーションが取れている'],
         ]);
    }
}
