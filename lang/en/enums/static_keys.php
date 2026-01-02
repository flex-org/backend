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
        'ca' => [
            'label' => 'Category-Based Sales',
            'description' => 'Sell educational content by category, giving students access to all courses within the selected category',
        ],
        'co' => [
            'label' => 'Course-Based Sales',
            'description' => 'Sell each course separately with full control over pricing and access permissions',
        ],
        's' => [
            'label' => 'Session / Lesson-Based Sales',
            'description' => 'Sell individual lessons or sessions separately, whether live or recorded',
        ],
        'ss' => [
            'label' => 'Subscriptions',
            'description' => 'Subscription system that allows students to access content for a specific period of time',
        ],
    ]
];
