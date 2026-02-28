<?php

namespace Database\Seeders;

use App\Modules\Features\Models\DynamicFeatures;
use Illuminate\Database\Seeder;
use App\Modules\V1\Features\Models\Feature;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            // basic features
            [
                'icon' => 'fa-book',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Courses',
                        'description' => 'Create and manage courses with lessons, videos and files',
                    ],
                    'ar' => [
                        'name' => 'الكورسات',
                        'description' => 'إنشاء وإدارة الكورسات مع الدروس والفيديوهات والملفات',
                    ],
                ],
            ],
            [
                'icon' => 'fa-users',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Students Management',
                        'description' => 'Manage students, enrollment and access permissions',
                    ],
                    'ar' => [
                        'name' => 'إدارة الطلاب',
                        'description' => 'إدارة الطلاب وتسجيلهم وصلاحيات الوصول',
                    ],
                ],
            ],
            [
                'icon' => 'fa-folder-open',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Content Management',
                        'description' => 'Upload and organize videos, files and learning materials',
                    ],
                    'ar' => [
                        'name' => 'تنظيم المحتوى',
                        'description' => 'رفع وتنظيم الفيديوهات والملفات والمواد التعليمية',
                    ],
                ],
            ],
            [
                'icon' => 'fa-user-shield',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Roles & Permissions',
                        'description' => 'Control access levels for admins, instructors and students',
                    ],
                    'ar' => [
                        'name' => 'الصلاحيات والأدوار',
                        'description' => 'التحكم في صلاحيات الأدمن والطلاب',
                    ],
                ],
            ],
            [
                'icon' => 'fa-lock',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Basic Security',
                        'description' => 'Secure access with authentication and encrypted connections',
                    ],
                    'ar' => [
                        'name' => 'الأمان الأساسي',
                        'description' => 'تأمين الدخول باستخدام التحقق والتشفير',
                    ],
                ],
            ],
            [
                'icon' => 'fa-chart-line',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Basic Reports',
                        'description' => 'View basic statistics about students and courses',
                    ],
                    'ar' => [
                        'name' => 'تقارير أساسية',
                        'description' => 'عرض إحصائيات أساسية عن الطلاب والكورسات',
                    ],
                ],
            ],
            [
                'icon' => 'fa-cog',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Platform Settings',
                        'description' => 'Manage platform settings and branding',
                    ],
                    'ar' => [
                        'name' => 'إعدادات المنصة',
                        'description' => 'التحكم في إعدادات المنصة والهوية',
                    ],
                ],
            ],
            [
                'icon' => 'fa-credit-card',
                'price' => 50,
                'active' => true,
                'default' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Online Payments',
                        'description' => 'Accept online payments, manage subscriptions and track transactions',
                    ],
                    'ar' => [
                        'name' => 'المدفوعات الإلكترونية',
                        'description' => 'قبول المدفوعات الإلكترونية، إدارة الاشتراكات وتتبع العمليات المالية',
                    ],
                ],
            ],
            // paid features
            [
                'icon' => 'fa-layer-group',
                'price' => 50,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Categories',
                        'description' => 'Organize courses into categories and manage them',
                    ],
                    'ar' => [
                        'name' => 'الأقسام',
                        'description' => 'تنظيم الكورسات فى أقسام وإدارتها',
                    ],
                ],
            ],
            [
                'icon' => 'fa-file-alt',
                'price' => 75,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Assignments',
                        'description' => 'Create assignments with file attachments and track submissions',
                    ],
                    'ar' => [
                        'name' => 'التكليفات',
                        'description' => 'إنشاء التكليفات مع ملفات مرفقة وتتبع التسليمات',
                    ],
                ],
            ],
            [
                'icon' => 'fa-question-circle',
                'price' => 50,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Question Bank',
                        'description' => 'Build and manage a question bank for exams',
                    ],
                    'ar' => [
                        'name' => 'بنك الأسئلة',
                        'description' => 'إنشاء وإدارة بنك الأسئلة للاختبارات',
                    ],
                ],
            ],
            [
                'icon' => 'fa-clipboard-check',
                'price' => 75,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Quizzes & Exams',
                        'description' => 'Create quizzes and exams and view student grades',
                    ],
                    'ar' => [
                        'name' => 'الاختبارات',
                        'description' => 'إنشاء الاختبارات والامتحانات ومتابعة درجات الطلاب',
                    ],
                ],
            ],
            [
                'icon' => 'fa-bullhorn',
                'price' => 50,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Announcements',
                        'description' => 'Create and schedule announcements for students',
                    ],
                    'ar' => [
                        'name' => 'الإعلانات',
                        'description' => 'إنشاء وجدولة الإعلانات للطلاب',
                    ],
                ],
            ],
            [
                'icon' => 'fa-broadcast-tower',
                'price' => 200,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Live Sessions',
                        'description' => 'Schedule and host live sessions and webinars',
                    ],
                    'ar' => [
                        'name' => 'الجلسات المباشرة',
                        'description' => 'جدولة واستضافة الجلسات المباشرة والندوات',
                    ],
                ],
            ],
            [
                'icon' => 'fa-certificate',
                'price' => 75,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Certificates',
                        'description' => 'Generate certificates for courses or categories',
                    ],
                    'ar' => [
                        'name' => 'الشهادات',
                        'description' => 'توليد الشهادات للكورسات أو الأقسام',
                    ],
                ],
            ],
            [
                'icon' => 'fa-calendar-alt',
                'price' => 50,
                'active' => true,
                'default' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Calendar',
                        'description' => 'View academic calendar and important deadlines',
                    ],
                    'ar' => [
                        'name' => 'التقويم',
                        'description' => 'عرض التقويم الأكاديمي والمواعيد الهامة',
                    ],
                ],
            ],
        ];

        foreach ($features as $data) {
            $translations = Arr::pull($data, 'translations');

            $feature = Feature::create($data);

            foreach ($translations as $locale => $translation) {
                $feature->translateOrNew($locale)->name = $translation['name'];
                $feature->translateOrNew($locale)->description = $translation['description'];
            }
            $feature->save();
        }
    }
}
