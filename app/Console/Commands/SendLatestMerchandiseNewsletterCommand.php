<?php

namespace App\Console\Commands;

use App\Jobs\SendMerchandiseNewslettetJob;
use App\Shop\Entity\Merchandise;
use App\Shop\Entity\User;
use Illuminate\Console\Command;

class SendLatestMerchandiseNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    指令名稱
    protected $signature = 'shop:sendLatestMerchandiseNewsletter';

    /**
     * The console command description.
     *
     * @var string
     */
//    指令描述
    protected $description = '[郵件] 寄送最新商品電子報';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
//    執行工作
    public function handle()
    {
        $this->info('寄送最新商品電子報（Start）');
        $this->info('撈取最新商品');
//        撈取最新更新 10 筆可販售商品
        $total_row = 10;
        $MerchandiseCollection = Merchandise::OrderBy('created_at', 'desc')
            ->where('status', 'S')
            ->take($total_row)
            ->get();

//        寄送電子信給所有會員，每次撈取 100 筆會員資料
        $row_per_page = 100;
        $page = 1;
        while (true) {
//            略過資料筆數 => 第二次執行，就要略過前 100 筆，第三次以此類推
            $skip = ($page - 1) * $row_per_page;
//            取得分頁會員資料
            $this->comment('取得使用者資料，第 ' . $page .' 頁，每頁 ' . $row_per_page . ' 筆');
            $UserCollection = User::OrderBy('id', 'asc')
            ->skip($skip)
            ->take($row_per_page)
            ->get();

            if (!$UserCollection->count()) {
//                沒有使用者資料了，停止派送電子報
                $this->question('沒有使用者資料了，停止派送電子報');
                break;
            }

//            派送會員電子信工作
            $this->comment('派送會員電子信（Start）');
            foreach ($UserCollection as $User) {
                SendMerchandiseNewslettetJob::dispatch($User, $MerchandiseCollection)->onQueue('low');
            }
            $this->comment('派送會員電子信（End）');

//            繼續找看看還有沒有需要寄送電子信的使用者
            $page++;
        }

        $this->info('寄送最新商品電子報（End）');
    }
}
