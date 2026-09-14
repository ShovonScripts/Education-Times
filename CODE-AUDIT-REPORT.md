# EdTimes — সম্পূর্ণ কোড অডিট রিপোর্ট

**প্রজেক্ট:** EdTimes (দৈনিক এডুকেশন টাইমস) — Laravel 12 নিউজ পোর্টাল
**অডিটের তারিখ:** ১৩ সেপ্টেম্বর, ২০২৬
**পরিধি:** `app/`, `routes/`, `config/`, `bootstrap/`, `database/`, `resources/` — `vendor/` ও `node_modules/` বাদ
**কোডবেসের আকার:** ৩৪টি কন্ট্রোলার (২,৯৪২ লাইন), ২০টি মডেল, ৪৪টি মাইগ্রেশন, ৯৪টি Blade ফাইল (৮,০০৩ লাইন)

এই অডিটে কোনো ফাইল পরিবর্তন করা হয়নি। প্রতিটি সমস্যার সাথে ফাইলের পাথ ও লাইন নম্বর দেওয়া আছে, যাতে আপনি নিজে দেখে যাচাই করতে পারেন।

---

## প্রথমেই বলি — যেগুলো আপনি ভালো করেছেন

সমস্যার তালিকা লম্বা, কিন্তু সেটা দেখে ভুল ধারণা হওয়ার কারণ নেই। এই প্রজেক্টে বেশ কিছু সিদ্ধান্ত সত্যিই ভালো, এবং অনেক অভিজ্ঞ ডেভেলপারও এগুলো মিস করেন:

**মডেল লেয়ারটা কোডবেসের সবচেয়ে স্বাস্থ্যকর অংশ।** ১৪টির মধ্যে ১১টি মডেলে `casts()` সঠিকভাবে দেওয়া, রিলেশনশিপে রিটার্ন টাইপ ডিক্লেয়ার করা, `User` মডেলে `'password' => 'hashed'` আছে। `Setting::set()` লেখার সময় নিজেই ক্যাশ ক্লিয়ার করে (`Setting.php:23`), আর `Article::deleteStoredImages()` রিমোট URL ডিলিট করার চেষ্টা করে না (`Article.php:146`) — এই দুটোই মনোযোগী কাজের চিহ্ন।

**বাংলা ফন্ট সেল্ফ-হোস্টেড, `font-display: swap` সহ।** `resources/css/app.css:8-45`-এ চারটা `@font-face` ব্লক, `woff2` + `woff` ফরম্যাটে। Google Fonts থেকে রেন্ডার-ব্লকিং রিকোয়েস্ট নেই। বাংলা ফন্ট ভারী, তাই এটা পুরো কোডবেসের সবচেয়ে ভালো পারফরম্যান্স সিদ্ধান্ত।

**অ্যাসেট পাইপলাইন পরিষ্কার।** Tailwind v4 + Vite ঠিকভাবে কনফিগার করা, তিনটা লেআউটেই `@vite([...])` ব্যবহার হয়েছে। CDN থেকে Tailwind টানা হয়নি, jQuery বা Bootstrap JS কোথাও নেই — এই দুটো মিশিয়ে ফেলা খুব সাধারণ ভুল, আপনি করেননি।

**ফরেন কি ও ইউনিক কনস্ট্রেইন্ট ধারাবাহিকভাবে দেওয়া।** সব রিলেশনে `->constrained()` যুক্তিসঙ্গত cascade নিয়ম সহ, `articles.slug` ও `categories.slug`-এ unique, `article_likes`/`saved_articles` পিভটে `unique(['user_id','article_id'])`।

**কোডে কোনো আবর্জনা নেই।** `dd()`, `dump()`, `var_dump`, `console.log` — একটাও নেই। হার্ডকোড করা `localhost` URL নেই (যা আছে সব স্টক Laravel কনফিগের `env()` ডিফল্ট)। খালি `catch` ব্লক নেই। SQL ইনজেকশন নেই — প্রতিটা `DB::raw`/`whereRaw`-এ কনস্ট্যান্ট স্ট্রিং, কোনো ইউজার ইনপুট ঢোকে না।

**CSRF সুরক্ষা অটুট।** `bootstrap/app.php`-এ কোনো `validateCsrfTokens(except: ...)` নেই, সব ফর্মে `@csrf` আছে। আর `Admin\PostController::index` (লাইন ৩৮-৪৫) sort কলামকে allow-list দিয়ে যাচাই করে — এটা ঠিক যেভাবে করা উচিত।

**আপলোড ফাইলনেম নিরাপদ।** কোথাও `storeAs`/`move` নেই, সব আপলোডে Laravel-এর `hashName()` ব্যবহার হয়েছে, তাই ক্লায়েন্টের দেওয়া ফাইলনেম কখনো ব্যবহৃত হয় না। পাথ ট্রাভার্সাল বা `.php` আপলোডের সুযোগ নেই।

এখন সমস্যাগুলোতে আসি।

---

## সারসংক্ষেপ

| তীব্রতা | সংখ্যা | অর্থ |
|---|---|---|
| 🔴 **ক্রিটিক্যাল** | ৪ | লাইভ সাইট ঝুঁকিতে — আজই ঠিক করা দরকার |
| 🟠 **হাই** | ১৪ | ব্যবহারকারী বা SEO-তে সরাসরি প্রভাব — এই স্প্রিন্টে |
| 🟡 **মিডিয়াম** | ১৮ | মানের সমস্যা — পরিকল্পনা করে ঠিক করুন |
| ⚪ **লো** | ১৫ | পরিচ্ছন্নতা — সময় পেলে |

সবচেয়ে জরুরি তিনটি কথা এক লাইনে: **আপনার প্রোডাকশন পাসওয়ার্ড ও অ্যাপ-কি গিটে কমিট হয়ে আছে**, **সার্ভারের `.env` ফাইলটি ব্রাউজার থেকে ডাউনলোড করা যায়**, আর **`articles` টেবিলে একটাও ইনডেক্স নেই**।

---

# 🔴 ক্রিটিক্যাল — আজই ঠিক করুন

## C1. প্রোডাকশন পাসওয়ার্ড `.env.example`-এ কমিট হয়ে আছে

**ফাইল:** `.env.example` লাইন ৫২ · `.gitignore` লাইন ৩-৫

```
MAIL_PASSWORD="anarjo%shofiq"
```

`.gitignore` `.env`, `.env.backup`, `.env.production` বাদ দেয় — কিন্তু **`.env.example` বাদ দেয় না**। তাই এই ফাইলটা গিটে ট্র্যাক হচ্ছে।

আর সমস্যাটা এখানেই শেষ নয়। আমি যাচাই করে দেখেছি, ঠিক এই একই স্ট্রিং `anarjo%shofiq` আপনার প্রোডাকশন **ডেটাবেস** পাসওয়ার্ডও (`.env.production` লাইন ২৫)। অর্থাৎ একটা টেমপ্লেট ফাইল একসাথে লাইভ মেইলবক্স আর লাইভ MySQL অ্যাকাউন্ট — দুটোরই চাবি ফাঁস করে দিচ্ছে।

**কী করতে হবে:**
1. **এখনই** মেইলবক্স ও ডেটাবেস — দুটোর পাসওয়ার্ড বদলান, এবং দুটো যেন **আলাদা** হয়।
2. `.env.example` লাইন ৫২ খালি করুন: `MAIL_PASSWORD=`
3. গিট হিস্টরি থেকে মুছুন — শুধু নতুন কমিটে বদলালে হবে না, পুরোনো কমিটে থেকে যাবে:
   ```bash
   # BFG Repo-Cleaner দিয়ে
   bfg --replace-text passwords.txt
   git push --force
   ```
4. রিপো যদি পাবলিক থেকে থাকে, ধরে নিন পাসওয়ার্ড ইতিমধ্যে অন্যের হাতে গেছে।

## C2. সার্ভারের `.env` ফাইল ব্রাউজার থেকে ডাউনলোড করা যায়

**ফাইল:** `.env` লাইন ৫ · প্রজেক্টে একমাত্র `.htaccess` হলো `public/.htaccess`

```
APP_URL=http://localhost/ProDo/edtimes/public
```

পুরো প্রজেক্টে `.htaccess` খুঁজে শুধু একটাই পাওয়া যায় — `public/.htaccess`। রুট লেভেলে কোনো `.htaccess` নেই। `APP_URL` বলছে সাইট `htdocs/ProDo/edtimes/public` থেকে সার্ভ হচ্ছে, মানে তার উপরের ডিরেক্টরিটাও Apache-এর document root-এর ভেতরে এবং ব্রাউজ করা যায়।

স্টক Apache শুধু `^\.ht` দিয়ে শুরু হওয়া ফাইল আটকায় — `.env` সেই প্যাটার্নে পড়ে না। তাই এগুলো প্লেইন টেক্সট হিসেবে সার্ভ হবে:

