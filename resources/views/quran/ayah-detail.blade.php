@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- الرأس -->
    <div class="bg-purple-50 border-l-4 border-purple-500 p-6 mb-8 rounded">
        <div class="text-right">
            <h1 class="text-3xl font-bold text-purple-600 mb-2">{{ $surah->name_ar }}</h1>
            <p class="text-gray-600">الآية {{ $ayah->ayah_number }}</p>
        </div>
    </div>

    <!-- المعلومات -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">السورة</p>
            <p class="text-lg font-bold text-blue-600">{{ $surah->name_ar }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">رقم الآية</p>
            <p class="text-lg font-bold text-green-600">{{ $ayah->ayah_number }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">الجزء</p>
            <p class="text-lg font-bold text-orange-600">{{ $juz->name }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">نوع النزول</p>
            <p class="text-lg font-bold text-purple-600">{{ $surah->revelation_place }}</p>
        </div>
    </div>

    <!-- الآية -->
    <div class="bg-white rounded shadow p-8 mb-8 border-l-4 border-purple-500">
        <p class="text-3xl leading-loose text-right mb-4" dir="rtl">
            {{ $ayah->text }}
        </p>
        <p class="text-right text-gray-600">
            <span class="font-bold">{{ $surah->name_ar }}</span> - 
            <span class="font-bold">الآية {{ $ayah->ayah_number }}</span>
        </p>
    </div>

    <!-- التفاصيل -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold mb-4">📋 معلومات السورة</h3>
            <ul class="space-y-2 text-right" dir="rtl">
                <li><span class="font-bold">الاسم العربي:</span> {{ $surah->name_ar }}</li>
                <li><span class="font-bold">الاسم الإنجليزي:</span> {{ $surah->name_en }}</li>
                <li><span class="font-bold">رقم السورة:</span> {{ $surah->id }}</li>
                <li><span class="font-bold">نوع النزول:</span> {{ $surah->revelation_place }}</li>
                <li><span class="font-bold">عدد الآيات:</span> {{ $surah->verses_count }}</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold mb-4">📚 معلومات الجزء</h3>
            <ul class="space-y-2 text-right" dir="rtl">
                <li><span class="font-bold">اسم الجزء:</span> {{ $juz->name }}</li>
                <li><span class="font-bold">رقم الجزء:</span> {{ $juz->id }}</li>
            </ul>
        </div>
    </div>

    <!-- الملاحة -->
    <div class="flex gap-4 mb-8">
        @if($previousAyah)
            <a href="{{ route('quran.ayah.show', [$surah->id, $previousAyah->ayah_number]) }}" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                ← الآية السابقة
            </a>
        @endif

        @if($nextAyah)
            <a href="{{ route('quran.ayah.show', [$surah->id, $nextAyah->ayah_number]) }}" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                الآية التالية →
            </a>
        @endif
    </div>

    <!-- الرجوع -->
    <div>
        <a href="{{ route('quran.surah.show', $surah) }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
            ← العودة لسورة {{ $surah->name_ar }}
        </a>
    </div>
</div>
@endsection
