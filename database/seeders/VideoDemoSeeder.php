<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $staff = Staff::all();

        if ($categories->isEmpty() || $staff->isEmpty()) {
            $this->command->warn('Categories or Staff empty — skipping VideoDemoSeeder.');
            return;
        }

        $admin = User::where('is_admin', true)->first();

        $articles = [
            [
                'title_bn' => 'শিক্ষকদের জন্য ডিজিটাল ক্লাসরুম প্রশিক্ষণ কর্মশালা অনুষ্ঠিত',
                'excerpt_bn' => 'প্রাথমিক শিক্ষকদের জন্য ডিজিটাল ক্লাসরুম প্রশিক্ষণ কর্মশালা সফলভাবে সম্পন্ন হয়েছে। ভিডিওতে দেখুন কর্মশালার গুরুত্বপূর্ণ মুহূর্তগুলো।',
                'body_bn' => '<p>প্রাথমিক শিক্ষকদের জন্য আয়োজিত ডিজিটাল ক্লাসরুম প্রশিক্ষণ কর্মশালা সফলভাবে সম্পন্ন হয়েছে। এই কর্মশালায় শিক্ষকদের আধুনিক প্রযুক্তি ব্যবহার করে পাঠদানের বিভিন্ন কৌশল শেখানো হয়েছে।</p><p>কর্মশালায় অংশগ্রহণকারী শিক্ষকরা বলেন, এই ধরনের প্রশিক্ষণ তাদের শ্রেণীকক্ষে প্রযুক্তি ব্যবহারে আত্মবিশ্বাসী করে তুলবে।</p><p>নিচের ভিডিওতে কর্মশালার গুরুত্বপূর্ণ মুহূর্তগুলো দেখুন।</p>',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'reading_time_minutes' => 2,
                'is_featured' => true,
            ],
            [
                'title_bn' => 'প্রাথমিক শিক্ষার ভবিষ্যৎ: আধুনিক প্রযুক্তির ব্যবহার',
                'excerpt_bn' => 'প্রাথমিক শিক্ষায় প্রযুক্তির ব্যবহার নিয়ে বিশেষ প্রতিবেদন। আধুনিক শিক্ষাব্যবস্থায় ডিজিটাল মাধ্যমের ভূমিকা নিয়ে আলোচনা।',
                'body_bn' => '<p>প্রাথমিক শিক্ষার ভবিষ্যৎ নির্ভর করছে প্রযুক্তির সঠিক ব্যবহারের ওপর। বর্তমান বিশ্বে ডিজিটাল মাধ্যম শিক্ষাব্যবস্থার একটি অপরিহার্য অংশ হয়ে দাঁড়িয়েছে।</p><p>শিক্ষা বিশেষজ্ঞরা মনে করেন, প্রযুক্তি শিক্ষার্থীদের শেখার আগ্রহ বাড়াতে এবং শিক্ষকদের পাঠদান সহজ করতে গুরুত্বপূর্ণ ভূমিকা রাখছে।</p><p>আসুন দেখি আধুনিক প্রযুক্তি কীভাবে প্রাথমিক শিক্ষাকে পরিবর্তন করছে।</p>',
                'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'reading_time_minutes' => 2,
                'is_editor_pick' => true,
            ],
            [
                'title_bn' => 'শিক্ষার্থীদের জন্য মজার গণিত প্রতিযোগিতা',
                'excerpt_bn' => 'প্রাথমিক বিদ্যালয়ের শিক্ষার্থীদের জন্য আয়োজিত গণিত প্রতিযোগিতার উল্লেখযোগ্য মুহূর্তগুলো ভিডিওতে তুলে ধরা হলো।',
                'body_bn' => '<p>প্রাথমিক বিদ্যালয়ের শিক্ষার্থীদের জন্য আয়োজিত বার্ষিক গণিত প্রতিযোগিতা অনুষ্ঠিত হয়েছে। এতে বিভিন্ন বিদ্যালয়ের শতাধিক শিক্ষার্থী অংশগ্রহণ করে।</p><p>প্রতিযোগিতায় শিক্ষার্থীরা তাদের গণিত দক্ষতা প্রদর্শনের সুযোগ পায়। বিজয়ীদের হাতে পুরস্কার তুলে দেওয়া হয়।</p><p>নিচের ভিডিওতে প্রতিযোগিতার মজার মুহূর্তগুলো দেখুন।</p>',
                'video_url' => 'https://youtu.be/9bZkp7q19f0',
                'reading_time_minutes' => 3,
                'is_slider' => true,
                'slider_order' => 4,
            ],
            [
                'title_bn' => 'শিক্ষক-অভিভাবক সম্মেলনে গুরুত্বপূর্ণ সিদ্ধান্ত',
                'excerpt_bn' => 'স্কুলের শিক্ষক-অভিভাবক সম্মেলনে শিক্ষার্থীদের উন্নয়নে বেশ কিছু গুরুত্বপূর্ণ সিদ্ধান্ত গৃহীত হয়েছে।',
                'body_bn' => '<p>প্রাথমিক বিদ্যালয়ের বার্ষিক শিক্ষক-অভিভাবক সম্মেলন অনুষ্ঠিত হয়েছে। এতে শিক্ষার্থীদের একাডেমিক উন্নয়ন ও শৃঙ্খলা নিয়ে বিস্তারিত আলোচনা হয়।</p><p>সম্মেলনে শিক্ষার্থীদের নিয়মিত উপস্থিতি, পড়াশোনার অগ্রগতি ও সহশিক্ষা কার্যক্রমে অংশগ্রহণ নিয়ে গুরুত্ব দেওয়া হয়।</p><p>অভিভাবকরা তাদের মতামত প্রকাশের সুযোগ পান এবং বিদ্যালয় কর্তৃপক্ষ ভবিষ্যৎ পরিকল্পনা তুলে ধরেন।</p>',
                'video_url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'reading_time_minutes' => 3,
            ],
        ];

        $index = Article::max('id') ?? 0;

        foreach ($articles as $i => $data) {
            $slug = Str::slug($data['title_bn']) . '-video-' . ($index + $i + 1);
            if (Article::where('slug', $slug)->exists()) {
                $this->command->warn("  Skipped (slug exists): {$data['title_bn']}");
                continue;
            }

            $catId = $categories->random()->id;
            $staffMember = $staff->random();

            $article = Article::create(array_merge($data, [
                'slug' => $slug,
                'category_id' => $catId,
                'status' => 'published',
                'featured_image' => null,
                'featured_image_caption' => null,
                'photo_credit' => null,
                'is_breaking' => false,
                'is_featured' => $data['is_featured'] ?? false,
                'is_editor_pick' => $data['is_editor_pick'] ?? false,
                'is_slider' => $data['is_slider'] ?? false,
                'slider_order' => $data['slider_order'] ?? 0,
                'published_at' => now()->subHours($i + 1),
                'author_id' => $admin?->id ?? 1,
                'meta_title' => $data['title_bn'],
                'meta_description' => $data['excerpt_bn'],
                'focus_keywords' => 'প্রাথমিক শিক্ষা, ভিডিও, ডিজিটাল শিক্ষা',
                'indexable' => true,
                'created_at' => now()->subDays(1)->subHours($i),
                'updated_at' => now()->subHours($i + 1),
            ]));

            $article->staffs()->sync([$staffMember->id]);

            $this->command->info("  Created video article: {$data['title_bn']} ({$data['video_url']})");
        }

        $this->command->info(count($articles) . ' video demo articles seeded successfully.');
    }
}
