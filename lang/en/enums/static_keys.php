<?php

return [

    'feature_type' => [
        'basic' => 'basic',
        'pro' => 'pro',
    ],
 
    'plan_type' => [
        'basic' => 'Basic',
        'pro' => 'Pro',
        'customized' => 'Customized'
    ],

    'subscription_status' => [
        'active' => 'active',
        'expired' => 'expired',
        'freetrial' => 'free trial' ,
        'pending' => 'pending',
        'deactivated' => 'deactivated',
    ],

    'billing_cycle' => [
        'yearly' => 'Yearly', 
        'monthly' => 'Monthly', 
        'quarterly' => 'Quarterly'
    ],

    'selling_system' => [
        'cacos' => 'selling by category, course or session',
        'caco' => 'selling by category or course',
        'cas' => 'selling by category or session',
        'cos' => 'selling by course or session',
        'ca' => 'selling by category',
        'co' => 'selling by course',
        's' => 'selling by session'
    ]
];
