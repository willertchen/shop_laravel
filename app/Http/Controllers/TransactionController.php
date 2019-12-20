<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop\Entity\Merchandise;
use Validator;  // 驗證器

class TransactionController extends Controller
{
    // 商品清單檢視
    public function transactionListPage()
    {
        $user_id = session()->get('user_id');

//        每頁資料量
        $row_per_page = 10;
//        撈取商品分頁資料
        $TransactionPaginate = Transaction::where('user_id', $user_id)
            ->OrderBy('created_at', 'desc')
            ->with('Merchandise')  // 把關聯資料也撈取出來
            ->paginate($row_per_page);

        foreach ($TransactionPaginate as &$Transaction) {
//            設定商品圖片網址
            if (!is_null($Transaction->Merchandise->photo)) {
                $Transaction->Merchandise->photo = url($Transaction->Merchandise->photo);
            }
        }

        $binding = [
            'title' => '交易紀綠',
            'TransactionPaginate' => $TransactionPaginate,
        ];

        return view('transaction.listUserTransaction', $binding);
    }

}
