<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\District;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoSeeder = new DemoDataSeeder();
        $demoSeeder->setCommand($this->command);
        $demoSeeder->seedAllUserTypes();

        $this->seedDistricts();
        $this->seedCategories();
        $this->seedStaff();
        $this->seedArticles();

        $this->call(VideoDemoSeeder::class);
        $this->call(NewsSeeder::class);

        $this->safe($demoSeeder, 'seedComments');
        $this->safe($demoSeeder, 'seedArticleTags');
        $this->safe($demoSeeder, 'seedArticleLikes');
        $this->safe($demoSeeder, 'seedSavedArticles');
        $this->safe($demoSeeder, 'seedArchiveDocuments');
        $this->safe($demoSeeder, 'seedRedirects');
        $this->safe($demoSeeder, 'seedSettings');
        $this->safe($demoSeeder, 'seedContacts');
        $this->safe($demoSeeder, 'seedAdvertisements');
    }

    private function safe(object $seeder, string $method): void
    {
        try {
            $seeder->{$method}();
        } catch (\Throwable $e) {
            $this->command->warn("Skipped {$method}: " . $e->getMessage());
        }
    }

    private function seedDistricts(): void
    {
        $districts = [
            ['name_bn' => 'ঢাকা', 'name_en' => 'Dhaka', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'ফরিদপুর', 'name_en' => 'Faridpur', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'গাজীপুর', 'name_en' => 'Gazipur', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'গোপালগঞ্জ', 'name_en' => 'Gopalganj', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'কিশোরগঞ্জ', 'name_en' => 'Kishoreganj', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'মাদারীপুর', 'name_en' => 'Madaripur', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'মানিকগঞ্জ', 'name_en' => 'Manikganj', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'মুন্সীগঞ্জ', 'name_en' => 'Munshiganj', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'নারায়ণগঞ্জ', 'name_en' => 'Narayanganj', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'নরসিংদী', 'name_en' => 'Narsingdi', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'রাজবাড়ী', 'name_en' => 'Rajbari', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'শরীয়তপুর', 'name_en' => 'Shariatpur', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'টাঙ্গাইল', 'name_en' => 'Tangail', 'division_bn' => 'ঢাকা', 'division_en' => 'Dhaka'],
            ['name_bn' => 'চট্টগ্রাম', 'name_en' => 'Chattogram', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'বান্দরবান', 'name_en' => 'Bandarban', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'ব্রাহ্মণবাড়িয়া', 'name_en' => 'Brahmanbaria', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'চাঁদপুর', 'name_en' => 'Chandpur', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'কক্সবাজার', 'name_en' => "Cox's Bazar", 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'কুমিল্লা', 'name_en' => 'Cumilla', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'ফেনী', 'name_en' => 'Feni', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'খাগড়াছড়ি', 'name_en' => 'Khagrachari', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'লক্ষ্মীপুর', 'name_en' => 'Lakshmipur', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'নোয়াখালী', 'name_en' => 'Noakhali', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'রাঙ্গামাটি', 'name_en' => 'Rangamati', 'division_bn' => 'চট্টগ্রাম', 'division_en' => 'Chattogram'],
            ['name_bn' => 'রাজশাহী', 'name_en' => 'Rajshahi', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'বগুড়া', 'name_en' => 'Bogura', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'চাঁপাইনবাবগঞ্জ', 'name_en' => 'Chapainawabganj', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'জয়পুরহাট', 'name_en' => 'Joypurhat', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'নওগাঁ', 'name_en' => 'Naogaon', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'নাটোর', 'name_en' => 'Natore', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'পাবনা', 'name_en' => 'Pabna', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'সিরাজগঞ্জ', 'name_en' => 'Sirajganj', 'division_bn' => 'রাজশাহী', 'division_en' => 'Rajshahi'],
            ['name_bn' => 'খুলনা', 'name_en' => 'Khulna', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'বাগেরহাট', 'name_en' => 'Bagerhat', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'চুয়াডাঙ্গা', 'name_en' => 'Chuadanga', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'যশোর', 'name_en' => 'Jashore', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'ঝিনাইদহ', 'name_en' => 'Jhenaidah', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'কুষ্টিয়া', 'name_en' => 'Kushtia', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'মাগুরা', 'name_en' => 'Magura', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'মেহেরপুর', 'name_en' => 'Meherpur', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'নড়াইল', 'name_en' => 'Narail', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'সাতক্ষীরা', 'name_en' => 'Satkhira', 'division_bn' => 'খুলনা', 'division_en' => 'Khulna'],
            ['name_bn' => 'বরিশাল', 'name_en' => 'Barishal', 'division_bn' => 'বরিশাল', 'division_en' => 'Barishal'],
            ['name_bn' => 'ভোলা', 'name_en' => 'Bhola', 'division_bn' => 'বরিশাল', 'division_en' => 'Barishal'],
            ['name_bn' => 'ঝালকাঠি', 'name_en' => 'Jhalokati', 'division_bn' => 'বরিশাল', 'division_en' => 'Barishal'],
            ['name_bn' => 'পটুয়াখালী', 'name_en' => 'Patuakhali', 'division_bn' => 'বরিশাল', 'division_en' => 'Barishal'],
            ['name_bn' => 'পিরোজপুর', 'name_en' => 'Pirojpur', 'division_bn' => 'বরিশাল', 'division_en' => 'Barishal'],
            ['name_bn' => 'বরগুনা', 'name_en' => 'Barguna', 'division_bn' => 'বরিশাল', 'division_en' => 'Barishal'],
            ['name_bn' => 'সিলেট', 'name_en' => 'Sylhet', 'division_bn' => 'সিলেট', 'division_en' => 'Sylhet'],
            ['name_bn' => 'হবিগঞ্জ', 'name_en' => 'Habiganj', 'division_bn' => 'সিলেট', 'division_en' => 'Sylhet'],
            ['name_bn' => 'মৌলভীবাজার', 'name_en' => 'Moulvibazar', 'division_bn' => 'সিলেট', 'division_en' => 'Sylhet'],
            ['name_bn' => 'সুনামগঞ্জ', 'name_en' => 'Sunamganj', 'division_bn' => 'সিলেট', 'division_en' => 'Sylhet'],
            ['name_bn' => 'রংপুর', 'name_en' => 'Rangpur', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'দিনাজপুর', 'name_en' => 'Dinajpur', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'গাইবান্ধা', 'name_en' => 'Gaibandha', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'কুড়িগ্রাম', 'name_en' => 'Kurigram', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'লালমনিরহাট', 'name_en' => 'Lalmonirhat', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'নীলফামারী', 'name_en' => 'Nilphamari', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'পঞ্চগড়', 'name_en' => 'Panchagarh', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'ঠাকুরগাঁও', 'name_en' => 'Thakurgaon', 'division_bn' => 'রংপুর', 'division_en' => 'Rangpur'],
            ['name_bn' => 'ময়মনসিংহ', 'name_en' => 'Mymensingh', 'division_bn' => 'ময়মনসিংহ', 'division_en' => 'Mymensingh'],
            ['name_bn' => 'জামালপুর', 'name_en' => 'Jamalpur', 'division_bn' => 'ময়মনসিংহ', 'division_en' => 'Mymensingh'],
            ['name_bn' => 'নেত্রকোণা', 'name_en' => 'Netrokona', 'division_bn' => 'ময়মনসিংহ', 'division_en' => 'Mymensingh'],
            ['name_bn' => 'শেরপুর', 'name_en' => 'Sherpur', 'division_bn' => 'ময়মনসিংহ', 'division_en' => 'Mymensingh'],
        ];

        foreach ($districts as $district) {
            District::firstOrCreate(['name_en' => $district['name_en']], $district);
        }

        $this->command->info('64 districts seeded successfully.');
    }

    private function seedCategories(): void
    {
        $categories = [
            ['name_bn' => 'জাতীয় শিক্ষা', 'name_en' => 'National Education', 'slug' => 'national', 'order' => 1],
            ['name_bn' => 'শিক্ষা নীতিমালা', 'name_en' => 'Education Policy', 'slug' => 'education-policy', 'order' => 2],
            ['name_bn' => 'শিক্ষক ও কর্মচারী', 'name_en' => 'Teachers & Staff', 'slug' => 'teacher-rights', 'order' => 3],
            ['name_bn' => 'প্রশিক্ষণ ও সেমিনার', 'name_en' => 'Training & Seminars', 'slug' => 'training', 'order' => 4],
            ['name_bn' => 'পরীক্ষা ও ফলাফল', 'name_en' => 'Exam & Results', 'slug' => 'exam-results', 'order' => 5],
            ['name_bn' => 'নিয়োগ ও বদলি', 'name_en' => 'Recruitment & Transfer', 'slug' => 'recruitment-transfer', 'order' => 6],
            ['name_bn' => 'বিজ্ঞপ্তি ও সার্কুলার', 'name_en' => 'Notice & Circular', 'slug' => 'notice-circular', 'order' => 7],
            ['name_bn' => 'বিশ্ববিদ্যালয়', 'name_en' => 'Universities', 'slug' => 'universities', 'order' => 8],
            ['name_bn' => 'মাদ্রাসা শিক্ষা', 'name_en' => 'Madrasah Education', 'slug' => 'madrasah', 'order' => 9],
            ['name_bn' => 'কারিগরি শিক্ষা', 'name_en' => 'Technical Education', 'slug' => 'technical', 'order' => 10],
            ['name_bn' => 'ক্যারিয়ার', 'name_en' => 'Career', 'slug' => 'career', 'order' => 11],
            ['name_bn' => 'আন্তর্জাতিক', 'name_en' => 'International', 'slug' => 'international', 'order' => 12],
            ['name_bn' => 'মতামত', 'name_en' => 'Opinion', 'slug' => 'opinion', 'order' => 13],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }

        $this->command->info('13 categories seeded successfully.');
    }

    private function seedStaff(): void
    {
        $staffs = [
            ['name_bn' => 'আব্দুর রহিম', 'name_en' => 'Abdur Rahim', 'designation_bn' => 'প্রধান প্রতিবেদক', 'designation_en' => 'Chief Reporter', 'staff_type' => 'reporter', 'email' => 'rahim@educationtimes.com', 'phone' => '01711111111', 'bio_bn' => 'প্রবীন শিক্ষা সাংবাদিক', 'is_active' => true, 'order' => 1],
            ['name_bn' => 'ফাতিমা বেগম', 'name_en' => 'Fatima Begum', 'designation_bn' => 'সিনিয়র প্রতিবেদক', 'designation_en' => 'Senior Reporter', 'staff_type' => 'reporter', 'email' => 'fatima@educationtimes.com', 'phone' => '01722222222', 'bio_bn' => 'শিক্ষা নীতি ও প্রশাসন নিয়ে কাজ করেন', 'is_active' => true, 'order' => 2],
            ['name_bn' => 'করিম উদ্দিন', 'name_en' => 'Karim Uddin', 'designation_bn' => 'প্রতিবেদক', 'designation_en' => 'Reporter', 'staff_type' => 'reporter', 'email' => 'karim@educationtimes.com', 'phone' => '01733333333', 'bio_bn' => 'ঢাকা বিভাগের শিক্ষা সংবাদ', 'is_active' => true, 'order' => 3],
            ['name_bn' => 'নাসরিন আক্তার', 'name_en' => 'Nasrin Akhtar', 'designation_bn' => 'প্রতিবেদক', 'designation_en' => 'Reporter', 'staff_type' => 'reporter', 'email' => 'nasrin@educationtimes.com', 'phone' => '01744444444', 'bio_bn' => 'শিক্ষা নিয়ে লেখালেখি', 'is_active' => true, 'order' => 4],
            ['name_bn' => 'ড. আবু বকর', 'name_en' => 'Dr. Abu Bakr', 'designation_bn' => 'কলামিস্ট', 'designation_en' => 'Columnist', 'staff_type' => 'columnist', 'email' => 'dr.bakr@educationtimes.com', 'bio_bn' => 'শিক্ষা গবেষক ও কলাম লেখক', 'is_active' => true, 'order' => 5],
            ['name_bn' => 'মাসুমা খাতুন', 'name_en' => 'Masuma Khatun', 'designation_bn' => 'জেলা প্রতিবেদক', 'designation_en' => 'District Correspondent', 'staff_type' => 'correspondent', 'email' => 'masuma@educationtimes.com', 'phone' => '01755555555', 'bio_bn' => 'রংপুর বিভাগের শিক্ষা সংবাদ', 'is_active' => true, 'order' => 6],
            ['name_bn' => 'এস এম শাহজাহান', 'name_en' => 'S M Shahjahan', 'designation_bn' => 'সম্পাদক', 'designation_en' => 'Editor', 'staff_type' => 'editor', 'email' => 'editor@educationtimes.com', 'phone' => '01766666666', 'bio_bn' => 'এডুকেশন টাইমসের সম্পাদক। শিক্ষা সাংবাদিকতায় দুই দশকের অভিজ্ঞতা।', 'is_active' => true, 'order' => 0],
            ['name_bn' => 'অধ্যাপক ড. মো. শফিকুল ইসলাম', 'name_en' => 'Prof. Dr. Md. Shafiqul Islam', 'designation_bn' => 'উপদেষ্টা', 'designation_en' => 'Advisor', 'staff_type' => 'advisor', 'email' => 'advisor@educationtimes.com', 'bio_bn' => 'শিক্ষা মন্ত্রণালয়ের সাবেক সচিব। শিক্ষা খাতে বিশেষজ্ঞ।', 'is_active' => true, 'order' => 0],
        ];

        foreach ($staffs as $s) {
            Staff::firstOrCreate(['email' => $s['email']], $s);
        }

        $this->command->info('6 staff members seeded successfully.');
    }

    private function seedArticles(): void
    {
        $categories = Category::all();
        $staff = Staff::all();

        if ($categories->isEmpty() || $staff->isEmpty()) {
            return;
        }

        $admin = User::where('is_admin', true)->first();

        $articles = [
            [
                'title_bn' => 'শিক্ষা ক্ষেত্রে ডিজিটাল প্রযুক্তির ব্যবহার বাড়ছে',
                'excerpt_bn' => 'দেশের বিদ্যালয়গুলোতে ডিজিটাল প্রযুক্তির ব্যবহার ক্রমশ বাড়ছে। সরকার ডিজিটাল শিক্ষা কার্যক্রম জোরদার করছে।',
                'body_bn' => '<p>সরকারি বিদ্যালয়গুলোতে ডিজিটাল প্রযুক্তির ব্যবহার দিন দিন বেড়েই চলেছে। ইতিমধ্যে দেশের ৮০ শতাংশ বিদ্যালয়ে মাল্টিমিডিয়া ক্লাসরুম স্থাপন করা হয়েছে।</p><p>শিক্ষা মন্ত্রণালয় সূত্রে জানা গেছে, আগামী দুই বছরের মধ্যে শতভাগ বিদ্যালয়ে ডিজিটাল ক্লাসরুম স্থাপনের লক্ষ্য নির্ধারণ করা হয়েছে।</p><p>শিক্ষা বিশেষজ্ঞরা বলছেন, ডিজিটাল প্রযুক্তি শিক্ষার্থীদের শেখার আগ্রহ বাড়াতে এবং শিক্ষকদের পাঠদান সহজ করতে গুরুত্বপূর্ণ ভূমিকা রাখছে।</p>',
                'category_id' => $categories->first()->id,
                'staff_id' => $staff->first()->id,
                'reading_time_minutes' => 3,
                'is_featured' => true,
                'is_editor_pick' => true,
            ],
            [
                'title_bn' => 'শিক্ষক নিয়োগে নতুন নীতিমালা অনুমোদন',
                'excerpt_bn' => 'সরকার শিক্ষক নিয়োগে নতুন নীতিমালা অনুমোদন করেছে। এতে স্বচ্ছতা ও জবাবদিহিতা নিশ্চিত হবে বলে আশা করা যাচ্ছে।',
                'body_bn' => '<p>সরকার বিদ্যালয়ে শিক্ষক নিয়োগের নতুন নীতিমালা অনুমোদন করেছে। এই নীতিমালায় নিয়োগ প্রক্রিয়াকে আরও স্বচ্ছ ও জবাবদিহিমূলক করার পদক্ষেপ নেওয়া হয়েছে।</p><p>নতুন নীতিমালা অনুযায়ী, শিক্ষক নিয়োগে থাকবে লিখিত পরীক্ষা, মৌখিক পরীক্ষা ও শিক্ষকতা দক্ষতা মূল্যায়ন। প্রতিটি ধাপে প্রার্থীদের নম্বর সংরক্ষিত থাকবে।</p><p>শিক্ষক নেতারা নতুন নীতিমালাকে স্বাগত জানিয়েছেন এবং বলেছেন, এতে মেধাবী প্রার্থীরা শিক্ষকতা পেশায় আসতে আগ্রহী হবেন।</p>',
                'category_id' => $categories->where('slug', 'recruitment-transfer')->first()?->id ?? $categories->first()->id,
                'staff_id' => $staff->skip(1)->first()->id,
                'reading_time_minutes' => 4,
                'is_breaking' => true,
                'is_editor_pick' => true,
            ],
            [
                'title_bn' => 'এসএসসি পরীক্ষার সময়সূচি প্রকাশ',
                'excerpt_bn' => 'এসএসসি পরীক্ষার সময়সূচি প্রকাশ করা হয়েছে। আগামী মাসে শুরু হবে এই পরীক্ষা।',
                'body_bn' => '<p>শিক্ষা বোর্ড এসএসসি পরীক্ষার সময়সূচি প্রকাশ করেছে। আগামী মাসে সারাদেশে একযোগে এই পরীক্ষা অনুষ্ঠিত হবে।</p><p>পরীক্ষা সুষ্ঠুভাবে সম্পাদনের জন্য ইতিমধ্যে প্রয়োজনীয় প্রস্তুতি নেওয়া হয়েছে। প্রতিটি উপজেলায় পরীক্ষা কেন্দ্র নির্ধারণ করা হয়েছে।</p><p>শিক্ষা মন্ত্রণালয়ের পক্ষ থেকে জানানো হয়েছে, এবারের পরীক্ষায় কোনো রকম অনিয়ম বরদাস্ত করা হবে না। কেন্দ্রগুলোতে সিসি ক্যামেরা বসানো হবে।</p>',
                'category_id' => $categories->where('slug', 'exam-results')->first()?->id ?? $categories->first()->id,
                'staff_id' => $staff->skip(2)->first()->id,
                'reading_time_minutes' => 3,
            ],
            [
                'title_bn' => 'শিক্ষার্থীদের মানসিক স্বাস্থ্য সুরক্ষায় নতুন উদ্যোগ',
                'excerpt_bn' => 'বিদ্যালয়ের শিক্ষার্থীদের মানসিক স্বাস্থ্য সুরক্ষায় সরকার নতুন উদ্যোগ গ্রহণ করেছে।',
                'body_bn' => '<p>বিদ্যালয়ের শিক্ষার্থীদের মানসিক স্বাস্থ্য সুরক্ষায় সরকার নতুন উদ্যোগ গ্রহণ করেছে। এই উদ্যোগের আওতায় প্রতিটি বিদ্যালয়ে একজন করে কাউন্সেলর নিয়োগ দেওয়া হবে।</p><p>শিক্ষা বিশেষজ্ঞরা বলছেন, শিক্ষার্থীদের মানসিক স্বাস্থ্যের প্রতি নজর দেওয়া অত্যন্ত জরুরি। বিশেষত কোভিড-১৯ মহামারির পর শিক্ষার্থীদের মধ্যে মানসিক চাপ বেড়েছে।</p><p>ইতিমধ্যে বেশ কিছু বিদ্যালয়ে পাইলট প্রকল্প হিসেবে কাউন্সেলিং সেবা চালু করা হয়েছে, যা সফল হয়েছে।</p>',
                'category_id' => $categories->where('slug', 'national')->first()?->id ?? $categories->first()->id,
                'staff_id' => $staff->skip(3)->first()->id,
                'reading_time_minutes' => 3,
            ],
            [
                'title_bn' => 'শিক্ষকদের বেতন কাঠামো পুনর্বিবেচনার দাবি',
                'excerpt_bn' => 'শিক্ষকদের বেতন কাঠামো পুনর্বিবেচনার দাবি জানিয়েছেন শিক্ষক নেতারা। বর্তমান বেতনে জীবনযাপন কঠিন বলে জানান তারা।',
                'body_bn' => '<p>সারাদেশের শিক্ষকরা তাদের বেতন কাঠামো পুনর্বিবেচনার দাবি জানিয়েছেন। বুধবার রাজধানীর একটি হোটেলে আয়োজিত সংবাদ সম্মেলনে শিক্ষক নেতারা এই দাবি জানান।</p><p>শিক্ষক নেতারা বলেছেন, বর্তমান বেতন কাঠামোতে একজন শিক্ষকের পক্ষে জীবনযাপন করা অত্যন্ত কঠিন। মূল্যস্ফীতি বিবেচনায় বেতন কাঠামো হালনাগাদ করা জরুরি।</p><p>তারা দ্রুত সময়ের মধ্যে শিক্ষকদের বেতন কাঠামো পুনর্বিবেচনার জন্য সরকারের প্রতি আহ্বান জানিয়েছেন।</p>',
                'category_id' => $categories->where('slug', 'teacher-rights')->first()?->id ?? $categories->first()->id,
                'staff_id' => $staff->skip(4)->first()->id,
                'reading_time_minutes' => 4,
                'is_featured' => true,
            ],
            [
                'title_bn' => 'বিদ্যালয়ে নতুন পাঠ্যক্রম চালু',
                'excerpt_bn' => 'আগামী শিক্ষাবর্ষ থেকে বিদ্যালয়ে নতুন পাঠ্যক্রম চালু হচ্ছে। এতে ব্যবহারিক শিক্ষার ওপর জোর দেওয়া হয়েছে।',
                'body_bn' => '<p>আগামী শিক্ষাবর্ষ থেকে দেশের সব বিদ্যালয়ে নতুন পাঠ্যক্রম চালু করা হবে। এই পাঠ্যক্রমে ব্যবহারিক শিক্ষা ও দক্ষতা উন্নয়নের ওপর জোর দেওয়া হয়েছে।</p><p>জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (এনসিটিবি) ইতিমধ্যে নতুন পাঠ্যক্রমের খসড়া চূড়ান্ত করেছে। বিশেষজ্ঞদের মতামত নিয়ে এতে কিছু পরিবর্তন আনা হয়েছে।</p><p>নতুন পাঠ্যক্রমে শিক্ষার্থীদের পড়া, লেখা, গণনা ও বিশ্লেষণ ক্ষমতা বিকাশের ওপর বিশেষ গুরুত্ব দেওয়া হয়েছে।</p>',
                'category_id' => $categories->where('slug', 'education-policy')->first()?->id ?? $categories->first()->id,
                'staff_id' => $staff->skip(5)->first()->id,
                'reading_time_minutes' => 3,
                'is_editor_pick' => true,
            ],
        ];

        foreach ($articles as $i => $data) {
            $slug = Str::slug($data['title_bn']) . '-' . ($i + 1);
            if (Article::where('slug', $slug)->exists()) {
                continue;
            }
            $article = Article::create(array_merge($data, [
                'slug' => $slug,
                'status' => 'published',
                'featured_image' => null,
                'featured_image_caption' => null,
                'photo_credit' => null,
                'is_breaking' => $data['is_breaking'] ?? false,
                'is_featured' => $data['is_featured'] ?? false,
                'is_editor_pick' => $data['is_editor_pick'] ?? false,
                'is_slider' => $i < 4,
                'slider_order' => $i < 4 ? $i : 0,
                'published_at' => now()->subHours($i * 4),
                'author_id' => $admin?->id ?? 1,
                'meta_title' => $data['title_bn'],
                'meta_description' => $data['excerpt_bn'],
                'focus_keywords' => 'শিক্ষা, শিক্ষক, বিদ্যালয়',
                'indexable' => true,
                'created_at' => now()->subDays($i + 1),
                'updated_at' => now()->subHours($i * 4),
            ]));

            $this->command->info("  Created article: {$data['title_bn']}");
        }

        $this->command->info(count($articles) . ' sample articles seeded successfully.');
    }
}