```
/ProDo/edtimes/.env
/ProDo/edtimes/.env.production
/ProDo/edtimes/composer.json
/ProDo/edtimes/storage/logs/laravel.log
```

**আর একটা বিষয় আলাদা করে বলা দরকার:** আমি যাচাই করে দেখেছি আপনার `.env` (লাইন ৩) আর `.env.production` (লাইন ৩) — দুটোতে **একই `APP_KEY`**। এর মানে আপনার লোকাল ডেভেলপমেন্ট কপিটা যার হাতেই আছে, সে প্রোডাকশনের সেশন কুকি ডিক্রিপ্ট ও জাল করতে পারবে, signed URL বানাতে পারবে।

**কী করতে হবে:**
1. Apache-এর `DocumentRoot` সরাসরি `.../edtimes/public`-এ সেট করুন। শেয়ার্ড cPanel-এ শুধু `public/*`-এর ভেতরের ফাইলগুলো `public_html`-এ রাখুন, আর অ্যাপের বাকি অংশ তার এক লেভেল উপরে।
2. প্রোডাকশনের জন্য **আলাদা** `APP_KEY` বানান — `php artisan key:generate`। বর্তমান কি-টা ফাঁস হয়ে গেছে বলে ধরে নিন।
3. বাড়তি সুরক্ষা হিসেবে রুটে একটা `.htaccess`:
   ```apache
   <FilesMatch "^\.env">
       Require all denied
   </FilesMatch>
   ```

## C3. `articles` টেবিলে একটাও ইনডেক্স নেই

**ফাইল:** `database/migrations/2026_06_03_042003_create_articles_table.php:11-35` (এবং comments, page_views টেবিলেও একই অবস্থা)

৪৪টি মাইগ্রেশনে `->index(` খুঁজে **কোনো অ্যাপ্লিকেশন টেবিলে একটাও হিট পাওয়া যায়নি**। `articles`-এ যা আছে তা শুধু প্রাইমারি কি, `slug`-এ unique, আর `->constrained()` থেকে আসা ইমপ্লিসিট ফরেন-কি ইনডেক্স।

যেগুলো নেই কিন্তু দরকার: `status`, `published_at`, `is_breaking`, `is_featured`, `is_editor_pick`, `is_slider`, `deleted_at`।

সমস্যা হলো, সাইটের প্রায় প্রতিটা কুয়েরি ঠিক এই কলামগুলোই ব্যবহার করে:

```php
// HomeController.php:113-118
Article::where('status', ArticleStatus::PUBLISHED->value)
    ->latest('published_at')   // ← status আর published_at, দুটোরই ইনডেক্স নেই
    ->take(8)->get();
```

**বাস্তব প্রভাব:** প্রতিটা কুয়েরি পুরো টেবিল স্ক্যান করে, তারপর filesort করে। `articles`-এ `longText body_bn` আছে, তাই MySQL পুরো আর্টিকেলের বডিসহ প্রতিটা রো পড়ে। ৫,০০০ আর্টিকেল হলে হোমপেজ ও প্রতিটা ক্যাটেগরি/সার্চ পেজ ~১ms থেকে ২০০-৮০০ms-এ নেমে যাবে। শেয়ার্ড cPanel-এ (কম `innodb_buffer_pool_size`, থ্রটল করা IOPS) এটাই আপনার সবচেয়ে বড় স্কেলিং দেয়াল।

**সমাধান — একটা মাইগ্রেশন, সবচেয়ে বেশি লাভ:**
```php
Schema::table('articles', function (Blueprint $table) {
    $table->index(['status', 'published_at']);
    $table->index(['status', 'category_id', 'published_at']);
    $table->index(['status', 'is_featured', 'published_at']);
    $table->index(['status', 'is_breaking', 'published_at']);
    $table->index(['status', 'is_slider', 'slider_order']);
    $table->index('deleted_at');
});
Schema::table('comments', fn ($t) => $t->index(['article_id', 'status', 'parent_id']));
Schema::table('page_views', fn ($t) => $t->index('created_at'));
Schema::table('article_tag', fn ($t) => $t->index('tag'));
```

## C4. প্রতিটা পেজ লোডে `page_views`-এ একটা করে রো লেখা হয়

**ফাইল:** `app/Http/Controllers/ArticleController.php:36-44`

```php
PageView::create([
    'viewable_type' => Article::class,
    'viewable_id' => $article->id,
    'ip' => Request::ip(),
    'user_agent' => Request::userAgent(),
]);
```

কোনো ডিডুপ্লিকেশন নেই, বট ফিল্টার নেই, স্যাম্পলিং নেই, কিউ নেই। প্রতিটা হিট — মানুষ, Googlebot, AhrefsBot, আপটাইম মনিটর — রিকোয়েস্ট সাইকেলের ভেতরেই একটা রো লেখে।

**বাস্তব প্রভাব:** দিনে ৫০ হাজার পেজভিউ হলে বছরে ~১.৮ কোটি রো, প্রতিটায় পুরো `user_agent` স্ট্রিং (~২০০ বাইট) ও `referer`। বছরে ৫ GB-র বেশি — বেশিরভাগ শেয়ার্ড cPanel প্ল্যানে মোট ডিস্কই ৫-২০ GB। উপরন্তু এই রাইটটা TTFB ব্লক করে, আর পরিষ্কার করার একমাত্র উপায় `Admin\SettingController.php:34`-এর `PageView::truncate()` — সব-বা-কিছুই-না।

**সমাধান (যেকোনো একটাই সাহায্য করবে):**
- সবচেয়ে ভালো: অ্যাগ্রিগেট করুন। একটা `article_view_counts` টেবিল (`article_id` + `date` + `views`), `upsert` + `increment` দিয়ে — প্রতি হিটে একটা রো নয়, প্রতি আর্টিকেলে প্রতিদিন একটা রো।
- অথবা রাইটটা কিউ-তে পাঠান।
- ন্যূনতম: বট বাদ দিন, আর সেশন-প্রতি শর্ট ক্যাশ কি দিয়ে ডিডুপ করুন।
- ৯০ দিনের পুরোনো রো মোছার একটা কমান্ড যোগ করুন।

---

# 🟠 হাই — এই স্প্রিন্টেই

## H1. "স্টাফ" বলে আসলে কোনো পারমিশন লেভেল নেই — লেখককে ফুল অ্যাডমিন বানাতে হয়

**ফাইল:** `routes/web.php:85-92` · `app/Http/Middleware/AdminMiddleware.php:20-22`

```php
// routes/web.php:85 — staff রুট, কিন্তু admin মিডলওয়্যার
Route::middleware(['auth', 'admin'])->prefix('staff')->name('staff.')->group(...)
```
```php
// AdminMiddleware.php:20 — শুধু is_admin দেখে, is_editor কখনো দেখে না
if (!$user->is_admin) {
    abort(403, 'Unauthorized access.');
}
```

`AdminMiddleware`-ই একমাত্র গেট, আর সে শুধু `is_admin` পরীক্ষা করে। `is_editor` কোনো মিডলওয়্যার কখনো দেখে না। ফলে `is_editor = true, is_admin = false` থাকা একজন ইউজার — যা ঠিক `UserController::toggleRole` (লাইন ১৩১) "editor" রোলের জন্য তৈরি করে — **`/staff/*`-এর প্রতিটা রুটে 403 পায়**।

অর্থাৎ কোনো রিপোর্টারকে লেখার জায়গা দিতে চাইলে তাকে `is_admin = true` করতেই হবে, আর সেই মুহূর্তেই সে পেয়ে যাচ্ছে:

- `POST /admin/users` → নতুন অ্যাডমিন অ্যাকাউন্ট তৈরি
- `POST /admin/users/{user}/toggle-role` → যে কাউকে প্রোমোট করা
- `DELETE /admin/articles/{article}` → `forceDelete()`, ফেরানো যায় না
- `POST /admin/settings/clear-data` → অ্যানালিটিক্স টেবিল মুছে ফেলা
- `POST /admin/seo/redirects` → সাইটের যেকোনো URL হাইজ্যাক (দেখুন M4)

সবচেয়ে হতাশার কথা: `StaffArticleController`-এ মালিকানা যাচাই **ঠিকভাবেই করা আছে** (লাইন ৭৫-৭৭, ৮৪-৮৬, ১৪৪-১৪৬ — `edit`, `update`, `destroy` তিনটাতেই `if ($article->author_id !== Auth::id()) abort(403);`)। কিন্তু সেটা এখন অর্থহীন, কারণ যে `/staff/articles`-এ ঢুকতে পারে সে `/admin/articles/{id}`-এও ঢুকতে পারে, যেখানে কোনো মালিকানা যাচাই নেই।

