<?php

namespace App\Http\Controllers;

use App\Shop\Entity\Merchandise;
use Validator;  // 驗證器

class MerchandiseController extends Controller
{
//    新增商品
    public function merchandiseCreateProcess()
    {
//        建立商品基本資料
        $merchandise_data = [
            'status' => 'C',  // 建立中
            'name' => '',  // 商品名稱
            'name_en' => '',  // 商品英文名稱
            'introduction' => '',  // 商品介紹
            'introduction_en' => '',  // 商品英文介紹
            'photo' => null,  // 商品照片
            'price' => 0,  // 價格
            'remain_count' => 0,  // 商品剩餘數量
        ];
        $Merchandoise = Merchandise::create($merchandise_data);

//        重新導向至「商品編輯頁」
        return redirect('/merchandise' . $Merchandoise->id . '/edit');
    }

//    商品編輯頁
    public function merchandiseItemEditPage($merchandise_id)
    {
        // 撈取商品資料
        $Merchandise = Merchandise::findOrFail($merchandise_id);

        if (!is_null($Merchandise->photo)) {
//            設定商品照片網址
            $Merchandise->photo = url($Merchandise->photo);
        }

        $binding = [
            'title' => '編輯商品',
            'Merchandise' => $Merchandise,
        ];

        return view('merchandise.editMerchandise', $binding);
    }
}
