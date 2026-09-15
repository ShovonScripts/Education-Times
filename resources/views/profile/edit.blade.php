@extends('layouts.app')

@section('title', 'প্রোফাইল সম্পাদনা — ' . config('app.name'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-1 text-sm text-[#666] hover:text-[#111] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            প্রোফাইলে ফিরুন
        </a>
    </div>

    <div class="bg-white border border-[#e5e5e5] p-8">
        <h1 class="font-serif font-black text-2xl text-[#111] mb-8">প্রোফাইল সম্পাদনা</h1>

        @if (session('success'))
            <div class="bg-[#f5f5f5] border-l-4 border-[#111] p-4 mb-6 text-sm text-[#111]">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6">
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">নাম *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full border border-[#e5e5e5] bg-[#f5f5f5] text-[#888] px-4 py-2.5 text-sm cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ফোন</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">দেশ</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}" placeholder="বাংলাদেশ" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">জন্মদিন</label>
                    <input type="date" name="birthday" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">লিঙ্গ</label>
                    <select name="gender" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm bg-white">
                        <option value="">নির্বাচন করুন</option>
                        <option value="পুরুষ" @selected(old('gender', $user->gender) == 'পুরুষ')>পুরুষ</option>
                        <option value="নারী" @selected(old('gender', $user->gender) == 'নারী')>নারী</option>
                        <option value="অন্যান্য" @selected(old('gender', $user->gender) == 'অন্যান্য')>অন্যান্য</option>
                    </select>
                </div>
            </div>

            <div class="border-t-2 border-[#111] pt-5 mt-5">
                <h3 class="font-serif font-black text-sm text-[#111] mb-4 uppercase tracking-wider">ঠিকানা ও পেশাগত তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">জেলা</label>
                        <select name="district_id" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm bg-white">
                            <option value="">নির্বাচন করুন</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" @selected(old('district_id', $user->district_id) == $district->id)>{{ $district->name_bn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">উপজেলা</label>
                        <input type="text" name="upazila" value="{{ old('upazila', $user->upazila) }}" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">বিদ্যালয়</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $user->school_name) }}" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পদবী</label>
                        <select name="designation" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm bg-white">
                            <option value="">নির্বাচন করুন</option>
                            <option value="সহকারী শিক্ষক" @selected(old('designation', $user->designation) == 'সহকারী শিক্ষক')>সহকারী শিক্ষক</option>
                            <option value="প্রধান শিক্ষক" @selected(old('designation', $user->designation) == 'প্রধান শিক্ষক')>প্রধান শিক্ষক</option>
                            <option value="সহকারী প্রধান শিক্ষক" @selected(old('designation', $user->designation) == 'সহকারী প্রধান শিক্ষক')>সহকারী প্রধান শিক্ষক</option>
                            <option value="সিনিয়র শিক্ষক" @selected(old('designation', $user->designation) == 'সিনিয়র শিক্ষক')>সিনিয়র শিক্ষক</option>
                            <option value="ইন্সট্রাক্টর" @selected(old('designation', $user->designation) == 'ইন্সট্রাক্টর')>ইন্সট্রাক্টর</option>
                            <option value="অন্যান্য" @selected(old('designation', $user->designation) == 'অন্যান্য')>অন্যান্য</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t-2 border-[#111] mt-5">
                <button type="submit" class="bg-[#111] text-white px-6 py-2.5 text-sm font-semibold uppercase tracking-wider hover:bg-black transition">সংরক্ষণ করুন</button>
                <a href="{{ route('profile.show') }}" class="border border-[#111] text-[#111] px-6 py-2.5 text-sm font-semibold uppercase tracking-wider hover:bg-[#111] hover:text-white transition">বাতিল</a>
            </div>
        </form>
    </div>
</div>
@endsection
