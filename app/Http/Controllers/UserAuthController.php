<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use Validator;  // 驗證器
use Hash;       // 雜湊
use App\Shop\Entity\User;  // 使用者 Eloquent ORM Model

class UserAuthController extends Controller
{

    // 註冊頁
    public function signUpPage(){
        $binding = [
            'title' => '註冊',
        ];
        return view('auth.signUp', $binding);
    }

    // 處理註冊資料
    public function signUpProcess(){
        $input = request()->all();

        // 驗證規則
        $rules = [
            'nickname' => [
                'required',
                'max:50',
            ],
            'email' => [
                'required',
                'max:150',
                'email',
            ],
            'password' => [
                'required',
                'same:password_confirmation',
                'min:6',
            ],
            'password_confirmation' => [
                'required',
                'min:6',
            ],
            'type' => [
                'required',
                'in:G,A',
            ],
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect('/user/auth/sign-up')
                ->withErrors($validator)
                ->withInput();
        }

//        密碼加密
        $input['password'] = Hash::make($input['password']);

//        新增會員資料
        $User = User::create($input);

//        寄送註冊通知信
        $mail_binding = [
            'nickname' => $input['nickname'],
        ];

        Mail::send('email.signUpEmailNotification', $mail_binding, function ($mail) use ($input){
            $mail->to($input['email']);
            $mail->from('eml0777ys@gmail.com');
            $mail->subject('恭禧註冊 Shop Laravel 成功');
        });

//        重新導向到登入頁面
        return redirect('/user/auth/sign-in');
    }
}