**সমাধান:**
```php
// app/Http/Middleware/StaffMiddleware.php (নতুন)
if (!$user->is_editor && !$user->is_admin) {
    abort(403);
}
```
`bootstrap/app.php:20`-এ alias করুন, তারপর `routes/web.php:85` বদলে `['auth', 'staff']` করুন। এরপর রিপোর্টারদের `is_editor`-এ নামিয়ে আনুন।

## H2. স্টোরড XSS — আর্টিকেল বডি sanitize ছাড়াই রেন্ডার হয়

**ফাইল:** `resources/views/article/show.blade.php:109`

```blade
<div class="prose-bn">
    {!! $article->body_bn !!}
</div>
```

`body_bn` কাঁচা অবস্থায় এখানে আসে। তিনটা জায়গা থেকে এটা লেখা যায়, এবং একটা অ্যাডমিন-গেটেড নয়:

- `DashboardController.php:62-95` — রুট `POST /dashboard/post`, সাধারণ `auth` গ্রুপে, শুধু `is_editor` দেখে (লাইন ৬৪)। ভ্যালিডেশন `'body_bn' => 'required|string'` — কোনো HTML ফিল্টার নেই।
- `StaffArticleController.php:40` — একই নিয়ম।
- `Admin/ArticleController.php:62, 139` — একই নিয়ম।

ইনপুট উইজেটটা সাধারণ textarea (`staff/articles/create.blade.php:35`-এ `font-mono` ক্লাস), মানে লেখক ইচ্ছে করেই কাঁচা HTML টাইপ করেন। আর `composer.json`-এ কোনো HTML sanitizer নেই — `mews/purifier` নেই, `ezyang/htmlpurifier` নেই।

**আক্রমণের পথ:** একজন `is_editor` ইউজার বডিতে `<img src=x onerror="...">` দিয়ে পোস্ট করে → স্টেটাস `submitted` হয় → অ্যাডমিন `/admin/posts/pending` থেকে অনুমোদন করে → এরপর থেকে প্রতিটা ভিজিটরের ব্রাউজারে স্ক্রিপ্টটা চলে, যার মধ্যে পাবলিক সাইট ব্রাউজ করা লগইন করা অ্যাডমিনও আছেন।

**সমাধান:** লেখার সময় sanitize করুন, রেন্ডারের সময় নয়।
```bash
composer require mews/purifier
```
```php
// তিনটা কন্ট্রোলারেই, Article::create/update-এর আগে
$validated['body_bn'] = clean($validated['body_bn']);
```

## H3. ডেমো সিডার `admin@educationtimes.com / admin123` বানায় — এবং প্রতিবার চালালে পাসওয়ার্ড রিসেট করে

**ফাইল:** `database/seeders/DatabaseSeeder.php:17-19` · `DemoDataSeeder.php:40-51`

```php
// DemoDataSeeder.php:40
$admin = User::updateOrCreate(
    ['email' => 'admin@educationtimes.com'],
    ['password' => Hash::make('admin123'), 'is_admin' => true, ...]
);
```

এটা ডিফল্ট সিডারে শর্তহীনভাবে চলে — কোনো `app()->environment('local')` গার্ড নেই। আর যেহেতু `updateOrCreate` ইমেইল দিয়ে খোঁজে, প্রোডাকশনে `php artisan db:seed` চালালে এটা শুধু অ্যাকাউন্ট বানায় না — **বিদ্যমান অ্যাডমিনের পাসওয়ার্ড `admin123`-এ ফিরিয়ে দেয়**। `reporter123` ও `user123` একইভাবে সিড হয়। অ্যাডমিন লগইন ফর্ম `GET /admin`-এ পাবলিকলি খোলা।

ফাইল দেখে বলা সম্ভব নয় সিডারটা লাইভ সার্ভারে চলেছে কি না — সেটা প্রোডাকশন `users` টেবিল দেখে যাচাই করতে হবে।

**সমাধান:**
```php
if (! app()->environment('production')) {
    $demoSeeder->seedAllUserTypes();
}
```
এবং **এখনই** লাইভ ডেটাবেসে দেখুন এই তিনটা অ্যাকাউন্ট আছে কি না; থাকলে পাসওয়ার্ড বদলান।

## H4. তিনটা আলাদা আর্টিকেল-তৈরির পথ, তিন রকম নিয়ম

**ফাইল:** `Admin/ArticleController.php:48-115` · `StaffArticleController.php:34-71` · `DashboardController.php:62-109`

| | Admin store | Staff store | Dashboard storePost |
|---|---|---|---|
| ভ্যালিডেশন নিয়ম | ১৫টি | ৭টি | ৬টি |
| ছবির সাইজ সীমা | ৪ MB | ৪ MB | **২ MB** |
| ফলাফল স্টেটাস | caller যা দেয় | `DRAFT` | `'submitted'` |
| slug তৈরি | `ArticleService` | `ArticleService` | **হাতে লেখা** |

ছবির ২ MB বনাম ৪ MB পার্থক্যটা সত্যিকারের বাগ: একই JPEG `/admin/articles/create` দিয়ে আপলোড হয়, কিন্তু `/dashboard/post` দিয়ে রিজেক্ট হয়। ড্যাশবোর্ড ফর্ম ব্যবহারকারী কেউ এটা অনুমান করতে পারবেন না।

slug-এর ব্যাপারটা আরো গুরুতর। `DashboardController` সার্ভিসটা লাইন ১৭-এ ইনজেক্ট করে — তারপর কখনো ব্যবহার করে না:

```php
// DashboardController.php:77-81 (যাচাই করা)
$slug = Str::slug($validated['title_bn']);
if (empty($slug)) { $slug = 'post-' . Str::random(8); }
$slug .= '-' . Str::random(4);
```

ফলে একই বাংলা শিরোনামের দুটো আর্টিকেল ড্যাশবোর্ড দিয়ে `shironam-a3f9` ও `shironam-7b21` পায়, কিন্তু admin/staff দিয়ে `shironam` ও `shironam-1`। তিনটা সমস্যা: URL-এর আকার লেখকের রোল অনুযায়ী বদলায়, `-1` কনভেনশন মানা হয় না, আর **uniqueness কখনো যাচাই করা হয় না** — `articles.slug` unique, তাই সংঘর্ষ হলে সেটা 500 এরর, fallback নয়।

**সমাধান:** একটা `ArticleService::create(array $data, ?UploadedFile $image): Article` বানিয়ে তিনটা কন্ট্রোলার থেকেই সেটা ডাকুন। সবচেয়ে ছোট ফিক্স হিসেবে অন্তত `DashboardController.php:77-83` বদলে:
```php
$validated['slug'] = $this->articleService->generateUniqueSlug($validated['title_bn']);
```

## H5. মন্তব্য মডারেশন আসলে কাজই করে না

**ফাইল:** `app/Http/Controllers/CommentController.php:24` (যাচাই করা)

```php
'status' => 'approved',   // ← হার্ডকোড
```

`Admin/CommentController.php:21-31`-এ `approve()` ও `reject()` লেখা আছে, `routes/web.php:125-128`-এ রুটও আছে — কিন্তু প্রতিটা নতুন মন্তব্য তৈরির সাথে সাথেই `approved`। অর্থাৎ মডারেশন UI-টা সাজানো ছবি মাত্র; অ্যাডমিন "reject" চাপলে এমন একটা ফিল্ড বদলাচ্ছেন যা গেট হিসেবে কেউ পড়ে না।

একটা শিক্ষা-বিষয়ক নিউজ সাইটে যেকোনো লগইন করা ব্যবহারকারীর মন্তব্য সাথে সাথে পাবলিক হয়ে যাওয়া — এটা ট্রাস্ট ও সেফটির সমস্যা। `throttle:10,1` আছে, কিন্তু রেট লিমিট মডারেশনের বিকল্প নয়।

এছাড়া লাইন ১৬-এ `'parent_id' => 'nullable|exists:comments,id'` যাচাই করে না যে parent একই আর্টিকেলের — তাই এক আর্টিকেলের থ্রেডে অন্য আর্টিকেলের রিপ্লাই জোড়া লাগানো সম্ভব।

**সমাধান:**
```php
'status' => 'pending',
// এবং
'parent_id' => ['nullable', Rule::exists('comments','id')->where('article_id', $request->article_id)],
```

## H6. `intervention/image` ইনস্টল করা আছে, কিন্তু কখনো ব্যবহার হয়নি — পুরো সাইজের ছবি সার্ভ হচ্ছে

**ফাইল:** `composer.json:11` · আপলোড: `Admin/ArticleController.php:92, 172, 216`, `StaffArticleController.php:56, 103`, `DashboardController.php:89-90`

পুরো `app/`-এ `Intervention|ImageManager|Image::` খুঁজে **একটাও ম্যাচ নেই**। প্রতিটা আপলোড কাঁচা পাসথ্রু:

