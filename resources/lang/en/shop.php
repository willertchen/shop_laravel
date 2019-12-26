<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'hone' => 'Home',
    'transaction' => [
        'name' => 'Transaction',
        'list' => 'Transaction List',
        'buy' => 'But',
        'fields' => [
            'buy-count' => 'Buy Number',
        ],
    ],
    'merchandise' => [
        'name' => 'Merchandise',
        'create' => 'Create Merchandise',
        'manage' => 'Manage Merchandise',
        'edit' => 'Edit Merchandise',
        'list' => 'Merchandise List',
        'page' => 'Merchandise Page',
        'purchase-success' => 'Purchase Success',
        'update' => 'Update',
        'fields' => [
            'id' => 'Id',
            'status-name' => 'Status',
            'status' => [
                'create' => 'Create',
                'sell' => 'Sell',
            ],
            'name' => 'Name',
            'name-en' => 'English Name',
            'introduction' => 'Introduction',
            'introduction-en' => 'English Introduction',
            'photo' => 'Photo',
            'price' => 'Price',
            'remain-count' => 'Remain Number',
        ],
    ],
    'auth' => [
        'sign-out' => 'Sign Out',
        'sign-in' => 'Sign In',
        'sign-up' => 'Sign Up',
        'facebook-sign-in' => 'Facebook Sign In',
    ],
    'user' => [
        'fields' => [
            'nickname' => 'Nickname',
            'email' => 'Email',
            'password' => 'Password',
            'confirm-password' => 'Confirm Password',
            'type-name' => 'Type',
            'type' => [
                'general' => 'General',
                'admin' => 'Admin',
            ],
        ],
    ],
];
