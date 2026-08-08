@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-emerald-100">
    <!-- الرأس -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-12 shadow-lg">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-extrabold mb-2">📖 السور</h1>
            <p class="text-emerald-100 text-lg">114 سورة من الكتاب الحكيم</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <!-- البحث -->
        <div class="flex gap-2 mb-12 justify-center max-w-md mx-auto">
            <form action="{{ route('quran.search') }}" method="GET" class="flex gap-2 w-full">
                <input type="text" name="q" placeholder="ابحث عن سورة..." class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:outline-none">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold transition">بحث</button>
            </form>
        </div>

        <!-- الروابط السريعة -->
        <div class="flex gap-3 mb-12 justify-center flex-wrap">
            <a href="{{ route('quran.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold transition">🔖 السور</a>
            <a href="{{ route('quran.juz.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">📚 الأجزاء</a>
        </div>

        <!-- شبكة السور -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($surahs as $surah)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden border-l-4 border-emerald-500">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-emerald-700 mb-1">{{ $surah->name_ar }}</h3>
                            <p class="text-sm text-gray-600">{{ $surah->name_en }}</p>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $surah->id }}</span>
                    </div>

                    <div class="flex gap-3 text-sm text-gray-700">
                        <span class="flex items-center gap-1">
                            <span>{{ $surah->revelation_place === 'مكية' ? '🕌' : '🌆' }}</span>
                            <span>{{ $surah->revelation_place === 'مكية' ? 'مكية' : 'مدنية' }}</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span>📝</span>
                            <span>{{ $surah->verses_count }} آية</span>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- الترقيم -->
        <div class="mt-12">
            {{ $surahs->links() }}
        </div>
    </div>
</div>
@endsection
