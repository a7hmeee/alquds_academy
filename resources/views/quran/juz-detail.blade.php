@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- الرأس -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-5xl font-extrabold mb-2">{{ $juz->name }}</h1>
                    <p class="text-blue-100 text-lg">السور في هذا الجزء</p>
                </div>
                <div class="text-right">
                    <div class="text-5xl font-bold bg-blue-500 bg-opacity-40 rounded-lg px-6 py-3">
                        {{ $juz->id }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        @if($surahs->isNotEmpty())
            <!-- قائمة السور -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($surahs as $surah)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 p-5 border-l-4 border-blue-500">
                    <h3 class="text-xl font-bold text-blue-700 mb-1">{{ $surah->name_ar }}</h3>
                    <p class="text-sm text-gray-600">{{ $surah->name_en }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg text-center">
                <p class="text-yellow-800 text-lg font-semibold">لا توجد سور في هذا الجزء</p>
            </div>
        @endif

        <!-- الرجوع -->
        <div class="mt-12">
            <a href="{{ route('quran.juz.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                ← الرجوع للأجزاء
            </a>
        </div>
    </div>
</div>
@endsection