```php
// Admin/ArticleController.php:92
$article->update(['featured_image' => $request->file('featured_image')->store('articles', 'public')]);
```

ভ্যালিডেশনে `max:4096` (৪ MB) অনুমোদিত।

**বাস্তব প্রভাব:** হোমপেজে ১৬টা আর্টিকেল কার্ড + ৬ স্লাইডের হিরো + হিরো-গ্রিড — মোটামুটি ২৫টা ছবি। কার্ডগুলো দেখায় ৮০×৬৪ থেকে ৪০০×২২৫ পিক্সেলে, কিন্তু ব্রাউজার প্রতিবার ৪ MB-র মূল ফাইলটাই ডাউনলোড করে। সবচেয়ে খারাপ ক্ষেত্রে একটা হোমপেজ **~১০০ MB ছবি ট্রান্সফার**, যার আসল প্রয়োজন হয়তো ৫০০ KB। বাংলাদেশের মোবাইল কানেকশনে পেজটা কার্যত ব্যবহারের অযোগ্য, আর শেয়ার্ড হোস্টিংয়ের ব্যান্ডউইথ কোটা কয়েক দিনেই শেষ।

**সমাধান** (প্যাকেজ তো ইনস্টলই আছে):
```php
use Intervention\Image\Laravel\Facades\Image;

$img = Image::read($request->file('featured_image'));
$img->scaleDown(width: 1200)->toWebp(82)->save($fullPath);   // আর্টিকেল হিরো
$img->scaleDown(width: 400)->toWebp(78)->save($thumbPath);   // কার্ড থাম্ব
```
`Article`-এ একটা `thumbnail_url` অ্যাকসেসর যোগ করে `article-card.blade.php:42,70` ও `category-block.blade.php:40`-এ সেটা ব্যবহার করুন।

**ডিপ্লয় নোট:** `public/`-এ `storage` সিমলিংক নেই। সার্ভারে `php artisan storage:link` না চালানো পর্যন্ত প্রতিটা `Storage::disk('public')->url()` 404 দেবে।

## H7. হোমপেজে গরম ক্যাশেও ~৮২টা কুয়েরি চলে

**ফাইল:** `HomeController.php:21-143` · `AppServiceProvider.php:40-61` · `partials/header.blade.php` · `partials/footer.blade.php`

`config/cache.php:18` ডিফল্ট `database`, আর `.env.example:37`-এ `CACHE_STORE=database`। অর্থাৎ **প্রতিটা `Cache::remember()` নিজেই `cache` টেবিলে একটা SQL SELECT**। অ্যাপটা ক্যাশিংয়ের উপর ভারীভাবে নির্ভর করে, ফলে সেটা বহু ছোট ছোট কুয়েরিতে রূপ নিচ্ছে।

একটা গরম-ক্যাশ হোমপেজ রিকোয়েস্টের হিসাব:

| উৎস | কুয়েরি |
|---|---|
| `RedirectMiddleware.php:16,19` — `Schema::hasTable()` + redirect লুকআপ | ২ |
| সেশন (`SESSION_DRIVER=database`) | ১-২ |
| হেডার composer + ৭× `Setting::get` | ৮ |
| `header.blade.php` — আরো ৯× `Setting::get` | ৯ |
| `layouts/app.blade.php` — ৫× | ৫ |
| `HomeController` — ১৩× `Cache::remember` | ১৩ |
| lazy-load করা `$article->category` (দেখুন H8) | ১৬ |
| `x-ads.banner` ×৩ | ৩ |
| ফুটার composer + `footer.blade.php` ১৫× `Setting::get` + Partner | ২৪ |

**মোট ≈ ৮২টি কুয়েরি।** ঠান্ডা ক্যাশে আরো ~৩০টা যোগ হয়।

সবচেয়ে বড় অপচয় `Setting::get`-এর পুনরাবৃত্তি: শুধু `social_facebook` **পাঁচবার** আলাদাভাবে আনা হয় প্রতি রিকোয়েস্টে, কারণ `Setting::get`-এ কোনো request-level memoization নেই।

**সমাধান:**
1. `.env`-এ `CACHE_STORE=file` করুন — শেয়ার্ড হোস্টিংয়ে DB রাউন্ডট্রিপ ও `cache` টেবিলের কনটেনশন দুটোই বাদ যায়।
2. পুরো settings টেবিল একবারে ক্যাশ করুন — ~৩৬টার বদলে ১টা কুয়েরি:
   ```php
   private static array $memo = [];
   public static function get(string $key, mixed $default = null): mixed {
       static::$memo = static::$memo ?: Cache::remember('all_settings', 3600,
           fn () => static::pluck('value', 'key')->all());
       return static::$memo[$key] ?? $default;
   }
   ```
3. `header.blade.php` ও `footer.blade.php`-এর ডুপ্লিকেট `Setting::get` কলগুলো মুছুন — composer ইতিমধ্যে ওই ভ্যালুগুলো পাঠাচ্ছে।

## H8. N+1: হোমপেজে `$article->category` ১৬ বার lazy-load হয়

**কুয়েরির জায়গা:** `HomeController.php:24-30` · **লুপের জায়গা:** `components/news/article-card.blade.php:49-50, 78-79`

```php
// HomeController.php:28 — staffs eager load করে, category করে না
->with('staffs:id,name_bn')
```
```blade
{{-- article-card.blade.php:78 --}}
@if($showTag && $article->category)
```

হোমপেজে কার্ড রেন্ডার হয়: ১ + ৪ + ৩ + ১ + ৪ + ৩ = **১৬টা lazy load**।

গুরুত্বপূর্ণ ব্যাপার — এটা **ক্যাশ হিটেও চলে**। ক্যাশ করা payload-এ Article মডেলগুলো আছে কিন্তু `category` রিলেশন নেই, তাই unserialize করে `->category` ধরলেই আবার DB-তে যায়। ক্যাশিং এই N+1 ঢাকতে পারে না।

**সমাধান** (এক লাইন):
```php
->with(['staffs:id,name_bn', 'category:id,name_bn,slug'])
```

## H9. অ্যাডমিন পোস্ট লিস্টে প্রতি রো-তে `COUNT(*)`

**কুয়েরি:** `Admin/PostController.php:22` (`withCount` নেই), পেজিনেশন ২৫ · **লুপ:** `admin/posts/index.blade.php:143`

```blade
{{ number_format($article->pageViews()->count()) }}
```

একই বাগ `admin/posts/featured.blade.php:38`-এও। প্রতি পেজ লোডে ২৫টা বাড়তি `SELECT count(*) FROM page_views WHERE ...` — আর সেটা স্কিমার সবচেয়ে বড় ও দ্রুত বাড়তে থাকা টেবিলে (C4)। `page_views` কয়েক লাখ রো ছাড়ালেই সম্পাদকদের সবচেয়ে বেশি ব্যবহার করা পেজটা কয়েক সেকেন্ড নিতে শুরু করবে।

**সমাধান:** কন্ট্রোলারে `->withCount('pageViews')`, ভিউতে `$article->page_views_count`।

## H10. সার্চ `longText` বডিতে `LIKE '%...%'` চালায় — DoS-এর পথ

**ফাইল:** `app/Http/Controllers/SearchController.php:22-27`

```php
$q->where('title_bn', 'like', "%{$search}%")
  ->orWhere('body_bn', 'like', "%{$search}%")     // longText
```

শুরুতে `%` থাকলে কোনো ইনডেক্স কাজে আসে না, আর `body_bn` `longText` — তাই MySQL প্রতিটা সার্চে প্রতিটা আর্টিকেলের পুরো বডি ডিস্ক থেকে পড়ে।

এই এন্ডপয়েন্ট **পাবলিক, আনঅথেনটিকেটেড, আনথ্রটলড**। উপরন্তু `%` ও `_` escape করা হয় না, তাই `?q=%` সব রো ম্যাচ করায়, আর `q`-এর দৈর্ঘ্যেরও কোনো সীমা নেই। ৫,০০০ আর্টিকেলে কয়েকটা সমসাময়িক সার্চই শেয়ার্ড MySQL-এর কোটা শেষ করে পুরো সাইট ফেলে দিতে পারে।

**সমাধান:**
```php
// মাইগ্রেশন
$table->fullText(['title_bn', 'excerpt_bn', 'body_bn']);
// কন্ট্রোলার
$request->validate(['q' => 'nullable|string|max:100']);
$query->whereFullText(['title_bn','excerpt_bn','body_bn'], $search);
```
রুটে `->middleware('throttle:30,1')` যোগ করুন।

## H11. সাইটম্যাপ সব আর্টিকেল মেমরিতে তোলে — বডিসহ

**ফাইল:** `app/Http/Controllers/Admin/SeoController.php:120`

