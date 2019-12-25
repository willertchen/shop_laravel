<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendSignUpMailJob;
use Mail;
use Validator;  // 驗證器
use Hash;       // 雜湊
use DB;
use App\Shop\Entity\User;  // 使用者 Eloquent ORM Model
use Socialite;

class UserAuthController extends Controller
{

    // 註冊頁
    public function signUpPage()
    {
        $binding = [
            'title' => '註冊',
        ];
        return view('auth.signUp', $binding);
    }

    // 處理註冊資料
    public function signUpProcess()
    {
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
            'email' => $input['email'],
        ];

//        派發 "註冊成功信" 工作
        SendSignUpMailJob::dispatch($mail_binding);

//        重新導向到登入頁面
        return redirect('/user/auth/sign-in');
    }

//    登入
    public function signInPage()
    {
        $binding = [
            'title' => '登入',
        ];

        return view('auth.signIn', $binding);
    }

//    處理登入資料
    public function signInProcess()
    {
//        接收輸入資料
        $input = request()->all();

//        驗證規則
        $rules = [
//            Email
            'email' => [
                'required',
                'max:150',
                'email',
            ],
//            密碼
            'password' => [
                'required',
                'min:6',
            ],
        ];

//        驗證資料
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
//            資料驗證錯誤
            return redirect('/user/auth/sign-in')
                ->withErrors($validator)
                ->withInput();
        }

//        啟用記錄 SQL 語法
//        DB::enableQueryLog();

//        撈取使用者資料
        $User = User::where('email', $input['email'])->firstOrFail();

//        列印出目前資料庫目前所有執行的 SQL 語法
//        var_dump(DB::getQueryLog());
//        exit;

//        檢查密碼是否正確
        $is_password_correct = Hash::check($input['password'], $User->password);

        if (!$is_password_correct) {
            $error_message = [
                'msg' => [
                    '密碼驗證錯誤',
                ],
            ];

            return redirect('/user/auth/sign-in')
                ->withErrors($error_message)
                ->withInput();
        }

//        session 記錄會員編號
        session()->put('user_id', $User->id);

//        重新導向到原先使用者造訪的頁面，沒有嘗試造訪頁，則重新導回首頁
        return redirect()->intended('/');
    }

//    處理登出資料
    public function signOut()
    {
//        清除 Session
        session()->forget('user_id');

//        重碟導向首頁
        return redirect('/');
    }

//    facebook 登入
    public function facebookSignInProcess()
    {
        $redirect_url = env('FB_REDIRECT');

        return Socialite::driver('facebook')
            ->scopes(['user_friends'])
            ->redirectUrl($redirect_url)
            ->redirect();
    }

//    Facebook 登入重新導向授權資料處理
    public function facebookSignInCallbackProcess()
    {
        if (request()->error == 'access_denied') {
            throw new Exception('授權失敗，存取錯誤');
        }
//        依照網域產出重新導向連結 (來驗證是否為發出時同一 callback)
        $redirect_url = env('FB_REDIRECT');
//        取得第三方使用者資料
        $FacebookUser = Socialite::driver('facebook')
            ->fields([
                'name',
                'email',
                'gender',
                'verified',
                'link',
                'first_name',
                'last_name',
                'locale',
            ])
            ->redirectUrl($redirect_url)->user();

        $facebook_email = $FacebookUser->email;

        if (is_null($facebook_email)) {
            throw new Exception('未授權取得使用者 Email');
        }
//        取得 Facebook 資料
        $facebook_id = $FacebookUser->id;
        $facebook_name = $FacebookUser->name;

//        取得使用者資料是否有此 Facebook id 資料
        $User = User::where('facebook_id', $facebook_id)->first();

        if (is_null($User)) {
//            沒有綁定 Facebook Id 的帳號滿透過 Email 尋找是否有此帳號
            $User = User::where('email', $facebook_email)->fisrt();
            if (!is_null($User)) {
//                有此帳號，綁定 Facebook Id
                $User->facebook_id = $facebook_id;
                $User->save();
            }
        }

        if (is_null($User)) {
//            尚未註冊
            $input = [
                'email' => $facebook_email,
                'nickname' => $facebook_name,
                'password' => uniqid(),  // 隨機產生密碼
                'facebook_id' => $facebook_id,
                'type' => 'G',
            ];
//            密碼加密
            $input['password'] = Hash::make($input['password']);
//            新增會員資料
            $User = User::create($input);

//            寄送註冊信
            $mail_binding = [
                'nickname' => $input['nickname'],
                'email' => $input['email'],
            ];

//            派發 "註冊成功信" 工作
            SendSignUpMailJob::dispatch($mail_binding);

        }
//        登入會員
//        session 記錄會員編號
        session()->put('user_id', $User->id);

//        重新導向到原先使用者造訪頁面，如果沒有造訪頁則重新導向回首頁
        return redirect()->intended('/');
    }
}
