<?php

namespace Database\Seeders;

use App\Modules\Features\Models\DynamicFeatures;
use Illuminate\Database\Seeder;
use App\Modules\Features\Models\Feature;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'icon' => 'fa-book',
                'price' => 0,
                'active' => true,
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
                'icon' => 'fa-layer-group',
                'price' => 0,
                'active' => true,
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
                'icon' => 'fa-video',
                'price' => 0,
                'active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Sessions',
                        'description' => 'Schedule and manage sessions within courses',
                    ],
                    'ar' => [
                        'name' => 'الجلسات',
                        'description' => 'جدولة وإدارة الجلسات داخل الكورسات',
                    ],
                ],
            ],
            [
                'icon' => 'fa-file-alt',
                'price' => 0,
                'active' => true,
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
                'price' => 0,
                'active' => true,
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
                'price' => 0,
                'active' => true,
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
                'price' => 0,
                'active' => true,
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
                'price' => 0,
                'active' => true,
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
                'price' => 0,
                'active' => true,
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
                'price' => 0,
                'active' => true,
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
            [
                'icon' => 'fa-mobile-alt',
                'price' => 199.99,
                'active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Mobile App',
                        'description' => 'Get a dedicated mobile application for your platform',
                    ],
                    'ar' => [
                        'name' => 'تطبيق موبايل',
                        'description' => 'احصل على تطبيق موبايل مخصص لمنصتك',
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

            // إنشاء Permission مرتبط بالـ Feature
            Permission::firstOrCreate([
                'name' => 'feature-' . $feature->id,
                'guard_name' => 'sanctum',
            ]);
        }
    }
}