```php
$articles = Article::where('status','published')->where('indexable',true)->latest('published_at')->get();
```

`select()` নেই, `chunk()` নেই, `cursor()` নেই। শুধু একটা URL আর তারিখ ছাপার জন্য প্রতিটা আর্টিকেলের পূর্ণ মডেল — **`body_bn` সহ** — হাইড্রেট হয়।

**প্রভাব:** ১০,০০০ আর্টিকেল × ~৬ KB বডি = ~৬০ MB কাঁচা ডেটা, Eloquent অবজেক্ট ওভারহেড ধরে ২০০ MB+। শেয়ার্ড cPanel-এ `memory_limit` সাধারণত ১২৮-২৫৬ MB, তাই `/sitemap.xml` মেমরি এররে মরবে — আর Google নিয়মিত এটা টানে, ফলে **ইনডেক্সিং চুপচাপ বন্ধ হয়ে যাবে**।

আরো একটা বিপদ লাইন ১৩৮-এ:
```php
$xml .= '<news:publication_date>' . $article->published_at->toIso8601String() . '</news:publication_date>';
```
`published_at` nullable, আর কুয়েরিটা `status` দিয়ে ফিল্টার করে, `published_at IS NOT NULL` দিয়ে নয়। একটা মাত্র রো-তে null থাকলেই পুরো সাইটম্যাপ 500 — আংশিক ফাইল নয়, **সম্পূর্ণ ব্যর্থতা**।

**সমাধান:**
```php
Article::where('status','published')->where('indexable',true)
    ->whereNotNull('published_at')
    ->select('slug','title_bn','published_at','updated_at')
    ->latest('published_at')
    ->chunk(1000, function ($chunk) { /* stream */ });
```
`published_at?->toIso8601String()` ব্যবহার করুন, আউটপুট এক ঘণ্টা ক্যাশ করুন, ৫০ হাজার URL ছাড়ালে sitemap index-এ ভাগ করুন।

## H12. `og:image` নেই — প্রতিটা ফেসবুক শেয়ার ফাঁকা ধূসর বাক্স

**ফাইল:** `resources/views/layouts/app.blade.php:21-24`

আমি নিজে যাচাই করেছি: ৯৪টা ভিউয়ে `og:image`, `twitter:card`, `application/ld+json`, `name="robots"`, `@section('canonical'` — **এর একটাও নেই। শূন্য।**

অথচ ঠিক করার অবকাঠামো আপনার কোডেই তৈরি আছে, শুধু ব্যবহার হয়নি: `app/Models/Article.php:114-133`-এ `getOgImageUrlAttribute()` ও `getFeaturedImageUrlAttribute()` আছে, আর `config/filesystems.php:44` সেগুলোকে **অ্যাবসোলিউট URL** ফেরায় — যা Facebook/X-এর ঠিক প্রয়োজন।

**প্রভাব:** একটা আর্টিকেলের প্রতিটা Facebook ও WhatsApp শেয়ার থাম্বনেইল ছাড়া দেখায়। বাংলাদেশের নিউজ সাইটে যেখানে ফেসবুকই প্রধান ট্রাফিক সোর্স, এটা সরাসরি মাপা যায় এমন CTR ক্ষতি।

**সমাধান** — লেআউটে:
```blade
<meta property="og:image" content="@yield('og_image', Setting::get('site_logo'))">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta name="twitter:card" content="summary_large_image">
```
`article/show.blade.php`-এ:
```blade
@section('og_image', $article->og_image_url ?? $article->featured_image_url)
@section('og_type', 'article')
```

## H13. কোনো JSON-LD স্ট্রাকচার্ড ডেটা নেই — Google News-এ ঢোকার দরজা বন্ধ

৯৪টা ভিউয়ে `NewsArticle`/`schema.org` — শূন্য ম্যাচ।

একটা নিউজ সাইটের জন্য এটাই সবচেয়ে বড় হাতছাড়া SEO সুযোগ। Google-এর Top Stories কারোসেল ও News সারফেসে ঢোকার জন্য `NewsArticle` স্কিমা কার্যত অপরিহার্য।

আরো একটা সম্পর্কিত সমস্যা `SeoController.php:136-139`-এ: `<news:news>` ব্লকে `<news:publication>` র‍্যাপার (যার ভেতরে `<news:name>` ও `<news:language>`) **নেই**, যা Google News স্কিমায় বাধ্যতামূলক। ফলে সাইটম্যাপটা Google News সরাসরি বাতিল করে।

**সমাধান:** `article/show.blade.php`-এ `NewsArticle` JSON-LD (`headline`, `image`, `datePublished`, `dateModified`, `author`, `publisher.logo`), লেআউটে `Organization` + `WebSite`। সাইটম্যাপে publication র‍্যাপার যোগ করুন এবং `<news:news>` শুধু শেষ ২ দিনের আর্টিকেলে দিন।

## H14. `indexable` ফ্ল্যাগ আসলে কিছুই করে না

`SeoController.php:120` এটা দিয়ে সাইটম্যাপ ফিল্টার করে, আর অ্যাডমিন UI (`SeoController.php:277`) এটাকে "সার্চ ইঞ্জিন ইনডেক্সিং নিয়ন্ত্রণ" হিসেবে দেখায় — কিন্তু কোনো ভিউয়ে `name="robots"` নেই (যাচাই করা)।

অর্থাৎ non-indexable চিহ্নিত একটা আর্টিকেল শুধু সাইটম্যাপ থেকে বাদ যায়; Google তবুও ইন্টারনাল লিংক ধরে সেটা ক্রল ও ইনডেক্স করে।

**কেন এটা গুরুতর:** সম্পাদক ভাবছেন তিনি একটা প্রত্যাহার করা বা ভুল সংবাদ de-index করেছেন। সেটা ইনডেক্সে রয়ে যাচ্ছে। একটা সংবাদমাধ্যমের জন্য এটা শুধু SEO নয়, আইনি ও সুনামের ঝুঁকি।

**সমাধান:** `article/show.blade.php`-এ
```blade
@if(!$article->indexable)<meta name="robots" content="noindex,follow">@endif
```
আর লেআউটে ডিফল্ট `<meta name="robots" content="index,follow,max-image-preview:large">`।

---

# 🟡 মিডিয়াম

## নিরাপত্তা

**M1 — SVG আপলোড অনুমোদিত (`SettingController.php:47-50`, `PartnerController.php:29,55`).** `mimes:...,svg` থাকায় `<script>` বহনকারী SVG পাস করে, `storage/app/public`-এ জমা হয় এবং `/storage/settings/<hash>.svg`-এ সরাসরি গেলে সাইটের origin-এ স্ক্রিপ্ট চলে। `<img src>`-এ রেন্ডার নিরাপদ, সরাসরি URL নয়। **ফিক্স:** তিনটা জায়গা থেকেই `svg` বাদ দিন।

**M2 — Google OAuth লগইন `is_active` যাচাই করে না (`SocialiteController.php:22-38`).** `AuthController::login:66` ও `AdminMiddleware:24` — দুটোই নিষ্ক্রিয় অ্যাকাউন্ট আটকায়, কিন্তু Socialite callback-এ কোনো যাচাই নেই। নিষ্ক্রিয় করা অ্যাকাউন্ট `/auth/google/callback` দিয়ে আবার ঢুকে পড়তে পারে। **ফিক্স:** প্রতিটা `Auth::login()`-এর পরে `is_active` গার্ড দিন।

**M3 — আনঅথেনটিকেটেড GET এন্ডপয়েন্ট স্টেট বদলায় (`routes/web.php:47-48`, `AdController.php:95,105`).** `/ads/impression/{ad}` কোনো মিডলওয়্যার, CSRF বা throttle ছাড়াই `increment('impressions')` চালায়। `curl` লুপে বিজ্ঞাপনের হিসাব ফোলানো যায়, আর যে কেউ নিজের সাইটে `<img src=".../ads/impression/3">` বসিয়ে ভুয়া impression বানাতে পারে। **ফিক্স:** POST + CSRF করুন, বা `throttle:30,1` + IP-ভিত্তিক dedupe।
*(ভালো দিক: `click()`-এর open-redirect ঠিকভাবে আটকানো আছে — লাইন ৯৭-৯৯।)*

**M4 — অ্যাডমিন redirect যেকোনো অ্যাবসোলিউট URL নেয় (`SeoController.php:178`, `RedirectMiddleware.php:25`).** `new_url`-এ scheme/host যাচাই নেই, আর মিডলওয়্যারটা গ্লোবালি appended। `{old_url: '/login', new_url: 'https://evil.tld/login', status_code: 301}` একটা রো আসল লগইন পেজকে ফিশিং ক্লোনে পাঠিয়ে দেবে — আর 301 ব্রাউজার শক্তভাবে ক্যাশ করে, তাই রো মুছেও রক্ষা নেই। **ফিক্স:** relative (`/^\//`) বা নিজের host-এ সীমাবদ্ধ করুন।

