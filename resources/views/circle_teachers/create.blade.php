@extends('layouts.app')

@section('title', 'إضافة معلّم للحلقة')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-green-50 rounded-lg">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    إضافة معلّم للحلقة
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    تعيين معلم جديد للحلقة الدراسية
                </p>
            </div>
        </div>

        <a href="{{ route('circles.show', $circle) }}"
           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            الرجوع للحلقة
        </a>
    </div>

    {{-- Circle Info Card --}}
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl p-5">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-sm font-medium text-green-800">الحلقة المحددة</p>
                </div>
                <p class="font-bold text-xl text-gray-800">{{ $circle->name }}</p>
            </div>
            <div class="bg-white rounded-lg px-3 py-1 border border-green-200">
                <span class="text-sm font-medium text-green-700">{{ $circle->code ?? 'بدون كود' }}</span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">
                بيانات المعلم الجديد
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                قم بتعيين معلم جديد للحلقة مع تحديد دوره وحالته
            </p>
        </div>

        <form method="POST"
              action="{{ route('circle-teachers.store') }}"
              class="p-6 space-y-6">
            @csrf

            <input type="hidden" name="circle_id" value="{{ $circle->id }}">

            {{-- Teacher Selection --}}
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        اختر المعلم
                    </span>
                    <span class="text-red-500 text-xs font-normal">* مطلوب</span>
                </label>
                
                <div class="relative">
                    <select name="teacher_id"
                            required
                            class="w-full h-12 px-4 pr-12 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all appearance-none bg-white">
                        <option value="" class="text-gray-400">— اختر معلّم من القائمة —</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" class="py-2">
                                {{ $teacher->full_name ?? $teacher->user->name }} — ({{ $teacher->user?->email ?? '-' }})
                                @if($teacher->specialization)
                                    <span class="text-gray-500 text-sm">({{ $teacher->specialization }})</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                
                @error('teacher_id')
                    <div class="flex items-center gap-2 text-red-600 text-sm mt-2 p-3 bg-red-50 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Role & Status Grid --}}
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Role --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            دور المعلّم
                        </span>
                    </label>
                    <select name="role"
                            class="w-full h-12 px-4 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all bg-white">
                        <option value="primary" class="flex items-center gap-2 py-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            معلّم أساسي
                        </option>
                        <option value="assistant" class="flex items-center gap-2 py-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            مساعد
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        المعلم الأساسي هو المسؤول الرئيسي عن الحلقة
                    </p>
                </div>

                {{-- Status --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            حالة التعيين
                        </span>
                    </label>
                    <select name="status"
                            class="w-full h-12 px-4 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all bg-white">
                        <option value="active" class="flex items-center gap-2 py-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            نشط
                        </option>
                        <option value="paused" class="flex items-center gap-2 py-2">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                            موقوف مؤقتًا
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        يحدد هذا حالة المعلم في الحلقة
                    </p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100 my-2"></div>

            {{-- Actions --}}
            <div class="flex flex-wrap justify-end gap-3 pt-4">
                <a href="{{ route('circles.show', $circle) }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    إلغاء
                </a>

                <button type="submit"
                        class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ وتثبيت المعلم
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-medium text-blue-800">ملاحظة مهمة</p>
                <p class="text-sm text-blue-600 mt-1">
                    يمكن تعيين أكثر من معلم للحلقة الواحدة. المعلم الأساسي هو المسؤول الأول عن متابعة الحلقة وتقاريرها.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
select option {
    padding: 12px;
    margin: 4px 0;
    border-radius: 8px;
}

select option:hover {
    background-color: #f0fdf4;
}

select option:checked {
    background-color: #059669;
    color: white;
}
</style>
@endpush