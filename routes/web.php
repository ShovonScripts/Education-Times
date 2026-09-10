<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\ArchiveDocumentController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController as UserCommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\StaffArticleController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{slug}', [ArticleController::class, 'show'])->name('article.show');
Route::get('/category/{slug}', [ArticleController::class, 'category'])->name('article.category');
Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/videos', [\App\Http\Controllers\VideoController::class, 'index'])->name('videos.index');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
Route::get('/staff/{staff}/articles', [StaffController::class, 'articles'])->name('staff.articles');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'showRobotsTxt'])->name('robots.txt');
Route::get('/ads/click/{ad}', [\App\Http\Controllers\Admin\AdController::class, 'click'])->name('admin.ads.click');
Route::get('/ads/impression/{ad}', [\App\Http\Controllers\Admin\AdController::class, 'impression'])->name('admin.ads.impression');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('pages.terms');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
    Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('google.callback');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/post', [DashboardController::class, 'storePost'])->name('dashboard.post.store');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/like/{article}', [LikeController::class, 'toggle'])->name('profile.like.toggle');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout-admin', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::post('/comments', [UserCommentController::class, 'store'])->middleware('throttle:10,1')->name('comments.store');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', 'ইমেইল ভেরিফিকেশন সফল!');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'ভেরিফিকেশন ইমেইল আবার পাঠানো হয়েছে।');
    })->name('verification.send');
});

Route::middleware(['auth', 'admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/articles', [StaffArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [StaffArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [StaffArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [StaffArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [StaffArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [StaffArticleController::class, 'destroy'])->name('articles.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/pending', [PostController::class, 'pending'])->name('pending');
        Route::get('/slider', [PostController::class, 'slider'])->name('slider');
        Route::get('/breaking', [PostController::class, 'breaking'])->name('breaking');
        Route::get('/featured', [PostController::class, 'featured'])->name('featured');
        Route::get('/scheduled', [PostController::class, 'scheduled'])->name('scheduled');
        Route::post('/{article}/toggle-flag', [PostController::class, 'toggleFlag'])->name('toggle-flag');
        Route::post('/{article}/update-status', [PostController::class, 'updateStatus'])->name('update-status');
        Route::post('/slider/reorder', [PostController::class, 'updateSliderOrder'])->name('slider-reorder');
        Route::post('/bulk', [PostController::class, 'bulkAction'])->name('bulk');
    });

    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/editor-image', [AdminArticleController::class, 'editorImage'])->name('articles.editor-image');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/{contact}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggle-role');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [AdminStaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [AdminStaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [AdminStaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [AdminStaffController::class, 'destroy'])->name('staff.destroy');

    Route::get('/archive', [ArchiveDocumentController::class, 'index'])->name('archive.index');
    Route::get('/archive/create', [ArchiveDocumentController::class, 'create'])->name('archive.create');
    Route::post('/archive', [ArchiveDocumentController::class, 'store'])->name('archive.store');
    Route::get('/archive/{archiveDocument}/edit', [ArchiveDocumentController::class, 'edit'])->name('archive.edit');
    Route::put('/archive/{archiveDocument}', [ArchiveDocumentController::class, 'update'])->name('archive.update');
    Route::delete('/archive/{archiveDocument}', [ArchiveDocumentController::class, 'destroy'])->name('archive.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/clear-data', [SettingController::class, 'clearData'])->name('settings.clear-data');

    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
    Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::post('/ads/{ad}/toggle-active', [AdController::class, 'toggleActive'])->name('ads.toggle-active');
    Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');

    Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::get('/pages/{slug}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{slug}', [AdminPageController::class, 'update'])->name('pages.update');

    Route::get('/seo', [SeoController::class, 'dashboard'])->name('seo.dashboard');
    Route::get('/seo/bulk-editor', [SeoController::class, 'bulkEditor'])->name('seo.bulk-editor');
    Route::post('/seo/bulk-update', [SeoController::class, 'bulkUpdate'])->name('seo.bulk-update');
    Route::get('/seo/robots', [SeoController::class, 'robotsEditor'])->name('seo.robots');
    Route::post('/seo/robots', [SeoController::class, 'robotsUpdate'])->name('seo.robots.update');
    Route::get('/seo/redirects', [SeoController::class, 'redirects'])->name('seo.redirects');
    Route::post('/seo/redirects', [SeoController::class, 'redirectStore'])->name('seo.redirects.store');
    Route::put('/seo/redirects/{redirect}', [SeoController::class, 'redirectUpdate'])->name('seo.redirects.update');
    Route::delete('/seo/redirects/{redirect}', [SeoController::class, 'redirectDestroy'])->name('seo.redirects.destroy');
    Route::get('/seo/article/{article}/analysis', [SeoController::class, 'articleSeoAnalysis'])->name('seo.article-analysis');

    Route::get('/newsletter', [NewsletterSubscriberController::class, 'index'])->name('newsletter.index');
    Route::post('/newsletter/{subscriber}/toggle-active', [NewsletterSubscriberController::class, 'toggleActive'])->name('newsletter.toggle-active');
    Route::delete('/newsletter/{subscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('newsletter.destroy');
});