**M5 — register/contact/newsletter-এ rate limit নেই (`routes/web.php:51,53,60`).** লগইন (`throttle:5,1`) ও কমেন্ট (`throttle:10,1`) ঠিকভাবে throttled, তাই এটা অসঙ্গতি। `/register`-এ সীমাহীন বট অ্যাকাউন্ট বানানো যায়, আর ইমেইল ভেরিফিকেশন কোথাও enforce হয় না (L4), তাই সেগুলো সাথে সাথেই মন্তব্য করার জন্য ব্যবহারযোগ্য।

**M6 — ইউজার নিষ্ক্রিয় করলে তার চলমান সেশন শেষ হয় না (`Admin/UserController.php:144`).** `SESSION_DRIVER=database`, কিন্তু `sessions` টেবিলের রো মোছা হয় না। নিষ্ক্রিয় করা নন-অ্যাডমিন ১২০ মিনিট পর্যন্ত `/dashboard` ও মন্তব্য ব্যবহার করতে থাকে। **ফিক্স:** `DB::table('sessions')->where('user_id', $user->id)->delete();`

**M7 — প্রোডাকশনে সেশন কুকি `Secure` নয় (`config/session.php:172`).** `.env.production`-এ `APP_URL=https://...` কিন্তু `SESSION_SECURE_COOKIE` সংজ্ঞায়িত নয়, তাই `null` — কুকি HTTP-তেও যায়। **ফিক্স:** `SESSION_SECURE_COOKIE=true` ও `SESSION_ENCRYPT=true`।

**M8 — `spatie/laravel-permission` ইনস্টল করা কিন্তু সম্পূর্ণ অব্যবহৃত (`composer.json:15`).** `User`-এ `HasRoles` trait নেই; `hasRole|assignRole|@can` — `app/` ও `resources/`-এ শূন্য হিট। অথরাইজেশন ১০০% ad-hoc বুলিয়ান, আর সেই দুই সিস্টেম পরস্পরবিরোধী (H1)। **ফিক্স:** হয় ঠিকভাবে গ্রহণ করুন, নাহয় `composer remove` করুন। দুটো একসাথে রাখলে H1-এর মতো গোলমাল হতেই থাকবে।

## ডেটাবেস ও পারফরম্যান্স

**M9 — `RedirectMiddleware` প্রতি GET-এ schema metadata কুয়েরি চালায় (`RedirectMiddleware.php:16`).** `Schema::hasTable('redirects')` `information_schema`-তে কুয়েরি করে, যা শেয়ার্ড MySQL-এ বিশেষভাবে ধীর। গার্ডটা এমন একটা অবস্থার জন্য যা শুধু মাইগ্রেশনের আগে সম্ভব। **ফিক্স:** গার্ড মুছে দিন, আর পুরো redirect map ক্যাশ করুন (`pluck('new_url','old_url')`)।

**M10 — অ্যাডমিন sidebar composer প্রতি পেজে ৫টা COUNT চালায় (`AppServiceProvider.php:21-31`).** পাঁচটা ইনডেক্সহীন count + আরো দুটো `Schema::hasTable()`। `Admin/SeoController.php:20-40` SEO ড্যাশবোর্ডের জন্য **এগারোটা** count চালায়। **ফিক্স:** একটা grouped কুয়েরি — `Article::select('status', DB::raw('count(*) as c'))->groupBy('status')` — ৬০ সেকেন্ড ক্যাশ সহ।

**M11 — `home_categories` ক্যাশে অব্যবহৃত `body_bn` জমা হয় (`HomeController.php:26`).** `body_bn` select করা হয় কিন্তু কোনো ভিউ পড়ে না। ১৫ ক্যাটেগরি × ৪ আর্টিকেল × ~৬ KB = ~৩৬০ KB মৃত ডেটা প্রতি ক্যাশ সাইকেলে serialize ও পড়া হয়। **ফিক্স:** select থেকে `'body_bn'` বাদ দিন।

**M12 — `mostRead`-এর ১ ঘণ্টার TTL কখনো টেকে না (`HomeController.php:85-93`).** `withCount` + `orderBy` প্রতিটা published আর্টিকেলের জন্য subquery চালায়, `LIMIT 5` তা থামাতে পারে না। কোডের কমেন্টে "requires table scan" স্বীকার করে ১ ঘণ্টা TTL দেওয়া — কিন্তু `ClearsHomepageCache.php:18` প্রতিটা আর্টিকেল সেভ/ফ্ল্যাগ-টগলে সেই কি-টা মুছে দেয়। সক্রিয় নিউজ ডেস্কে স্ক্যানটা অবিরাম চলে। **ফিক্স:** `articles.view_count` কলাম (ইনডেক্সড) রাখুন, আর `home_most_read`-কে invalidation লিস্ট থেকে বাদ দিন — জনপ্রিয়তার তালিকা প্রতিটা সম্পাদনার পরে তাজা হওয়ার দরকার নেই।

**M13 — `staff` মডেলিংয়ে দুটো পরস্পরবিরোধী সত্য।** `article_staff` পিভট তৈরি হয়েছে কিন্তু `articles.staff_id` কখনো drop হয়নি। `Article`-এ `staff()` ও `staffs()` দুটোই আছে, আর `Admin/ArticleController.php:96-98` প্রতি সেভে দুটোতেই লেখে। দুটো সোর্স অফ ট্রুথ নীরবে আলাদা হয়ে যেতে পারে। এছাড়া `2026_06_03_052728_fix_staff_table_columns.php:46-48`-এর `down()` খালি, তাই মাইগ্রেশন সেট রোলব্যাক করা যায় না।

**M14 — `article_tag`-এ unique নেই, ট্যাগ dedupe হয় না.** `Admin/ArticleController.php:101-109` কমা-স্ট্রিং ভেঙে লুপে insert করে, trim/dedupe ছাড়া — তাই "শিক্ষা, শিক্ষা" দুটো রো বানায়। আর `HomeController.php:105-111` ইনডেক্সহীন `tag` কলামে `GROUP BY ... ORDER BY count DESC` চালায়। **ফিক্স:** `unique(['article_id','tag'])` + `index('tag')`, আর `insertOrIgnore`।

**M15 — `published_at` nullable কিন্তু null গার্ড ছাড়াই ব্যবহৃত.** `article/show.blade.php:35` ও `SeoController.php:138` সরাসরি `->toIso8601String()` ডাকে। `Admin/PostController.php:159-161` গার্ড দেয় কিন্তু `:201` `COALESCE` ব্যবহার করে — অসঙ্গত পথ মানে null থাকা সম্ভব।

## কোড কোয়ালিটি

**M16 — একটাও Form Request নেই; ৩৭টা inline ভ্যালিডেশন, আর create/update নিয়মিত আলাদা হয়ে যায়.** `app/Http/Requests/` ডিরেক্টরিটাই নেই (যাচাই করা)। `$request->validate()` ২৩টা ফাইলে ৩৭ বার। উদাহরণ: `Admin/StaffController.php:26-39` ও `:56-69` — দুটো ১৩-নিয়মের ব্লক হুবহু এক, অর্থাৎ ১৪ লাইন কপি। `staff_type`-এর enum তালিকা চারবার লেখা আছে (দুই ভ্যালিডেশন, একবার লেবেল, একবার মাইগ্রেশন কমেন্ট)। **ফিক্স:** রিসোর্স-প্রতি একটা Form Request — `ArticleRequest`, `StaffRequest`, `CategoryRequest`, `UserRequest` — এতেই ৩৭টার প্রায় সবগুলো শুষে নেবে।

**M17 — `Article.status`-এ enum cast নেই; `'published'` ১০০ বার হার্ডকোড.** `app/Enums/ArticleStatus.php` সুন্দরভাবে লেখা, কিন্তু `Article.php:27-37`-এ `status` cast করা হয়নি। ফলাফল মাপা যায়: `ArticleStatus::` ৪টা ফাইলে ২১ বার, বিপরীতে quoted literal ২৭টা ফাইলে **১০০ বার**। কোনো Blade ফাইল enum ব্যবহার করে না। এর ব্যর্থতা ইতিমধ্যেই দৃশ্যমান — `Admin/PostController.php:156`-এর `'required|in:draft,published,submitted,archived'`-এ `scheduled` বাদ পড়েছে, যদিও একই কন্ট্রোলারের `scheduled()` মেথড scheduled আর্টিকেল দেখায়। **ফিক্স:** `'status' => ArticleStatus::class` — এই এক লাইন যোগ করলেই ১০০টা জায়গা টাইপ এরর হিসেবে ধরা পড়বে।

