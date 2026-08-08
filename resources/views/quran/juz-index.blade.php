@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- الرأس -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12 shadow-lg">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-extrabold mb-2">📚 أجزاء القرآن</h1>
            <p class="text-blue-100 text-lg">30 جزء من الكتاب الحكيم</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <!-- شبكة الأجزاء -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($juzList as $juz)
            <a href="{{ route('quran.juz.show', $juz) }}" class="group">
                <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-300 overflow-hidden border-t-4 border-blue-500 h-full">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 text-center">
                        <div class="text-4xl font-extrabold mb-2">{{ $juz->id }}</div>
                        <div class="text-sm font-semibold opacity-90">الجزء</div>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-gray-700 font-semibold group-hover:text-blue-600 transition">
                            {{ $juz->name }}
                        </p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- الترقيم -->
        <div class="mt-12">
            {{ $juzList->links() }}
        </div>

        <!-- الرجوع -->
        <div class="mt-8 text-center">
            <a href="{{ route('quran.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                ← الرجوع
            </a>
        </div>
    </div>
</div>
@endsection
