@extends('layouts.app')

@section('title','تعديل الأستاذ في الحلقة')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <h1 class="text-xl font-bold">
            تعديل إعدادات الأستاذ داخل الحلقة
        </h1>

        <a href="{{ route('circles.show', $circleTeacher->circle_id) }}"
           class="text-sm text-gray-600 hover:underline">
            ← الرجوع للحلقة
        </a>
    </div>

    <div class="bg-white border rounded-xl p-6 space-y-6">

        {{-- معلومات ثابتة (عرض فقط) --}}
        <div class="space-y-1 text-sm text-gray-700">
            <p>
                <strong>الأستاذ:</strong>
                {{ $circleTeacher->teacher->full_name }}
            </p>
            <p>
                <strong>الدور الحالي:</strong>
                {{ $circleTeacher->role == 'primary' ? 'أساسي' : 'مساعد' }}
            </p>
        </div>

        {{-- Form --}}
        <form method="POST"
              action="{{ route('circle-teachers.update', $circleTeacher) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- الدور داخل الحلقة --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    الدور داخل الحلقة
                </label>
                <select name="role"
                        class="w-full rounded-lg border-gray-300 focus:border-green-700 focus:ring-green-700">
                    <option value="primary"
                        @selected($circleTeacher->role === 'primary')>
                        أستاذ أساسي
                    </option>
                    <option value="assistant"
                        @selected($circleTeacher->role === 'assistant')>
                        أستاذ مساعد
                    </option>
                </select>
            </div>

            {{-- الحالة --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    الحالة داخل الحلقة
                </label>
                <select name="status"
                        class="w-full rounded-lg border-gray-300 focus:border-green-700 focus:ring-green-700">
                    <option value="active"
                        @selected($circleTeacher->status === 'active')>
                        نشط
                    </option>
                    <option value="paused"
                        @selected($circleTeacher->status === 'paused')>
                        موقوف
                    </option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap justify-end gap-3 pt-4 border-t">
                <a href="{{ route('circles.show', $circleTeacher->circle_id) }}"
                   class="px-4 py-2 border rounded-lg">
                    إلغاء
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-green-700 text-white hover:bg-green-800">
                    حفظ التعديل
                </button>
            </div>

        </form>

    </div>

</div>
@endsection