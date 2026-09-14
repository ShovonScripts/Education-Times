<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $staff = Staff::all();

        if ($categories->isEmpty() || $staff->isEmpty()) {
            $this->command->warn('Categories or Staff empty — skipping NewsSeeder.');
            return;
        }

        $admin = User::where('is_admin', true)->first();

        $articles = [
            [
                'title_bn' => 'জাতীয় শিক্ষা নীতি ২০২৬ প্রণয়ন শেষ পর্যায়ে',
                'title_en' => 'National Education Policy 2026 in Final Stage',
                'excerpt_bn' => 'দীর্ঘ দুই বছরের গবেষণা ও আলোচনার পর জাতীয় শিক্ষা নীতি ২০২৬ প্রণয়ন শেষ পর্যায়ে পৌঁছেছে।',
                'body_bn' => '<p>দীর্ঘ দুই বছরের গবেষণা ও আলোচনার পর জাতীয় শিক্ষা নীতি ২০২৬ প্রণয়ন শেষ পর্যায়ে পৌঁছেছে। শিক্ষা মন্ত্রণালয় সূত্রে জানা গেছে, এই সপ্তাহের মধ্যে নীতিমালার খসড়া চূড়ান্ত হবে।</p><p>নতুন নীতিতে প্রাথমিক থেকে উচ্চশিক্ষা পর্যন্ত সমন্বিত একটি কাঠামো প্রস্তাব করা হয়েছে। বিশেষত ব্যবহারিক দক্ষতা উন্নয়ন, ডিজিটাল শিক্ষা ও গবেষণার ওপর বিশেষ গুরুত্ব দেওয়া হয়েছে।</p><p>শিক্ষাবিদরা মনে করেন, এই নীতি দেশের শিক্ষাব্যবস্থাকে আরও সুশৃঙ্খল ও ফলপ্রসূ করতে সাহায্য করবে।</p><p>আরও জানুন এই ভিডিওতে:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'category_id' => $categories->where('slug', 'education-policy')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->first()->id],
                'reading_time_minutes' => 5,
                'is_breaking' => true,
                'is_featured' => true,
                'is_editor_pick' => true,
                'is_slider' => true,
                'slider_order' => 1,
            ],
            [
                'title_bn' => 'এসএসসি পরীক্ষায় রেকর্ড উত্তীর্ণের হার',
                'title_en' => 'Record Pass Rate in SSC Examination',
                'excerpt_bn' => 'এই বছরের এসএসসি পরীক্ষায় উত্তীর্ণের হার সর্বকালের সর্বোচ্চ রেকর্ড গড়েছে। মোট পরীক্ষার্থীর ৮৭.৫ শতাংশ উত্তীর্ণ হয়েছে।',
                'body_bn' => '<p>এই বছরের এসএসসি পরীক্ষায় উত্তীর্ণের হার সর্বকালের সর্বোচ্চ রেকর্ড গড়েছে। শিক্ষা বোর্ডের তথ্য অনুযায়ী, মোট পরীক্ষার্থীর ৮৭.৫ শতাংশ উত্তীর্ণ হয়েছে, যা গত বছরের তুলনায় ৪.২ শতাংশ বেশি।</p><p>বিজ্ঞান বিভাগে উত্তীর্ণের হার ছিল ৯১.৩ শতাংশ, যা বাণিজ্য বিভাগের ৮৫.৭ শতাংশ ও মানবিক বিভাগের ৮৬.১ শতাংশের চেয়ে বেশি।</p><p>শিক্ষা মন্ত্রী বলেছেন, এই সাফল্য শিক্ষকদের অক্লান্ত পরিশ্রমের ফল। পূর্ণ ভিডিও দেখুন:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'category_id' => $categories->where('slug', 'exam-results')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(1)->first()->id],
                'reading_time_minutes' => 4,
                'is_breaking' => true,
                'is_featured' => true,
                'is_slider' => true,
                'slider_order' => 2,
            ],
            [
                'title_bn' => 'প্রাথমিক শিক্ষকদের জন্য বিশেষ প্রশিক্ষণ কর্মসূচি ঘোষণা',
                'title_en' => 'Special Training Program Announced for Primary Teachers',
                'excerpt_bn' => 'সরকার প্রাথমিক শিক্ষকদের জন্য আধুনিক প্রশিক্ষণ কর্মসূচি ঘোষণা করেছে। এতে ৫০ হাজার শিক্ষক প্রশিক্ষণ পাবেন।',
                'body_bn' => '<p>সরকার প্রাথমিক শিক্ষকদের জন্য আধুনিক প্রশিক্ষণ কর্মসূচি ঘোষণা করেছে। এই কর্মসূচির আওতায় দেশের ৫০ হাজার প্রাথমিক শিক্ষক আধুনিক প্রযুক্তি ও পাঠদান কৌশলে প্রশিক্ষণ পাবেন।</p><p>প্রশিক্ষণের মধ্যে থাকবে ডিজিটাল ক্লাসরুম ব্যবহার, শিশু মনোবিজ্ঞান, ও মূল্যায়ন পদ্ধতি। প্রতিটি জেলায় একটি প্রশিক্ষণ কেন্দ্র স্থাপন করা হবে।</p><p>শিক্ষক নেতারা এই উদ্যোগকে স্বাগত জানিয়েছেন। ভিডিওতে দেখুন বিস্তারিত:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'category_id' => $categories->where('slug', 'training')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(2)->first()->id],
                'reading_time_minutes' => 4,
                'is_featured' => true,
                'is_editor_pick' => true,
                'is_slider' => true,
                'slider_order' => 3,
            ],
            [
                'title_bn' => 'বিশ্ববিদ্যালয়ে ভর্তি পরীক্ষায় অনলাইন আবেদন বাধ্যতামূলক',
                'title_en' => 'Online Application Mandatory for University Admission',
                'excerpt_bn' => 'আগামী শিক্ষাবর্ষ থেকে সব বিশ্ববিদ্যালয়ে ভর্তি পরীক্ষার আবেদন অনলাইনে জমা দেওয়া বাধ্যতামূলক হবে।',
                'body_bn' => '<p>আগামী শিক্ষাবর্ষ থেকে সব বিশ্ববিদ্যালয়ে ভর্তি পরীক্ষার আবেদন অনলাইনে জমা দেওয়া বাধ্যতামূলক হবে। বিশ্ববিদ্যালয় বোর্ড এই সিদ্ধান্ত নিয়েছে।</p><p>নতুন ব্যবস্থায় প্রার্থীরা ঘরে বসেই তাদের আবেদন জমা দিতে পারবেন। এতে কাগজপত্রের ব্যবহার কমবে এবং প্রক্রিয়াটি স্বচ্ছ হবে।</p><p>বিশ্ববিদ্যালয় কর্তৃপক্ষ জানিয়েছে, প্রযুক্তিগত সমস্যা কাটিয়ে ওঠার জন্য হেল্পলাইন সেবা চালু করা হয়েছে।</p><p>আরও জানুন ভিডিওতে:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category_id' => $categories->where('slug', 'universities')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(3)->first()->id],
                'reading_time_minutes' => 3,
                'is_breaking' => true,
                'is_editor_pick' => true,
            ],
            [
                'title_bn' => 'কারিগরি শিক্ষায় নতুন কোর্স চালু',
                'title_en' => 'New Courses Launched in Technical Education',
                'excerpt_bn' => 'কারিগরি শিক্ষা বোর্ড আর্টিফিশিয়াল ইন্টেলিজেন্স ও সাইবার নিরাপত্তাসহ নতুন কোর্স চালু করেছে।',
                'body_bn' => '<p>কারিগরি শিক্ষা বোর্ড আর্টিফিশিয়াল ইন্টেলিজেন্স, সাইবার নিরাপত্তা ও গ্রিন টেকনোলজিসহ নতুন কোর্স চালু করেছে। এই কোর্সগুলো আগামী শিক্ষাবর্ষ থেকে শুরু হবে।</p><p>নতুন কোর্সগুলো বাজার চাহিদা অনুযায়ী নকশা করা হয়েছে। শিক্ষার্থীরা এই কোর্স শেষে সরাসরি কাজের সুযোগ পাবেন।</p><p>কারিগরি শিক্ষা প্রতিমন্ত্রী বলেছেন, এই উদ্যোগ দেশের তরুণদের কর্মসংস্থানের সুযোগ বাড়াবে।</p><p>ভিডিওতে দেখুন বিস্তারিত:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'category_id' => $categories->where('slug', 'technical')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(4)->first()->id],
                'reading_time_minutes' => 3,
                'is_slider' => true,
                'slider_order' => 4,
            ],
            [
                'title_bn' => 'মাদ্রাসা শিক্ষায় আধুনিকায়নে নতুন ধাপ',
                'title_en' => 'New Step in Modernizing Madrasah Education',
                'excerpt_bn' => 'মাদ্রাসা শিক্ষায় আধুনিকায়নে সরকার নতুন ধাপ এগিয়ে দিয়েছে। কুরআন ও আরবি ছাড়াও বিজ্ঞান ও প্রযুক্তি শিক্ষা যুক্ত হবে।',
                'body_bn' => '<p>মাদ্রাসা শিক্ষায় আধুনিকায়নে সরকার নতুন ধাপ এগিয়ে দিয়েছে। আগামী শিক্ষাবর্ষ থেকে কুরআন ও আরবি ছাড়াও বিজ্ঞান, প্রযুক্তি ও ইংরেজি বিষয়ে আরও বেশি জোর দেওয়া হবে।</p><p>মাদ্রাসা শিক্ষা বোর্ডের চেয়ারম্যান বলেছেন, এই পরিবর্তন মাদ্রাসা শিক্ষার্থীদের আধুনিক বিশ্বের সাথে সামঞ্জস্য রাখতে সাহায্য করবে।</p><p>ইতিমধ্যে বেশ কিছু মাদ্রাসায় পাইলট প্রকল্প হিসেবে এই কার্যক্রম শুরু করা হয়েছে।</p><p>ভিডিওতে দেখুন বিস্তারিত:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'category_id' => $categories->where('slug', 'madrasah')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(5)->first()->id],
                'reading_time_minutes' => 4,
                'is_editor_pick' => true,
            ],
            [
                'title_bn' => 'শিক্ষার্থীদের জন্য বিনামূল্যে ইন্টারনেট সুবিধা ঘোষণা',
                'title_en' => 'Free Internet Facility Announced for Students',
                'excerpt_bn' => 'সরকার সব সরকারি বিদ্যালয়ের শিক্ষার্থীদের জন্য বিনামূল্যে ইন্টারনেট সুবিধা ঘোষণা করেছে।',
                'body_bn' => '<p>সরকার সব সরকারি বিদ্যালয়ের শিক্ষার্থীদের জন্য বিনামূল্যে ইন্টারনেট সুবিধা ঘোষণা করেছে। এই উদ্যোগের আওতায় দেশের ৩০,০০০ সরকারি বিদ্যালয়ে ব্রডব্যান্ড সংযোগ দেওয়া হবে।</p><p>তরুণ প্রজন্মের ডিজিটাল সক্ষমতা বাড়ানো এই উদ্যোগের মূল লক্ষ্য। শিক্ষার্থীরা অনলাইনে পড়াশোনার রিসোর্স ব্যবহার করতে পারবেন।</p><p>তথ্য ও যোগাযোগ প্রযুক্তি প্রতিমন্ত্রী বলেছেন, এটি ডিজিটাল বাংলাদেশ গড়তে গুরুত্বপূর্ণ পদক্ষেপ।</p><p>ভিডিওতে দেখুন:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'category_id' => $categories->where('slug', 'national')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->first()->id],
                'reading_time_minutes' => 3,
                'is_featured' => true,
            ],
            [
                'title_bn' => 'বিশ্ববিদ্যালয়ে গবেষণা তহবিল বৃদ্ধি',
                'title_en' => 'University Research Fund Increased',
                'excerpt_bn' => 'সরকার সব সরকারি বিশ্ববিদ্যালয়ের গবেষণা তহবিল ৫০ শতাংশ বৃদ্ধি করেছে।',
                'body_bn' => '<p>সরকার সব সরকারি বিশ্ববিদ্যালয়ের গবেষণা তহবিল ৫০ শতাংশ বৃদ্ধি করেছে। এই সিদ্ধান্ত গুরুত্বপূর্ণ, কারণ গবেষণার জন্য তহবিলের অভাব দীর্ঘদিন ধরে সমস্যা ছিল।</p><p>নতুন তহবিলে প্রতিটি বিশ্ববিদ্যালয়ের জন্য সর্বোচ্চ ৫ কোটি টাকা পর্যন্ত গবেষণা অনুদান পাওয়া যাবে।</p><p>উপাচার্যরা এই সিদ্ধান্তকে স্বাগত জানিয়েছেন এবং বলেছেন, এতে দেশের গবেষণা খাত সমৃদ্ধ হবে।</p><p>ভিডিওতে দেখুন:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'category_id' => $categories->where('slug', 'universities')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(1)->first()->id],
                'reading_time_minutes' => 4,
                'is_editor_pick' => true,
            ],
            [
                'title_bn' => 'আন্তর্জাতিক শিক্ষা সম্মেলনে বাংলাদেশের অংশগ্রহণ',
                'title_en' => 'Bangladesh Participates in International Education Conference',
                'excerpt_bn' => 'জেনেভায় অনুষ্ঠিত আন্তর্জাতিক শিক্ষা সম্মেলনে বাংলাদেশের প্রতিনিধি দল অংশগ্রহণ করেছে।',
                'body_bn' => '<p>জেনেভায় অনুষ্ঠিত আন্তর্জাতিক শিক্ষা সম্মেলনে বাংলাদেশের প্রতিনিধি দল অংশগ্রহণ করেছে। সম্মেলনে বাংলাদেশের শিক্ষা খাতের অগ্রগতি তুলে ধরা হয়েছে।</p><p>প্রতিনিধি দলের নেতৃত্বে ছিলেন শিক্ষা মন্ত্রী। তিনি বলেছেন, বাংলাদেশ শিক্ষায় যে অগ্রগতি করেছে, তা আন্তর্জাতিক জুড়ে প্রশংসিত হয়েছে।</p><p>সম্মেলনে বাংলাদেশের ডিজিটাল শিক্ষা কার্যক্রম বিশেষভাবে প্রশংসিত হয়েছে।</p><p>ভিডিওতে দেখুন সম্মেলনের আলোচনা:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category_id' => $categories->where('slug', 'international')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(2)->first()->id],
                'reading_time_minutes' => 5,
                'is_featured' => true,
            ],
            [
                'title_bn' => 'শিক্ষার্থীদের ক্যারিয়ার কাউন্সেলিং সেবা চালু',
                'title_en' => 'Career Counseling Service Launched for Students',
                'excerpt_bn' => 'সব বিশ্ববিদ্যালয়ে শিক্ষার্থীদের জন্য ক্যারিয়ার কাউন্সেলিং সেবা চালু করা হয়েছে।',
                'body_bn' => '<p>সব সরকারি বিশ্ববিদ্যালয়ে শিক্ষার্থীদের জন্য ক্যারিয়ার কাউন্সেলিং সেবা চালু করা হয়েছে। এই সেবায় অভিজ্ঞ কাউন্সেলররা শিক্ষার্থীদের পেশাগত পথ নির্বাচনে সহায়তা করবেন।</p><p>কাউন্সেলিং সেবায় থাকবে পেশা নির্বাচন, রিজুমে তৈরি, সাক্ষাৎকারের প্রস্তুতি ও কর্মসংস্থানের সুযোগ সম্পর্কে তথ্য।</p><p>শিক্ষা মন্ত্রণালয়ের একজন কর্মকর্তা বলেছেন, এই সেবা শিক্ষার্থীদের ভবিষ্যৎ পরিকল্পনায় সাহায্য করবে।</p><p>ভিডিওতে দেখুন কাউন্সেলিং সেবার বিস্তারিত:</p>',
                'video_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'category_id' => $categories->where('slug', 'career')->first()?->id ?? $categories->first()->id,
                'staff_ids' => [$staff->skip(3)->first()->id],
                'reading_time_minutes' => 3,
                'is_breaking' => true,
                'is_slider' => true,
                'slider_order' => 5,
            ],
        ];

        $index = Article::max('id') ?? 0;

        foreach ($articles as $i => $data) {
            $slug = Str::slug($data['title_bn']) . '-news-' . ($index + $i + 1);
            if (Article::where('slug', $slug)->exists()) {
                $this->command->warn("  Skipped (slug exists): {$data['title_bn']}");
                continue;
            }

            $staffIds = $data['staff_ids'] ?? [];
            unset($data['staff_ids']);
            $article = Article::create(array_merge($data, [
                'slug' => $slug,
                'status' => 'published',
                'featured_image' => null,
                'featured_image_caption' => null,
                'photo_credit' => null,
                'is_breaking' => $data['is_breaking'] ?? false,
                'is_featured' => $data['is_featured'] ?? false,
                'is_editor_pick' => $data['is_editor_pick'] ?? false,
                'is_slider' => $data['is_slider'] ?? false,
                'slider_order' => $data['slider_order'] ?? 0,
                'published_at' => now()->subHours($i * 3 + 1),
                'author_id' => $admin?->id ?? 1,
                'meta_title' => $data['title_bn'],
                'meta_description' => $data['excerpt_bn'],
                'focus_keywords' => 'শিক্ষা, বিশ্ববিদ্যালয়, প্রাথমিক শিক্ষা, কারিগরি শিক্ষা',
                'indexable' => true,
                'created_at' => now()->subDays($i + 2),
                'updated_at' => now()->subHours($i * 3 + 1),
            ]));

            if (!empty($staffIds)) {
                $article->staffs()->sync($staffIds);
            }

            $this->command->info("  Created news article: {$data['title_bn']} ({$data['video_url']})");
        }

        $this->command->info(count($articles) . ' news articles seeded successfully.');
    }
}
