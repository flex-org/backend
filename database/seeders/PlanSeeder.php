<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use App\Modules\Plans\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'active' => true,
                'storage' => 100, 
                'capacity' => 2000,
                'type' => 'basic',
                'translations' => [
                    'en' => [
                        'description' => 'Perfect for instructors starting out. Create courses, add videos, manage students, and organize content with ease.',
                        'points' => [
                            'Create unlimited categories & courses',
                            'Organize courses into sessions',
                            'Add videos and files',
                            'Up to 5000 students',
                            '100 GB storage',
                            'Multiple Themes',
                            'Content Control',
                        ]
                    ],
                    'ar' => [
                        'description' => 'مثالية للمدرسين المبتدئين. أنشئ دورات، أضف فيديوهات، إدارة الطلاب وتنظيم المحتوى بسهولة.',
                        'points' => [
                            'إنشاء عدد غير محدود من الدورات زالأقسام',
                            'تنظيم الدورات إلى جلسات',
                            'إضافة فيديوهات والملفات',
                            'حتى 5,000 طالب',
                            '100 جيجابايت تخزين',
                            'الثيمات المتعددة',
                            'التحكم في المحتوى'
                        ]
                    ],
                ],
                'pricing' => [
                    [
                        'billing_cycle' => 'monthly',
                        'months'     => 1,
                        'price'      => 1000,
                        'discount'   => 0,
                        'is_in_sale' => false,
                    ],
                    [
                        'billing_cycle' => 'yearly',
                        'months'     => 12,
                        'price'      => 11000,
                        'discount'   => 1000,
                        'is_in_sale' => false,
                    ]
                ]
            ],
            [
                'active' => true,
                'storage' => 500, 
                'capacity' => 10000,
                'type' => 'pro',
                'translations' => [
                    'en' => [
                        'description' => 'Customize your platform yourself and choose from multiple features to match your exact needs.',
                        'points' => [
                            'Everything in Basic',
                            'Drag and drop features builder',
                            'Choose from multiple advanced features',
                            'Up to 100,000 students',
                            '500 GB storage availble to growth',
                            'Priority support',
                            'Live sessions, quizzes, assignments, certificates, and question bank',
                        ]
                    ],
                    'ar' => [
                        'description' => 'خصص منصتك بنفسك واختر من بين مميزات متعددة لتلبي احتياجاتك بدقة.',
                        'points' => [
                            'كل شيء في الخطة الأساسية',
                            'أداة بناء مميزات بالسحب والإفلات',
                            'الاختيار من بين مميزات متقدمة متعددة',
                            'حتى 100,000 طالب',
                            '500 جيجابايت تخزين قابلة للزيادة',
                            'دعم أولوية',
                            'جلسات مباشرة، اختبارات، واجبات، شهادات وبنك أسئلة',
                        ]
                    ],
                ],
                'pricing' => [
                    [
                        'billing_cycle' => 'monthly',
                        'months'     => 1,
                        'price'      => 2000,
                        'discount'   => 0,
                        'is_in_sale' => true,
                    ],
                    [
                        'billing_cycle' => 'yearly',
                        'months'     => 12,
                        'price'      => 20000,
                        'discount'   => 4000,
                        'is_in_sale' => true,
                    ]
                ]
            ],
            [
                'active' => true,
                'type' => 'customized',
                'translations' => [
                    'en' => [
                        'description' => 'We build your idea into a fully tailored e-learning platform made just for you.',
                        'points' => [
                            'Fully tailored platform based on your idea',
                            'Customizable features as requested',
                            'Personalized integrations',
                            'Dedicated account manager',
                            'Custom pricing based on requirements',
                        ]
                    ],
                    'ar' => [
                        'description' => 'نحوّل فكرتك إلى منصة تعليم إلكتروني كاملة مصممة خصيصًا لك.',
                        'points' => [
                            'منصة مصممة بالكامل حسب فكرتك',
                            'مميزات قابلة للتخصيص حسب الطلب',
                            'تكاملات مخصصة',
                            'مدير حساب مخصص',
                            'تسعير مخصص حسب المتطلبات',
                        ]
                    ],
                ],
                'pricing' => []
            ],
        ];




        foreach ($plans as $data) {
            $translations = Arr::pull($data, 'translations');
            $pricing = Arr::pull($data, 'pricing');

            $plan = Plan::create($data);

            foreach ($translations as $locale => $translation) {
                $plan->translateOrNew($locale)->description = $translation['description'];
                $plan->translateOrNew($locale)->points = $translation['points'];
            }
            $plan->prices()->createMany($pricing);
            $plan->save();
        }
    }
}
