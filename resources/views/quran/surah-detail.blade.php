@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- الرأس -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded">
        <div class="flex flex-wrap justify-between items-start gap-2">
            <div>
                <h1 class="text-4xl font-bold text-blue-600 mb-2">{{ $surah->name_ar }}</h1>
                <p class="text-gray-600 text-lg">{{ $surah->name_en }}</p>
            </div>
            <span class="text-3xl font-bold text-blue-500">السورة #{{ $surah->id }}</span>
        </div>
    </div>

    <!-- المعلومات -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">نوع النزول</p>
            <p class="text-2xl font-bold text-blue-600">{{ $surah->revelation_place }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">عدد الآيات</p>
            <p class="text-2xl font-bold text-green-600">{{ $surah->verses_count }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">الأجزاء</p>
            <p class="text-2xl font-bold text-purple-600">
                {{ $statistics['juz_list']->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-600 text-sm">الآيات الفعلية</p>
            <p class="text-2xl font-bold text-orange-600">{{ $statistics['ayahs_count'] }}</p>
        </div>
    </div>

    <!-- الآيات -->
    <div class="bg-white rounded shadow">
        <div class="border-b px-6 py-4">
            <h2 class="text-2xl font-bold">الآيات</h2>
        </div>

        <div class="divide-y">
            @foreach($ayahs as $ayah)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex gap-4">
                    @if($ayah->ayah_number > 0)
                    <span class="flex-shrink-0 bg-blue-100 text-blue-800 px-3 py-1 rounded min-w-fit h-fit">
                        آية {{ $ayah->ayah_number }}
                    </span>
                    @else
                    <span class="flex-shrink-0 bg-amber-100 text-amber-800 px-3 py-1 rounded min-w-fit h-fit font-semibold">
                        البسملة
                    </span>
                    @endif
                    <div class="flex-grow">
                        <p class="text-xl leading-relaxed text-right mb-2" dir="rtl">
                            {{ $ayah->text }}
                        </p>
                        <div class="text-sm text-gray-600 flex gap-4 justify-end" dir="rtl">
                            <span>📑 {{ $ayah->juz->name }}</span>
                            <a href="{{ route('quran.ayah.show', [$surah->id, $ayah->ayah_number]) }}" class="text-blue-500 hover:underline">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- الترقيم -->
        <div class="p-6 border-t">
            {{ $ayahs->links() }}
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