**M18 — আর্টিকেল আপডেটে ট্যাগ খালি করলে মোছে না (`Admin/ArticleController.php:184-193`).**
```php
if (!empty($validated['tags'])) { $article->tags()->delete(); ... }
```
ট্যাগ ফিল্ড ফাঁকা করে সাবমিট করলে `!empty('')` false, তাই `delete()` চলে না — পুরোনো ট্যাগ থেকে যায়। মজার বিষয়, `StaffArticleController.php:122-133` এটা **ঠিকভাবে** করে (`isset` গার্ড দিয়ে)। একই ফিচার, দুই আচরণ, আর ভাঙা সংস্করণটা অ্যাডমিন পাশে।

---

# ⚪ লো

**L1 — `errors/` ডিরেক্টরি নেই** (যাচাই করা)। প্রতিটা 404 — আর নিউজ সাইটে পুরোনো লিংক থেকে প্রচুর 404 আসে — সাইটের চেহারা ছাড়া, ইংরেজিতে, ফেরার পথ ছাড়া একটা মৃত প্রান্ত। **ফিক্স:** `errors/404.blade.php`, `500`, `419` — বাংলা মেসেজ, সার্চ বক্স ও সর্বশেষ শিরোনাম সহ।

**L2 — canonical সাপোর্ট মৃত কোড.** `layouts/app.blade.php:18-20`-এ `@hasSection('canonical')` হুক আছে, কিন্তু কোনো ভিউ সেটা define করে না (যাচাই করা: শূন্য)। `articles.canonical_url` কলামও মাইগ্রেশনের বাইরে কোথাও ব্যবহৃত হয় না।

**L3 — `lang/` নেই; কন্ট্রোলারে ৪৬টা হার্ডকোড করা বাংলা flash message.** আর সেগুলো ভাষা-মিশ্রিত: `'Categories তৈরি করা হয়েছে।'`, `'Pages Content আপডেট হয়েছে!'`, `'বাল্ক Action সম্পন্ন!'`। `AuthController:82` বলে `'ইমেইল বা পাসওয়ার্ড ভুল।'` আর `Admin\AuthController:49` বলে `'Email বা Password ভুল।'` — একই কাজে দুই ভাষারীতি। **ফিক্স:** `lang/bn/messages.php`-এ কেন্দ্রীভূত করুন; একজায়গায় এলে মিশ্রণটা চোখে পড়বে ও ঠিক করা সহজ হবে।

**L4 — পাসওয়ার্ড রিসেট ফ্লো নেই, ইমেইল ভেরিফিকেশন enforce হয় না.** `login.blade.php:43-44`-এ `@if(Route::has('password.request'))` গার্ড থাকায় লিংকটা নীরবে কখনো দেখায় না। ব্রোকার ও `password_reset_tokens` টেবিল আছে, কিন্তু রুট রেজিস্টার করা নেই। ফলে পাসওয়ার্ড হারালে self-service recovery নেই — যা C1/C3-এর পরে বিশেষভাবে প্রয়োজন।

**L5 — ২টা টেস্ট, দুটোই স্টক scaffolding.** `tests/Unit/ExampleTest.php` (`assertTrue(true)`) আর `tests/Feature/ExampleTest.php`। দ্বিতীয়টা অবশ্য চিন্তা করে লেখা — `/up` হিট করে, আর `:14-22`-এ `SCRIPT_NAME`/`PHP_SELF` patch করে সাবডিরেক্টরি সমস্যা সামলায়। সেটা রাখার মতো একটা deploy smoke test। কিন্তু **বিজনেস লজিকের কভারেজ শূন্য** — H4, H5, M18 এই তিনটা বাগ প্রতিটাই একটা করে feature test দিয়ে ধরা পড়ত।

**L6 — README স্টক Laravel ফাইল, `CLAUDE.md` নেই.** তিন-রোলের কনটেন্ট ওয়ার্কফ্লো, XAMPP সাবডিরেক্টরি সেটআপ, সিড করার নিয়ম — কিছুই লেখা নেই। সবচেয়ে কম পরিশ্রমে সবচেয়ে বেশি কাজে দেওয়া ফিক্স। *(ভালো দিক: `composer.lock` ও `package-lock.json` দুটোই আছে, `.git` আছে।)*

**L7 — `ActivityLog` মডেল সম্পূর্ণ অব্যবহৃত.** `ActivityLog::` বা `Models\ActivityLog` — শুধু নিজের সংজ্ঞা ছাড়া কোনো হিট নেই। **যা কিছু লগ করে না এমন একটা audit-log মডেল না থাকার চেয়েও খারাপ**, কারণ এটা এমন একটা কভারেজের ধারণা দেয় যা আসলে নেই। হয় `LogsActivity` trait দিয়ে যুক্ত করুন, নাহয় মুছে দিন।
*(বাকি ৮টা মডেল — `Advertisement`, `District`, `Setting`, `Partner`, `Comment`, `ArticleTag`, `SavedArticle`, `Redirect` — সবই সক্রিয়ভাবে ব্যবহৃত।)*

**L8 — অনাথ মাইগ্রেশন: পুরো ফিচার এরিয়ার টেবিল, কোনো মডেল/কন্ট্রোলার/ভিউ ছাড়া.** `2026_06_03_042006_create_training_tables.php` **ছয়টা** টেবিল বানায় (`courses`, `lessons`, `enrollments`, `lesson_completions`, `quiz_attempts`, `certificates`)। এছাড়া `events` ও `opinion_submissions`। খুঁজে দেখা গেছে এগুলোর নাম **শুধু মাইগ্রেশন ফাইলেই** আছে। প্রতিটা এনভায়রনমেন্টে ৮টা মৃত টেবিল, যা প্রতিটা স্কিমা পরিবর্তন ও ব্যাকআপে হিসাব করতে হয়। *(খেয়াল করার মতো: এই মাইগ্রেশন একটা `certificates` টেবিল বানায়, যা আপনার আলাদা QR সার্টিফিকেট প্রজেক্টের সাথে ধারণাগতভাবে ওভারল্যাপ করে — নিশ্চিত হয়ে নিন এগুলো একই ফিচারের দুটো অর্ধসমাপ্ত চেষ্টা নয়।)*

**L9 — `withExceptions` খালি, `app/`-এ একটাও try/catch নেই.** এটা "চুপচাপ গিলে ফেলা" নয়, বরং **অনুপস্থিতি**। ফলে slug-ভিত্তিক পাবলিক রুটে `ModelNotFoundException`-এর জন্য কোনো `renderable` নেই, আর `Admin/ArticleController.php:89-93`-এ ছবি আপলোড ব্যর্থ হলে `Article` রো ইতিমধ্যে কমিট হয়ে গেছে (কোনো transaction নেই) — ফলাফল একটা ছবিহীন আর্টিকেল ও একটা 500।

**L10 — ২৩২টা `<label>`, একটাতেও `for=` নেই** (যাচাই করা: `<label[^>]*\bfor=` → ০ ম্যাচ)। wrapping label-ও নেই। স্ক্রিন রিডার ফিল্ডের নাম না বলে শুধু "edit text, blank" বলে — পুরো অ্যাডমিন CMS ও কন্টাক্ট ফর্ম সহায়ক প্রযুক্তিতে অব্যবহার্য। মোবাইলে label-এ ট্যাপ করে ফোকাস করার সুবিধাও নষ্ট। যান্ত্রিক ফিক্স, কোনো লজিক ঝুঁকি নেই।

**L11 — মোবাইল মেনু বন্ধ থাকলেও কিবোর্ড ফোকাস নেয় (`partials/header.blade.php:128-129`).** ড্রয়ারটা `translate-x` ও `opacity` দিয়ে লুকানো, `display:none` বা `inert` দিয়ে নয় — তাই তার ~৮টা লিংক সবসময় tab order-এ থাকে। ডেস্কটপে কিবোর্ড ব্যবহারকারী হেডার থেকে ট্যাব করতে করতে ৮টা অদৃশ্য লিংকে পড়ে যান ও ফোকাস হারিয়ে ফেলেন। **ফিক্স:** বন্ধ অবস্থায় `inert` + `aria-hidden="true"`।

**L12 — স্লাইডার চিরকাল autoplay করে, থামানোর উপায় নেই.** `slider.blade.php:268-272`-এ `autoplay: { delay: 5500 }`, `pauseOnMouseEnter` নেই, pause বাটন নেই। `home.blade.php:279`-এর ticker একটা `requestAnimationFrame` লুপ যা কখনো থামে না। `prefers-reduced-motion` — পুরো `resources/`-এ শূন্য ম্যাচ। WCAG 2.2.2 Level A ব্যর্থতা, এবং স্থায়ী rAF লুপ মোবাইলে ব্যাটারি খায়।

