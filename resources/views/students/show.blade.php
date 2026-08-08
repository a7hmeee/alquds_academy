@extends('layouts.app')

@section('title', 'تفاصيل الطالب')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 py-12 px-4">
    <div class="max-w-5xl mx-auto">
        
        <!-- الرأس -->
        <div class="mb-8">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-slate-900">
                    <i class="fas fa-user-circle text-lg"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-300 break-words">
                    {{ $student->full_name }}
                </h1>
            </div>
            <p class="text-slate-400 text-lg">معلومات الطالب الكاملة</p>
        </div>

        <!-- الأزرار العلوية -->
        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('students.edit', $student) }}"
               class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-slate-900 rounded-lg font-bold hover:shadow-lg hover:shadow-yellow-400/50 transition duration-300 transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>تعديل البيانات
            </a>
            <a href="{{ route('students.index') }}"
               class="px-6 py-3 bg-slate-700/50 border border-slate-600 text-slate-300 rounded-lg font-bold hover:border-yellow-400 hover:text-yellow-400 transition duration-300">
                <i class="fas fa-arrow-right mr-2"></i>رجوع
            </a>
        </div>

        <!-- البطاقة 1: الصورة والمعلومات الأساسية -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- الصورة -->
                @if($student->photo)
                    <img src="{{ asset('storage/'.$student->photo) }}"
                         class="w-32 h-32 rounded-full object-cover border-4 border-yellow-400/50 shadow-lg">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-slate-700 to-slate-600 flex items-center justify-center border-4 border-yellow-400/50 shadow-lg">
                        <i class="fas fa-user text-5xl text-yellow-400/50"></i>
                    </div>
                @endif

                <!-- المعلومات الأساسية -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-slate-400 text-sm">الحالة</p>
                            <p class="text-slate-100 font-semibold flex items-center gap-2">
                                @if($student->status === 'active')
                                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                    <span>🟢 نشط</span>
                                @elseif($student->status === 'paused')
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                                    <span>⏸️ موقوف</span>
                                @else
                                    <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                    <span>📦 مؤرشف</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm">رقم الهاتف</p>
                            <p class="text-slate-100 font-semibold">{{ $student->phone ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm">تاريخ الميلاد</p>
                            <p class="text-slate-100 font-semibold">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '—' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm">الجنس</p>
                            <p class="text-slate-100 font-semibold">
                                @if($student->gender === 'male') ذكر @elseif($student->gender === 'female') أنثى @else — @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- البطاقة 2: معلومات التحفظ القرآني -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                    <i class="fas fa-quran text-yellow-400"></i>
                </div>
                <h2 class="text-xl font-bold text-yellow-400">معلومات التحفظ القرآني</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- مستوى التحفظ -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <p class="text-slate-400 text-sm mb-2">مستوى التحفظ</p>
                    <p class="text-slate-100 font-bold text-lg">{{ $student->memorization_level ?? '—' }}</p>
                </div>

                <!-- مستوى التجويد -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <p class="text-slate-400 text-sm mb-2">مستوى التجويد</p>
                    <p class="text-slate-100 font-bold text-lg">{{ $student->tajweed_level ?? '—' }}</p>
                </div>

                <!-- الجزء الحالي -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <p class="text-slate-400 text-sm mb-2">الجزء الحالي</p>
                    <p class="text-slate-100 font-bold text-lg">{{ $student->current_juz ?? '—' }}</p>
                </div>

                <!-- السورة الحالية -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <p class="text-slate-400 text-sm mb-2">السورة الحالية</p>
                    <p class="text-slate-100 font-bold text-lg">{{ $student->current_surah ?? '—' }}</p>
                </div>

                <!-- الآية الحالية -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <p class="text-slate-400 text-sm mb-2">الآية الحالية</p>
                    <p class="text-slate-100 font-bold text-lg">{{ $student->current_ayah ?? '—' }}</p>
                </div>

                <!-- الخيارات -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <p class="text-slate-400 text-sm mb-3">الخيارات</p>
                    <div class="space-y-2">
                        <p class="text-slate-100 flex items-center gap-2">
                            @if($student->is_smart_mode)
                                <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                                <span>وضع ذكي</span>
                            @else
                                <span class="w-2 h-2 bg-slate-500 rounded-full"></span>
                                <span class="text-slate-400">بدون وضع ذكي</span>
                            @endif
                        </p>
                        <p class="text-slate-100 flex items-center gap-2">
                            @if($student->needs_assistance)
                                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                <span>يحتاج مساعدة</span>
                            @else
                                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                <span class="text-slate-100">لا يحتاج مساعدة</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- البطاقة 3: ولي الأمر -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                    <i class="fas fa-family text-yellow-400"></i>
                </div>
                <h2 class="text-xl font-bold text-yellow-400">بيانات ولي الأمر</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-slate-400 text-sm mb-2">الاسم</p>
                    <p class="text-slate-100 font-semibold">{{ $student->guardian_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-sm mb-2">رقم الهاتف</p>
                    <p class="text-slate-100 font-semibold">{{ $student->guardian_phone ?? '—' }}</p>
                </div>
            </div>
        </div>

        <!-- البطاقة 4: المعلم والحساب -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- المعلم -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-chalkboard-user text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">المعلم</h2>
                </div>
                <p class="text-slate-100 font-semibold">
                    {{ $student->teacher?->full_name ?? '🔲 غير محدد' }}
                </p>
                @if($student->teacher)
                    <p class="text-slate-400 text-sm mt-2">
                        📧 {{ $student->teacher->user?->email ?? '-' }}
                    </p>
                @endif
            </div>

            <!-- حساب الدخول -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                        <i class="fas fa-lock text-yellow-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-400">حساب الدخول</h2>
                </div>
                @if($student->user)
                    <p class="text-slate-100 font-semibold break-all">
                        {{ $student->user->email }}
                    </p>
                @else
                    <p class="text-slate-400 italic">لا يوجد حساب دخول</p>
                @endif
            </div>
        </div>

        <!-- البطاقة 5: الملاحظات -->
        @if($student->notes)
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                    <i class="fas fa-sticky-note text-yellow-400"></i>
                </div>
                <h2 class="text-xl font-bold text-yellow-400">الملاحظات</h2>
            </div>
            <p class="text-slate-300 leading-relaxed bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                {{ $student->notes }}
            </p>
        </div>
        @endif

        {{-- البطاقة 6: تقدم الطالب --}}
        @if(isset($progress) && $progress->count())
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-green-400/20 flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-400"></i>
                </div>
                <h2 class="text-xl font-bold text-yellow-400">تقدم الطالب (التسجيلات المعتمدة — درجة 70 فأعلى)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($progress as $item)
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/50">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-book-quran text-yellow-400"></i>
                        <span class="text-slate-100 font-bold">{{ $item['surah'] }}</span>
                    </div>
                    <div class="text-sm text-slate-400">
                        آية {{ $item['min_ayah'] ?? '—' }} → آية {{ $item['max_ayah'] ?? '—' }}
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 mt-1">
                        <span>{{ $item['count'] }} تسجيل معتمد</span>
                        <span>متوسط: {{ $item['avg_score'] ?? '—' }}/100</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- البطاقة 7: تسجيلات الطالب --}}
        @if(isset($submissions) && $submissions->count())
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl hover:border-yellow-400/40 transition duration-300 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center">
                    <i class="fas fa-microphone text-yellow-400"></i>
                </div>
                <h2 class="text-xl font-bold text-yellow-400">تسجيلات الطالب ({{ $submissions->count() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-right text-slate-400 border-b border-slate-700">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">السورة</th>
                            <th class="px-4 py-3">الجزء</th>
                            <th class="px-4 py-3">من آية</th>
                            <th class="px-4 py-3">إلى آية</th>
                            <th class="px-4 py-3">استماع</th>
                            <th class="px-4 py-3">الدرجة</th>
                            <th class="px-4 py-3">ملاحظات المعلم</th>
                            <th class="px-4 py-3">الحالة</th>
                            <th class="px-4 py-3">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $i => $sub)
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/20">
                            <td class="px-4 py-3 text-slate-300">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-slate-100 font-bold">{{ $sub->surah_display ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $sub->juz_display ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $sub->ayah_from ?? $sub->ayah ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $sub->ayah_to ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($sub->file_path)
                                    <audio controls preload="none" style="height: 32px; max-width: 180px;">
                                        <source src="{{ asset('storage/' . $sub->file_path) }}" type="audio/mpeg">
                                    </audio>
                                @else
                                    <span class="text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(!is_null($sub->score))
                                    <span class="font-bold text-lg {{ $sub->score >= 90 ? 'text-green-400' : ($sub->score >= 75 ? 'text-blue-400' : ($sub->score >= 60 ? 'text-yellow-400' : ($sub->score >= 50 ? 'text-orange-400' : 'text-red-400'))) }}">
                                        {{ $sub->score }}
                                    </span>
                                    <span class="text-slate-500 text-xs">/ 100</span>
                                @else
                                    <span class="text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-400 text-xs max-w-[200px]">
                                {{ Str::limit($sub->review_notes, 50) ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($sub->status === 'pending')
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-300">بانتظار</span>
                                @elseif($sub->status === 'accepted')
                                    <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-300">مقبول</span>
                                @elseif($sub->status === 'needs_work')
                                    <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-300">يحتاج تحسين</span>
                                @elseif($sub->status === 'reviewed')
                                    <span class="px-2 py-1 text-xs rounded bg-blue-500/20 text-blue-300">تمت المراجعة</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-400">{{ $sub->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @elseif(isset($submissions))
        <div class="bg-gradient-to-br from-slate-800 to-slate-800/50 border border-yellow-400/20 rounded-xl p-6 shadow-xl mb-6 text-center">
            <i class="fas fa-microphone-slash text-3xl text-slate-500 mb-3"></i>
            <p class="text-slate-400">لا توجد تسجيلات لهذا الطالب</p>
        </div>
        @endif

    </div>
</div>
@endsection