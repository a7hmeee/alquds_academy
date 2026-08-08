@extends('layouts.app')

@section('title', 'تقرير المؤسسة')
@section('page-title', '📋 تقرير المؤسسة: {{ $organization->name }}')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-4">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm">🖨️ طباعة</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-white">{{ $totalCircles }}</p>
                <p class="text-gray-400 text-sm">عدد الحلقات</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-emerald-400">{{ $totalStudents }}</p>
                <p class="text-gray-400 text-sm">إجمالي الطلاب</p>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 text-center">
                <p class="text-2xl font-bold text-purple-400">{{ $totalSubmissions }}</p>
                <p class="text-gray-400 text-sm">إجمالي التسجيلات</p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <h3 class="text-lg font-bold text-white">🕌 الحلقات</h3>
            </div>
            <div class="p-4">
                @if(count($circles) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b border-gray-700">
                                    <th class="py-2 px-3">الاسم</th>
                                    <th class="py-2 px-3">النوع</th>
                                    <th class="py-2 px-3">الطلاب</th>
                                    <th class="py-2 px-3">التسجيلات</th>
                                    <th class="py-2 px-3">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($circles as $c)
                                    <tr class="border-b border-gray-700/50 text-white">
                                        <td class="py-2 px-3">
                                            <a href="{{ route('reports.circle', $c) }}" class="text-emerald-400 hover:text-emerald-300">{{ $c->name }}</a>
                                        </td>
                                        <td class="py-2 px-3 text-gray-400">{{ $c->type }}</td>
                                        <td class="py-2 px-3">{{ $c->circle_students_count }}</td>
                                        <td class="py-2 px-3">{{ $c->submissions_count }}</td>
                                        <td class="py-2 px-3">
                                            <span class="px-2 py-0.5 rounded-full text-xs {{ $c->status === 'active' ? 'bg-emerald-900/50 text-emerald-400' : 'bg-red-900/50 text-red-400' }}">
                                                {{ $c->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا توجد حلقات</p>
                @endif
            </div>
        </div>
    </div>
@endsection