**L13 — ৪৬টা `<img>`-এ `width`/`height` নেই; `srcset`/`<picture>`/`fetchpriority` — শূন্য.** `article/show.blade.php:95`-এ LCP ছবিটাও মাপ ছাড়া। ফলে প্রতিটা ছবি লোড হওয়ার আগে শূন্য জায়গা দখল করে, আর্টিকেল বডি লাফায় — CLS, যা Core Web Vitals র‍্যাঙ্কিং ফ্যাক্টর। `footer.blade.php:37,160`-এ ফুটার ও পার্টনার লোগোতে `loading="lazy"`-ও নেই যদিও সেগুলো স্ক্রিনের অনেক নিচে।

**M-অতিরিক্ত — Swiper CDN থেকে লোড হয়, SRI বা `defer` ছাড়া (`slider.blade.php:5,260`).** কোডবেসের একমাত্র CDN ব্যবহার। `integrity`/`crossorigin` নেই, `preconnect` নেই, আর স্টাইলশিটটা `<body>`-র মধ্যে inject হয় — অনিশ্চিত জায়গায় render-blocking। একটা নিউজ সাইটের হোমপেজে third-party supply-chain ঝুঁকি। Swiper তো npm প্যাকেজ, আর আপনার Vite ঠিকভাবেই কনফিগার করা — **ফিক্স:** `npm i swiper`, `resources/js/app.js`-এ import, দুটো CDN ট্যাগ মুছে দিন।

**L14 — Bangla-তে `strlen()` ব্যবহার (`SeoController.php:214,222`).** UTF-8-এ বাংলা অক্ষর ৩ বাইট, তাই ঠিক মাপের ৫৫-অক্ষরের শিরোনাম ১৬৫ "অক্ষর" দেখায় ও "too long" ফ্ল্যাগ পায়। অ্যাডমিন SEO ড্যাশবোর্ড কার্যত প্রতিটা আর্টিকেলে ভুল রিপোর্ট দেয়। **ফিক্স:** `mb_strlen($value, 'UTF-8')`।

**L15 — `orWhere()` বন্ধনী ছাড়া (`SeoController.php:27-33, 76-84`).** SQL-এ `AND` `OR`-এর চেয়ে শক্তভাবে বাঁধে, তাই `WHERE a AND b OR c` অনিচ্ছাকৃত রো ম্যাচ করে — "SEO ডেটা নেই" এমন আর্টিকেলের সংখ্যা ভুল দেখাচ্ছে। **ফিক্স:** `->where(function ($q) { $q->orWhere(...)->orWhere(...); })`।

**অন্যান্য ছোট বিষয়:** `SeoController.php:47`-এ ভাঙা স্ট্রিং (`ইন্ডেক্স করা যাচ্ছে No`) · `article/show.blade.php:3-4`-এ `??` ব্যবহার, যা খালি স্ট্রিং ধরে না (অ্যাডমিন SEO ফিল্ড ফাঁকা সেভ করলে `<title></title>`) · RSS/Atom ফিড নেই · `admin`/`guest` লেআউটে `noindex` নেই, তাই অ্যাডমিন লগইন পেজ ইনডেক্সেবল · `hero-grid.blade.php:51`-এ ৪টা featured স্টোরি ১০২৪px-এর নিচে সম্পূর্ণ লুকানো (`hidden lg:flex`) — মোবাইল-প্রধান দর্শকের জন্য সেগুলোর অস্তিত্বই নেই · popup ad modal-এ `role="dialog"` নেই (অথচ `admin/settings/index.blade.php:363`-এ সঠিক প্যাটার্ন আপনার কোডেই আছে) · ad impression pixel-এ `class="hidden"` (`display:none`), যা ব্রাউজার fetch না-ও করতে পারে — বিজ্ঞাপনের হিসাব কম দেখাচ্ছে · `contact/index.blade.php:55,59`-এ হার্ডকোড করা ইমেইল ও ঠিকানা, অথচ ফুটার `Setting` থেকে পড়ে · `footer.blade.php:173`-এ dofollow আউটবাউন্ড লিংক · ~১,৩০০ লাইন inline `<style>`/`<script>` Blade-এ, যা bundle বা cache হয় না এবং ভবিষ্যতের CSP-তে `unsafe-inline` বাধ্য করবে।

---

# কোন ক্রমে ঠিক করবেন

## আজ (৩০ মিনিট)
1. **মেইল ও DB পাসওয়ার্ড বদলান**, দুটো আলাদা করুন (C1)
2. `.env.example` লাইন ৫২ খালি করুন (C1)
3. প্রোডাকশনে নতুন `APP_KEY` (C2)
4. রুটে `.htaccess` দিয়ে `.env` ব্লক করুন (C2)
5. লাইভ DB-তে `admin@educationtimes.com` আছে কি না দেখুন (H3)

## এই সপ্তাহে (~১ দিন)
6. DocumentRoot `public/`-এ সরান (C2) — স্থায়ী সমাধান
7. ইনডেক্স মাইগ্রেশন (C3) — **প্রতি লাইনে সবচেয়ে বেশি লাভ**
8. `StaffMiddleware` (H1)
9. HTML purifier (H2)
10. সিডারে environment গার্ড (H3)
11. তিনটা eager-load ফিক্স — এক লাইন করে (H8, H9, M-categories)

## এই স্প্রিন্টে (~৩ দিন)
12. ছবি রিসাইজ (H6) — **সবচেয়ে বড় দৃশ্যমান পরিবর্তন**
13. `CACHE_STORE=file` + settings একবারে ক্যাশ (H7) — ৮২ কুয়েরি থেকে ~১৫
14. সাইটম্যাপ chunk + null গার্ড (H11)
15. FULLTEXT + throttle (H10)
16. `og:image` + Twitter card (H12) — কয়েক লাইন, সরাসরি ট্রাফিক ফেরায়
17. `NewsArticle` JSON-LD (H13)
18. `robots` meta + canonical (H14, L2)
19. `page_views` অ্যাগ্রিগেশন (C4)
20. মন্তব্য `pending` (H5)

## এরপর
21. একটা `ArticleService` write path (H4)
22. Form Request (M16)
23. `status` enum cast (M17) — এক লাইন, ১০০টা সমস্যা দৃশ্যমান হবে
24. `errors/` ভিউ (L1)
25. label-এ `for=` (L10)
26. `lang/bn/` (L3)
27. অনাথ মাইগ্রেশন সিদ্ধান্ত (L8)
28. H4/H5/M18-এর জন্য feature test (L5)

---

## শেষ কথা

ফিচারের দিক থেকে এটা একটা সম্পূর্ণ, কাজ করা নিউজ পোর্টাল — স্লাইডার, breaking news, স্টাফ ব্যবস্থাপনা, বিজ্ঞাপন, নিউজলেটার, SEO প্যানেল, মডারেশন, শিডিউলড পাবলিশিং। এতগুলো অংশ একসাথে কাজ করানো সহজ নয়।

সমস্যাগুলোর একটা স্পষ্ট প্যাটার্ন আছে, আর সেটা বোঝা গেলে ঠিক করা অনেক সহজ হয়। **অবকাঠামো প্রায়ই তৈরি আছে কিন্তু যুক্ত করা হয়নি:** `intervention/image` ইনস্টল করা কিন্তু অব্যবহৃত; `ArticleService` লেখা কিন্তু একটা পথ সেটা বাইপাস করে; `ArticleStatus` enum আছে কিন্তু cast করা হয়নি; canonical-এর জন্য লেআউট হুক আছে কিন্তু কেউ define করে না; `getOgImageUrlAttribute()` অ্যাবসোলিউট URL ফেরায় কিন্তু কোনো meta ট্যাগ সেটা পড়ে না; `spatie/laravel-permission` ইনস্টল করা কিন্তু `HasRoles` নেই; মডারেশন UI আছে কিন্তু স্টেটাস হার্ডকোড।

এর মানে আপনার অনেক ফিক্সই **নতুন কিছু লেখা নয়, বিদ্যমান দুটো অংশকে জোড়া লাগানো** — প্রায়ই এক থেকে তিন লাইনের কাজ। তালিকাটা লম্বা দেখালেও প্রকৃত পরিশ্রম তত বেশি নয়।

আর ইনডেক্সের ব্যাপারটা আলাদা করে বলি: `articles` টেবিলে ছয়টা কম্পোজিট ইনডেক্স যোগ করা একটা মাইগ্রেশন, হয়তো ১৫ মিনিটের কাজ, কিন্তু সাইট বড় হওয়ার সাথে সাথে এটাই সবচেয়ে বড় পার্থক্য গড়ে দেবে।

**সবার আগে অবশ্যই:** পাসওয়ার্ড বদলানো ও `.env` ফাঁস বন্ধ করা। বাকি সব অপেক্ষা করতে পারে, এই দুটো পারে না।
