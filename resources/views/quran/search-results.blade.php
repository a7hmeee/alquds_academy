@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-emerald-100">
    <!-- الرأس -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-12 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl font-extrabold mb-2">🔍 نتائج البحث</h1>
            <p class="text-emerald-100 text-lg">عن: <strong>"{{ $query }}"</strong></p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        @if($surahs->count() > 0)
            <div class="mb-6 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                <p class="text-emerald-800"><strong>{{ $surahs->count() }}</strong> سورة تحتوي على نتائج البحث</p>
            </div>

            <!-- شبكة السور -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @foreach($surahs as $surah)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 p-6 border-l-4 border-emerald-500">
                    <h3 class="text-xl font-bold text-emerald-700 mb-2">{{ $surah->name_ar }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $surah->name_en }}</p>
                    @if($surah->ayahs->count() > 0)
                    <p class="text-sm bg-emerald-50 text-emerald-800 rounded px-3 py-1 inline-block font-semibold">
                        {{ $surah->ayahs->count() }} تطابق
                    </p>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- الترقيم -->
            <div class="mt-8">
                {{ $surahs->links() }}
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-8 rounded-lg text-center">
                <p class="text-yellow-800 text-lg font-semibold">😔 لا توجد نتائج</p>
                <p class="text-yellow-700 mt-2">حاول باستخدام كلمات أخرى</p>
            </div>
        @endif

        <!-- الرجوع -->
        <div class="mt-8 flex gap-3 justify-center">
            <a href="{{ route('quran.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                ← الرجوع للسور
            </a>
        </div>
    </div>
</div>
@endsection
