<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\ArchiveDocument;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\ArticleTag;
use App\Models\Category;
use App\Models\Comment;
use App\Models\District;
use App\Models\Redirect;
use App\Models\SavedArticle;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAllUserTypes();
        $this->seedComments();
        $this->seedArticleTags();
        $this->seedArticleLikes();
        $this->seedSavedArticles();
        $this->seedArchiveDocuments();
        $this->seedRedirects();
        $this->seedSettings();
        $this->seedContacts();
        $this->seedAdvertisements();
    }

    public function seedAllUserTypes(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@educationtimes.com'],
            [
                'name' => 'এডমিন',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_editor' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("Admin: admin@educationtimes.com / admin123");

        $editor = User::updateOrCreate(
            ['email' => 'reporter@educationtimes.com'],
            [
                'name' => 'রিপোর্টার',
                'password' => Hash::make('reporter123'),
                'is_admin' => false,
                'is_editor' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("Editor/Reporter: reporter@educationtimes.com / reporter123");

        $user = User::updateOrCreate(
            ['email' => 'user@educationtimes.com'],
            [
                'name' => 'সাধারণ ইউজার',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'is_editor' => false,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("Regular User: user@educationtimes.com / user123");
    }

    public function seedComments(): void
    {
        $articles = Article::where('status', 'published')->take(10)->get();
        $users = User::all();

        foreach ($articles as $article) {
            foreach ($users as $user) {
                Comment::firstOrCreate(
                    ['article_id' => $article->id, 'user_id' => $user->id, 'body' => "{$user->name} এর মন্তব্য: চমৎকার একটি সংবাদ। শিক্ষাক্ষেত্রে এমন উদ্যোগ সত্যিই প্রশংসনীয়।"],
                    ['status' => ['pending', 'approved', 'approved'][array_rand(['pending', 'approved', 'approved'])]]
                );
            }
        }
        $this->command->info('Comments seeded.');

        $parentComments = Comment::whereNull('parent_id')->take(5)->get();
        foreach ($parentComments as $parent) {
            Comment::firstOrCreate(
                ['article_id' => $parent->article_id, 'user_id' => $users->random()->id, 'parent_id' => $parent->id, 'body' => 'আমি একমত। এই বিষয়ে আরও আলোচনা হওয়া দরকার।'],
                ['status' => 'approved']
            );
        }
        $this->command->info('Reply comments seeded.');
    }

    public function seedArticleTags(): void
    {
        $articles = Article::where('status', 'published')->take(15)->get();
        $tagPool = ['শিক্ষা', 'প্রাথমিক', 'সরকার', 'নীতিমালা', 'ডিজিটাল', 'প্রযুক্তি', 'শিক্ষক', 'বিদ্যালয়', 'পরীক্ষা', 'ফলাফল', 'নিয়োগ', 'বদলি', 'আন্তর্জাতিক', 'গবেষণা', 'উন্নয়ন'];

        foreach ($articles as $article) {
            $tags = array_rand(array_flip($tagPool), rand(2, 4));
            foreach ((array)$tags as $tag) {
                ArticleTag::firstOrCreate(
                    ['article_id' => $article->id, 'tag' => $tag]
                );
            }
        }
        $this->command->info('Article tags seeded.');
    }

    public function seedArticleLikes(): void
    {
        $articles = Article::where('status', 'published')->take(20)->get();
        $users = User::all();

        foreach ($articles as $article) {
            foreach ($users as $user) {
                ArticleLike::firstOrCreate(
                    ['article_id' => $article->id, 'user_id' => $user->id]
                );
            }
        }
        $this->command->info('Article likes seeded.');
    }

    public function seedSavedArticles(): void
    {
        $articles = Article::where('status', 'published')->take(10)->get();
        $users = User::all();

        foreach ($users as $user) {
            foreach ($articles->random(min(5, $articles->count())) as $article) {
                SavedArticle::firstOrCreate(
                    ['user_id' => $user->id, 'article_id' => $article->id]
                );
            }
        }
        $this->command->info('Saved articles seeded.');
    }

    public function seedArchiveDocuments(): void
    {
        $adminId = User::where('is_admin', true)->first()?->id ?? 1;
        $years = [2024, 2025, 2026];
        $subcategories = ['সার্কুলার', 'বিজ্ঞপ্তি', 'বার্ষিক প্রতিবেদন', 'নীতিমালা'];

        foreach ($years as $year) {
            foreach ($subcategories as $sub) {
                ArchiveDocument::firstOrCreate(
                    ['title_bn' => "{$year} সালের {$sub}", 'year' => $year, 'subcategory' => $sub],
                    [
                        'slug' => Str::slug("{$year}-{$sub}"),
                        'description_bn' => "{$year} সালের {$sub} সংক্রান্ত ডকুমেন্ট",
                        'file_path' => 'documents/sample.pdf',
                        'file_type' => 'pdf',
                        'file_size' => rand(100, 5000),
                        'is_published' => true,
                        'uploaded_by' => $adminId,
                    ]
                );
            }
        }
        $this->command->info('Archive documents seeded.');
    }

    public function seedRedirects(): void
    {
        $redirects = [
            ['old_url' => '/old-news', 'new_url' => '/news/breaking-story', 'status_code' => 301],
            ['old_url' => '/previous-site', 'new_url' => '/', 'status_code' => 301],
            ['old_url' => '/old-category', 'new_url' => '/category/national', 'status_code' => 301],
        ];

        foreach ($redirects as $r) {
            Redirect::firstOrCreate(
                ['old_url' => $r['old_url']],
                ['new_url' => $r['new_url'], 'status_code' => $r['status_code'], 'is_active' => true]
            );
        }
        $this->command->info('Redirects seeded.');
    }

    public function seedContacts(): void
    {
        if (!class_exists(\App\Models\Contact::class) || !\Illuminate\Support\Facades\Schema::hasTable('contacts')) {
            return;
        }

        $contacts = [
            [
                'name' => 'রফিকুল ইসলাম',
                'email' => 'rafiqul@example.com',
                'phone' => '01711-111111',
                'subject' => 'সাইট সম্পর্কে মতামত',
                'message' => 'আপনাদের সাইটটি খুবই ভালো লাগছে। শুধু মোবাইল ভার্সনে ফন্ট সাইজ একটু বড় করলে ভালো হয়।',
                'read_at' => now(),
                'replied_at' => now()->subHours(2),
                'reply' => 'ধন্যবাদ আপনার মতামতের জন্য। আমরা মোবাইল ভার্সনের ফন্ট সাইজ নিয়ে কাজ করছি। শীঘ্রই আপডেট আসবে।',
            ],
            [
                'name' => 'শামীমা আক্তার',
                'email' => 'shamima@example.com',
                'phone' => '01722-222222',
                'subject' => 'নিউজ টিপ অফার',
                'message' => 'আমাদের স্কুলে একটি বিশেষ ইভেন্ট হয়েছে যা আপনি কভার করতে পারেন। বিস্তারিত জানাতে চাইলে আমার সাথে যোগাযোগ করুন।',
                'read_at' => now(),
                'replied_at' => null,
                'reply' => null,
            ],
            [
                'name' => 'আব্দুল করিম',
                'email' => 'karim@example.com',
                'subject' => 'বিজ্ঞাপনের বিষয়ে',
                'message' => 'আমরা আমাদের শিক্ষা বিষয়ক পণ্যের বিজ্ঞাপন দিতে চাই। দয়া করে বিজ্ঞাপনের রেট ও প্রক্রিয়া জানাবেন।',
                'read_at' => null,
                'replied_at' => null,
                'reply' => null,
            ],
        ];

        foreach ($contacts as $c) {
            \App\Models\Contact::firstOrCreate(
                ['email' => $c['email'], 'subject' => $c['subject']],
                $c
            );
        }
        $this->command->info('Contacts seeded.');
    }

    public function seedSettings(): void
    {
        $settings = [
            'site_name' => 'Education Times',
            'site_tagline' => 'শিক্ষার সংবাদ, শিক্ষার সাথে',
            'site_logo' => null,
            'site_favicon' => null,
            'social_facebook' => 'https://facebook.com/EducationTimesBD',
            'social_twitter' => 'https://twitter.com/EducationTimesBD',
            'social_youtube' => 'https://youtube.com/@EducationTimesBD',
            'editor_name' => 'এস এম শাহজাহান',
            'editor_designation' => 'সম্পাদক',
            'publisher_name' => 'এডুকেশন টাইমস মিডিয়া লি.',
            'footer_tagline' => 'এডুকেশন টাইমস — শিক্ষার সব খবর এক নজরে',
            'hero_breaking_label' => 'ব্রেকিং নিউজ',
            'newsletter_title' => 'নিউজলেটারে যোগ দিন',
            'newsletter_pitch' => 'সর্বশেষ শিক্ষা সংবাদ সরাসরি আপনার ইমেইলে পান। স্প্যাম নয়, যেকোনো সময় আনসাবস্ক্রাইব করতে পারবেন।',
            'footer_text' => 'এডুকেশন টাইমস - শিক্ষার সব খবর এক নজরে',
            'footer_email' => 'info@educationtimes.com',
            'contact_email' => 'contact@educationtimes.com',
            'contact_phone' => '+880-2-1234567',
            'contact_address' => 'ঢাকা, বাংলাদেশ',
            'about_text' => 'Education Times বাংলাদেশের শিক্ষা সংবাদ পরিবেশনে নিবেদিত একটি অনলাইন পোর্টাল।',
            'maintenance_mode' => '0',
            'meta_keywords' => 'শিক্ষা, এডুকেশন টাইমস, শিক্ষা সংবাদ, বাংলাদেশ',
            'google_analytics_id' => null,
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $pageContent = [
            'page_privacy' => '<h2>ভূমিকা</h2>
<p>Education Times (এডুকেশন টাইমস) আপনার গোপনীয়তাকে গুরুত্ব সহকারে বিবেচনা করে। এই প্রাইভেসি পলিসি ব্যাখ্যা করে যে আমরা কীভাবে আপনার ব্যক্তিগত তথ্য সংগ্রহ, ব্যবহার, সংরক্ষণ এবং সুরক্ষিত করি।</p>

<h2>তথ্য সংগ্রহ</h2>
<p>আমরা নিম্নলিখিত ধরণের তথ্য সংগ্রহ করতে পারি:</p>
<ul>
<li><strong>ব্যক্তিগত তথ্য:</strong> নাম, ইমেইল ঠিকানা — যা আপনি রেজিস্ট্রেশন, কমেন্ট বা কন্টাক্ট ফর্মের মাধ্যমে প্রদান করেন।</li>
<li><strong>স্বয়ংক্রিয় তথ্য:</strong> আইপি ঠিকানা, ব্রাউজার টাইপ, অপারেটিং সিস্টেম, ভিজিট করা পৃষ্ঠা এবং ভিজিটের সময় — যা কুকি এবং অন্যান্য ট্র্যাকিং প্রযুক্তির মাধ্যমে সংগ্রহ করা হয়।</li>
<li><strong>পছন্দের তথ্য:</strong> আপনি যে আর্টিকেল পড়েন, সংরক্ষণ করেন বা লাইক করেন সে সম্পর্কিত তথ্য।</li>
</ul>

<h2>তথ্য ব্যবহার</h2>
<p>আমরা সংগৃহীত তথ্য নিম্নলিখিত উদ্দেশ্যে ব্যবহার করি:</p>
<ul>
<li>আমাদের ওয়েবসাইটের সেবা প্রদান এবং উন্নত করা</li>
<li>ব্যবহারকারীর অভিজ্ঞতা ব্যক্তিগতকৃত করা</li>
<li>কন্টেন্ট সুপারিশ প্রদান করা</li>
<li>আমাদের সেবা সম্পর্কে যোগাযোগ করা</li>
<li>ওয়েবসাইটের ট্রাফিক বিশ্লেষণ এবং উন্নয়ন</li>
<li>আইনগত বাধ্যবাধকতা মেনে চলা</li>
</ul>

<h2>তথ্য সংরক্ষণ ও সুরক্ষা</h2>
<p>আমরা আপনার ব্যক্তিগত তথ্য সুরক্ষিত রাখতে যথাযথ নিরাপত্তা ব্যবস্থা গ্রহণ করি। আপনার তথ্য অননুমোদিত অ্যাক্সেস, পরিবর্তন, প্রকাশ বা ধ্বংস থেকে রক্ষা করতে আমরা শিল্প-মান অনুসরণ করি। আমরা আপনার তথ্য শুধুমাত্র প্রয়োজনীয় সময়ের জন্য সংরক্ষণ করি।</p>

<h2>কুকি</h2>
<p>আমাদের ওয়েবসাইট ব্যবহারকারীর অভিজ্ঞতা উন্নত করতে কুকি ব্যবহার করে। আপনি আপনার ব্রাউজার সেটিংসের মাধ্যমে কুকি নিয়ন্ত্রণ করতে পারেন। কুকি নিষ্ক্রিয় করলে আমাদের ওয়েবসাইটের কিছু বৈশিষ্ট্য সঠিকভাবে কাজ নাও করতে পারে।</p>

<h2>তৃতীয় পক্ষের লিংক</h2>
<p>আমাদের ওয়েবসাইটে তৃতীয় পক্ষের ওয়েবসাইটের লিংক থাকতে পারে। এই ওয়েবসাইটগুলোর নিজস্ব প্রাইভেসি পলিসি রয়েছে এবং তাদের কার্যকলাপের জন্য আমরা দায়ী নই। আমরা আপনাকে উৎসাহিত করি তাদের প্রাইভেসি পলিসি পড়তে।</p>

<h2>তথ্য ভাগ করে নেওয়া</h2>
<p>আমরা আইন দ্বারা প্রয়োজন না হলে বা আপনার স্পষ্ট সম্মতি ছাড়া আপনার ব্যক্তিগত তথ্য তৃতীয় পক্ষের সাথে ভাগ করে নিই না। আমরা আইন প্রয়োগকারী সংস্থা বা আইনগতভাবে অনুমোদিত অন্যান্য পক্ষের কাছে তথ্য প্রকাশ করতে পারি যদি আইন দ্বারা প্রয়োজন হয়।</p>

<h2>আপনার অধিকার</h2>
<p>আপনার নিম্নলিখিত অধিকার রয়েছে:</p>
<ul>
<li>আপনার ব্যক্তিগত তথ্য অ্যাক্সেস করার অধিকার</li>
<li>আপনার তথ্য সংশোধন বা আপডেট করার অধিকার</li>
<li>আপনার তথ্য মুছে ফেলার অনুরোধ করার অধিকার</li>
<li>আপনার তথ্য প্রক্রিয়াকরণে আপত্তি করার অধিকার</li>
<li>আপনার সম্মতি প্রত্যাহার করার অধিকার</li>
</ul>

<h2>যোগাযোগ</h2>
<p>এই প্রাইভেসি পলিসি সম্পর্কে আপনার কোন প্রশ্ন বা উদ্বেগ থাকলে, অনুগ্রহ করে আমাদের সাথে যোগাযোগ করুন:</p>
<p>ইমেইল: <a href="mailto:info@educationtimes.com">info@educationtimes.com</a></p>

<h2>এই নীতিমালার পরিবর্তন</h2>
<p>আমরা সময়ে সময়ে এই প্রাইভেসি পলিসি আপডেট করতে পারি। কোন পরিবর্তন হলে আমরা এই পৃষ্ঠায় আপডেট করব এবং প্রয়োজন অনুযায়ী আপনাকে অবহিত করব।</p>',

            'page_terms' => '<h2>ভূমিকা</h2>
<p>Education Times (এডুকেশন টাইমস) এ আপনাকে স্বাগতম। এই ওয়েবসাইট ব্যবহার করার মাধ্যমে আপনি নিম্নলিখিত শর্তাবলী ও নিয়মাবলী মেনে চলতে বাধ্য। আপনি যদি এই শর্তাবলীর সাথে একমত না হন, তাহলে অনুগ্রহ করে এই ওয়েবসাইট ব্যবহার করবেন না।</p>

<h2>সেবার বিবরণ</h2>
<p>Education Times শিক্ষা সংক্রান্ত খবর, তথ্য এবং সম্পদ প্রদান করে। আমরা সঠিক এবং আপ-টু-ডেট তথ্য প্রদানের চেষ্টা করি, তবে তথ্যের সম্পূর্ণ নির্ভুলতার গ্যারান্টি দিই না।</p>

<h2>বুদ্ধিবৃত্তিক সম্পত্তি</h2>
<p>এই ওয়েবসাইটের সমস্ত কন্টেন্ট — আর্টিকেল, ছবি, গ্রাফিক্স, লোগো, ভিডিও — Education Times বা তার লাইসেন্সদাতাদের সম্পত্তি। কপিরাইট আইন দ্বারা সুরক্ষিত। আমাদের পূর্বানুমতি ছাড়া কোন কন্টেন্ট পুনরুৎপাদন, বিতরণ বা পরিবর্তন করা যাবে না।</p>

<h2>ব্যবহারকারীর আচরণ</h2>
<p>আপনি এই ওয়েবসাইট ব্যবহার করার সময় নিম্নলিখিত বিষয়গুলি মেনে চলতে বাধ্য:</p>
<ul>
<li>কোন প্রকার অবৈধ বা ক্ষতিকর কার্যকলাপ করবেন না</li>
<li>অন্যের কপিরাইট বা বুদ্ধিবৃত্তিক সম্পত্তি লঙ্ঘন করবেন না</li>
<li>অশ্লীল, অপমানজনক বা ঘৃণামূলক কন্টেন্ট পোস্ট করবেন না</li>
<li>অন্যের গোপনীয়তা লঙ্ঘন করবেন না</li>
<li>স্প্যাম বা ভুয়া তথ্য ছড়াবেন না</li>
<li>ওয়েবসাইটের নিরাপত্তা ব্যবস্থা ভঙ্গের চেষ্টা করবেন না</li>
</ul>

<h2>কমেন্ট ও ইউজার কন্টেন্ট</h2>
<p>ব্যবহারকারীরা আর্টিকেলে কমেন্ট করতে পারেন। আপনি আপনার কমেন্টের জন্য সম্পূর্ণ দায়ী। আমরা যে কোন সময় যে কোন কারণে কমেন্ট সরানোর অধিকার রাখি। কমেন্ট করার মাধ্যমে আপনি আমাদেরকে একটি অ-এক্সক্লুসিভ, রয়্যালটি-মুক্ত লাইসেন্স প্রদান করেন।</p>

<h2>তৃতীয় পক্ষের লিংক</h2>
<p>আমাদের ওয়েবসাইটে তৃতীয় পক্ষের ওয়েবসাইটের লিংক থাকতে পারে। এই লিংকগুলি শুধুমাত্র আপনার সুবিধার জন্য। আমরা এই বাহ্যিক সাইটগুলোর কন্টেন্ট বা নির্ভরযোগ্যতার জন্য দায়ী নই।</p>

<h2>দায় সীমাবদ্ধতা</h2>
<p>Education Times, এর পরিচালক, কর্মচারী বা অংশীদাররা এই ওয়েবসাইট ব্যবহারের ফলে সৃষ্ট কোন প্রত্যক্ষ বা পরোক্ষ ক্ষতির জন্য দায়ী থাকবে না। আমাদের সেবা "যেমন আছে" ভিত্তিতে প্রদান করা হয়।</p>

<h2>অ্যাকাউন্ট টার্মিনেশন</h2>
<p>আমরা যে কোন ব্যবহারকারীর অ্যাকাউন্ট, পূর্ব নোটিশ ছাড়াই, যে কোন কারণে (শর্ত লঙ্ঘন সহ) স্থগিত বা বাতিল করার অধিকার রাখি।</p>

<h2>পরিবর্তন</h2>
<p>আমরা সময়ে সময়ে এই শর্তাবলী পরিবর্তন করতে পারি। পরিবর্তনগুলি এই পৃষ্ঠায় পোস্ট করার সাথে সাথেই কার্যকর হবে। পরিবর্তনের পরে ওয়েবসাইট ব্যবহার অব্যাহত রাখার অর্থ হল আপনি নতুন শর্তাবলী মেনে নিয়েছেন।</p>

<h2>প্রযোজ্য আইন</h2>
<p>এই শর্তাবলী বাংলাদেশের আইন দ্বারা পরিচালিত এবং ব্যাখ্যা করা হবে। কোন বিরোধের ক্ষেত্রে বাংলাদেশের আদালতের এখতিয়ার থাকবে।</p>

<h2>যোগাযোগ</h2>
<p>এই শর্তাবলী সম্পর্কে আপনার কোন প্রশ্ন থাকলে, অনুগ্রহ করে আমাদের সাথে যোগাযোগ করুন:</p>
<p>ইমেইল: <a href="mailto:info@educationtimes.com">info@educationtimes.com</a></p>',
        ];

        foreach ($pageContent as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('Settings seeded.');
    }

    public function seedAdvertisements(): void
    {
        $ads = [
            ['title' => 'হেডার ব্যানার', 'type' => 'banner', 'position' => 'header', 'code' => null, 'image_url' => 'https://placehold.co/728x90/E02020/ffffff?text=Education+Times', 'link_url' => 'https://educationtimes.com', 'width' => 728, 'height' => 90, 'is_active' => true, 'starts_at' => now()->subMonth(), 'ends_at' => now()->addYear()],
            ['title' => 'সাইডবার বিজ্ঞাপন', 'type' => 'banner', 'position' => 'sidebar', 'code' => null, 'image_url' => 'https://placehold.co/300x250/333333/ffffff?text=Advertise+Here', 'link_url' => 'https://educationtimes.com', 'width' => 300, 'height' => 250, 'is_active' => true, 'starts_at' => now()->subMonth(), 'ends_at' => now()->addYear()],
            ['title' => 'আর্টিকেল টপ', 'type' => 'code', 'position' => 'article-top', 'code' => '<div style="padding:10px;background:#f5f5f5;text-align:center">বিজ্ঞাপন</div>', 'image_url' => null, 'link_url' => null, 'width' => null, 'height' => null, 'is_active' => true, 'starts_at' => now()->subMonth(), 'ends_at' => now()->addYear()],
            ['title' => 'আর্টিকেল বটম', 'type' => 'banner', 'position' => 'article-bottom', 'code' => null, 'image_url' => 'https://placehold.co/728x90/E02020/ffffff?text=Read+More', 'link_url' => 'https://educationtimes.com', 'width' => 728, 'height' => 90, 'is_active' => true, 'starts_at' => now()->subMonth(), 'ends_at' => now()->addYear()],
            ['title' => 'ফুটার ব্যানার', 'type' => 'banner', 'position' => 'footer', 'code' => null, 'image_url' => 'https://placehold.co/728x90/0d0d0d/ffffff?text=Education+Times+Footer', 'link_url' => 'https://educationtimes.com', 'width' => 728, 'height' => 90, 'is_active' => true, 'starts_at' => now()->subMonth(), 'ends_at' => now()->addYear()],
            ['title' => 'পপআপ অফার', 'type' => 'code', 'position' => 'popup', 'code' => '<div style="padding:20px;background:#fff;border:2px solid #E02020;border-radius:8px;max-width:400px;margin:auto"><h3 style="color:#E02020">সাথে থাকুন!</h3><p>আমাদের নিউজলেটার সাবস্ক্রাইব করুন</p></div>', 'image_url' => null, 'link_url' => null, 'width' => null, 'height' => null, 'is_active' => true, 'starts_at' => now()->subMonth(), 'ends_at' => now()->addYear()],
        ];

        foreach ($ads as $ad) {
            Advertisement::firstOrCreate(
                ['title' => $ad['title']],
                array_merge($ad, ['order' => rand(1, 10), 'impressions' => rand(100, 5000), 'clicks' => rand(10, 200)])
            );
        }
        $this->command->info('Advertisements seeded.');
    }
}
