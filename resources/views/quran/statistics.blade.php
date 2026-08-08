@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- الرأس -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-center mb-4">📊 إحصائيات القرآن الكريم</h1>
    </div>

    <!-- الملخص -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded shadow">
            <p class="text-gray-200 text-sm">إجمالي السور</p>
            <p class="text-4xl font-bold">{{ $stats['summary']['total_surahs'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded shadow">
            <p class="text-gray-200 text-sm">إجمالي الآيات</p>
            <p class="text-4xl font-bold">{{ $stats['summary']['total_ayahs'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded shadow">
            <p class="text-gray-200 text-sm">إجمالي الأجزاء</p>
            <p class="text-4xl font-bold">{{ $stats['summary']['total_juz'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded shadow">
            <p class="text-gray-200 text-sm">نوع النزول</p>
            <p class="text-sm mt-2">
                مكي: {{ $stats['summary']['meccan_surahs'] }} | 
                مدني: {{ $stats['summary']['madinan_surahs'] }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- أطول وأقصر سورة -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-2xl font-bold mb-4">📍 المواقع</h3>
            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <p class="text-gray-600 text-sm">أطول سورة</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['longest_surah']->name_ar }}</p>
                    <p class="text-sm text-gray-600">{{ $stats['longest_surah']->verses_count }} آية</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <p class="text-gray-600 text-sm">أقصر سورة</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['shortest_surah']->name_ar }}</p>
                    <p class="text-sm text-gray-600">{{ $stats['shortest_surah']->verses_count }} آية</p>
                </div>
            </div>
        </div>

        <!-- الأجزاء -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-2xl font-bold mb-4">📚 توزيع الآيات على الأجزاء</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($stats['ayahs_per_juz'] as $juz)
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">{{ $juz->name }}</span>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">{{ $juz->ayahs_count }} آية</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- السور -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-2xl font-bold mb-4">📖 جميع السور</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right" dir="rtl">
                <thead class="bg-gray-100 border-b-2 border-gray-300">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">اسم السورة</th>
                        <th class="px-4 py-2">الآيات (معلن)</th>
                        <th class="px-4 py-2">الآيات (فعلي)</th>
                        <th class="px-4 py-2">النوع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['surahs_list'] as $surah)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 text-gray-600">{{ $surah['id'] }}</td>
                        <td class="px-4 py-2 font-bold">{{ $surah['name'] }}</td>
                        <td class="px-4 py-2">{{ $surah['verses'] }}</td>
                        <td class="px-4 py-2">{{ $surah['actual_verses'] }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm 
                                @if($surah['revelation'] === 'مكية') 
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-green-100 text-green-800
                                @endif
                            ">
                                {{ $surah['revelation'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- الرجوع -->
    <div class="mt-8">
        <a href="{{ route('quran.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
            ← الرجوع للسور
        </a>
    </div>
</div>
@endsection
