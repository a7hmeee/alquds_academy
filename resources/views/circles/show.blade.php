@extends('layouts.app')

@section('title','تفاصيل الحلقة')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--cream)]">{{ $circle->name }}</h1>
            <p class="text-sm text-[var(--slate-blue)] mt-1">
                {{ $circle->organization?->name ?? 'بدون جهة' }} • {{ $circle->type }} • {{ $circle->status }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('circles.progress.index', $circle) }}"
               class="px-4 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">
                سجل التقدّم
            </a>

            <a href="{{ route('circles.edit',$circle) }}"
               class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">
                <i class="fas fa-pen ml-1"></i> تعديل
            </a>

            <a href="{{ route('circles.index') }}"
               class="px-4 py-2 rounded-lg border border-[var(--border)] text-[var(--cream)] hover:bg-[var(--deep-green)]/20">
                ← رجوع
            </a>
        </div>
    </div>

    {{-- Basic Info --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-2">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div><span class="text-[var(--slate-blue)]">المستوى:</span> <b class="text-[var(--cream)]">{{ $circle->level ?? '-' }}</b></div>
            <div><span class="text-[var(--slate-blue)]">السعة:</span> <b class="text-[var(--cream)]">{{ $circle->capacity ?? '-' }}</b></div>
            <div><span class="text-[var(--slate-blue)]">الجزء:</span> <b class="text-[var(--gold)]">{{ $circle->juz?->name ?? 'غير محدد' }}</b></div>
            <div><span class="text-[var(--slate-blue)]">الوصف:</span> <b class="text-[var(--cream)]">{{ $circle->description ?? '-' }}</b></div>
        </div>
    </div>

    {{-- Juz Progress Overview for all students --}}
    @if($circle->juz_id && $circle->circleStudents->count())
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-[var(--cream)]">
                <i class="fas fa-chart-bar text-[var(--gold)] ml-2"></i>
                تقدم الطلاب — {{ $circle->juz?->name ?? 'الجزء ' . $circle->juz_id }}
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 6px;">
                <thead>
                    <tr class="text-right text-[var(--slate-blue)]">
                        <th class="px-4 py-2">الطالب</th>
                        <th class="px-4 py-2">نسبة الحفظ</th>
                        <th class="px-4 py-2">الآيات</th>
                        <th class="px-4 py-2">السور المكتملة</th>
                        <th class="px-4 py-2">التفاصيل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($circle->circleStudents as $cs)
                        @php
                            $prog = $studentsProgress[$cs->student?->id] ?? null;
                            $pct = $prog['total_percent'] ?? 0;
                            $completedSurahs = $prog ? $prog['surahs']->where('percent', '>=', 100)->count() : 0;
                            $totalSurahs = $prog ? $prog['surahs']->count() : 0;
                        @endphp
                        <tr class="bg-[var(--dark-bg)]/30 rounded-lg">
                            <td class="px-4 py-3">
                                <div class="font-bold text-[var(--cream)]">{{ $cs->student?->user?->name ?? $cs->student?->full_name ?? 'بدون اسم' }}</div>
                                <div class="text-xs text-[var(--slate-blue)]">{{ $cs->student?->user?->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-2.5 bg-[var(--dark-bg)] rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500"
                                             style="width: {{ $pct }}%; background: {{ $pct >= 100 ? '#10B981' : ($pct >= 50 ? '#FFD700' : '#60a5fa') }};"></div>
                                    </div>
                                    <span class="font-bold text-sm {{ $pct >= 100 ? 'text-green-400' : ($pct >= 50 ? 'text-[var(--gold)]' : 'text-blue-400') }}">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-[var(--cream)]">
                                {{ $prog['covered_ayahs'] ?? 0 }} / {{ $prog['total_ayahs'] ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-[var(--cream)]">
                                {{ $completedSurahs }} / {{ $totalSurahs }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('circles.students.recordings', [$circle, $cs->student]) }}"
                                   class="px-3 py-1 rounded bg-[var(--gold)] text-[var(--dark-bg)] font-bold text-xs">
                                    <i class="fas fa-eye ml-1"></i> عرض
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Teachers Section --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-[var(--cream)]">
                <i class="fas fa-chalkboard-teacher text-[var(--gold)] ml-2"></i>
                أساتذة الحلقة
            </h2>
        </div>

        {{-- Add teacher form --}}
        <form method="POST" action="{{ route('circles.teachers.store', $circle) }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 rounded-lg border border-[var(--border)] bg-[var(--dark-bg)]/20">
            @csrf

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">اختر الأستاذ/الأساتذة</label>
                <select name="teacher_profile_ids[]" multiple required
                        class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                    @foreach($availableTeachers as $t)
                        <option value="{{ $t->id }}">
                            {{ $t->user?->name ?? 'بدون اسم' }} — ({{ $t->user?->email ?? 'بدون ايميل' }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_profile_ids')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('teacher_profile_ids.*')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الدور</label>
                <select name="role" class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="primary">رئيسي</option>
                    <option value="assistant">مساعد</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحالة</label>
                <select name="status" class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="active">نشط</option>
                    <option value="paused">موقوف</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button class="px-6 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">
                    <i class="fas fa-plus ml-1"></i> إضافة
                </button>
            </div>
        </form>

        {{-- Teachers list (table) — use circleTeachers (CircleTeacher model) --}}
        @if($circle->circleTeachers->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-left text-[var(--slate-blue)]">
                            <th class="px-4 py-2">الاسم</th>
                            <th class="px-4 py-2">الإيميل</th>
                            <th class="px-4 py-2">الدور</th>
                            <th class="px-4 py-2">الحالة</th>
                            <th class="px-4 py-2 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($circle->circleTeachers as $ct)
                            <tr class="bg-[var(--surface)] rounded-lg overflow-hidden">
                                <td class="px-4 py-3 font-bold text-[var(--cream)]">
                                    {{ $ct->teacher?->user?->name ?? $ct->teacher?->full_name ?? 'بدون اسم' }}
                                </td>
                                <td class="px-4 py-3 text-[var(--slate-blue)]">{{ $ct->teacher?->user?->email ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $ct->role }}</td>
                                <td class="px-4 py-3">{{ $ct->status }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('circle-teachers.update',$ct) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="px-2 py-1 rounded bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                                            <option value="primary" @selected($ct->role=='primary')>رئيسي</option>
                                            <option value="assistant" @selected($ct->role=='assistant')>مساعد</option>
                                        </select>
                                        <select name="status" class="px-2 py-1 rounded bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                                            <option value="active" @selected($ct->status=='active')>نشط</option>
                                            <option value="paused" @selected($ct->status=='paused')>موقوف</option>
                                        </select>
                                        <button class="px-3 py-1 rounded bg-[var(--deep-green)] text-white">تحديث</button>
                                    </form>

                                    <form method="POST" action="{{ route('circle-teachers.destroy',$ct) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-red-600 text-white" onclick="return confirm('إزالة الأستاذ من الحلقة؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-sm text-[var(--slate-blue)]">لا يوجد أساتذة مرتبطين بهذه الحلقة بعد.</div>
        @endif
    </div>

    {{-- Students Section --}}
    <div class="p-6 rounded-xl border border-[var(--border)] bg-[var(--surface)] space-y-5">
        <h2 class="text-lg font-bold text-[var(--cream)]">
            <i class="fas fa-user-graduate text-[var(--gold)] ml-2"></i>
            طلاب الحلقة
        </h2>

        {{-- Add student form --}}
        <form method="POST" action="{{ route('circles.students.store', $circle) }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 rounded-lg border border-[var(--border)] bg-[var(--dark-bg)]/20">
            @csrf

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">اختر الطالب</label>
                <select name="student_id" required
                        class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="">— اختر —</option>
                    @foreach($availableStudents as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->user?->name ?? $s->full_name ?? 'بدون اسم' }} — ({{ $s->user?->email ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">الحالة</label>
                <select name="status" class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                    <option value="active">نشط</option>
                    <option value="paused">موقوف</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-[var(--cream)]">تاريخ الانضمام</label>
                <input type="date" name="joined_at"
                       class="w-full px-4 py-3 rounded-lg bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button class="px-6 py-2 rounded-lg bg-[var(--gold)] text-[var(--dark-bg)] font-bold">
                    <i class="fas fa-plus ml-1"></i> إضافة
                </button>
            </div>
        </form>

        {{-- Students list (table) — use circleStudents (CircleStudent model) --}}
        @if($circle->circleStudents->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto border-separate" style="border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-left text-[var(--slate-blue)]">
                            <th class="px-4 py-2">الاسم</th>
                            <th class="px-4 py-2">الإيميل</th>
                            <th class="px-4 py-2">المعلم المسؤول</th>
                            <th class="px-4 py-2">الحالة</th>
                            <th class="px-4 py-2">تاريخ الانضمام</th>
                            <th class="px-4 py-2 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($circle->circleStudents as $cs)
                            <tr class="bg-[var(--surface)] rounded-lg overflow-hidden">
                                <td class="px-4 py-3 font-bold text-[var(--cream)]">{{ $cs->student?->user?->name ?? $cs->student?->full_name ?? 'بدون اسم' }}</td>
                                <td class="px-4 py-3 text-[var(--slate-blue)]">{{ $cs->student?->user?->email ?? '-' }}</td>

                                <td class="px-4 py-3">
                                    @if($studentHasTeacherColumn)
                                        @if($circle->circleTeachers->count())
                                            <form method="POST" action="{{ route('circle-students.update', $cs) }}" class="flex gap-2 items-center">
                                                @csrf
                                                @method('PUT')
                                                <select name="teacher_id" class="px-3 py-2 rounded bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                                                    <option value="">— بدون —</option>
                                                    @foreach($circle->circleTeachers as $ct)
                                                        <option value="{{ $ct->teacher?->id }}"
                                                            @selected(optional($cs->student)->teacher_id == $ct->teacher?->id)>
                                                            {{ $ct->teacher?->user?->name ?? $ct->teacher?->full_name ?? 'أستاذ' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button class="px-3 py-1 rounded bg-[var(--gold)] text-[var(--dark-bg)]">ربط</button>
                                            </form>
                                        @else
                                            <div class="text-xs text-[var(--slate-blue)]">لا يوجد أساتذة للحلقة</div>
                                        @endif
                                    @else
                                        <div class="text-xs text-yellow-400">عمود <code>teacher_id</code> غير موجود في جدول <code>student_profiles</code> — الربط لن يُحفظ.</div>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('circle-students.update',$cs) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="px-2 py-1 rounded bg-[var(--surface)] border border-[var(--border)] text-[var(--cream)]">
                                            <option value="active" @selected($cs->status=='active')>نشط</option>
                                            <option value="paused" @selected($cs->status=='paused')>موقوف</option>
                                        </select>
                                        <button class="px-3 py-1 rounded bg-[var(--deep-green)] text-white">تحديث</button>
                                    </form>
                                </td>

                                <td class="px-4 py-3 text-[var(--slate-blue)]">{{ $cs->joined_at?->format('Y-m-d') ?? '-' }}</td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('circles.students.recordings', [$circle, $cs->student]) }}"
                                       class="px-3 py-1 rounded bg-[var(--gold)] text-[var(--dark-bg)] font-bold ml-2">
                                        <i class="fas fa-microphone ml-1"></i> تسجيلاته
                                    </a>
                                    <form method="POST" action="{{ route('circle-students.destroy',$cs) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-red-600 text-white" onclick="return confirm('إزالة الطالب من الحلقة؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-sm text-[var(--slate-blue)]">لا يوجد طلاب في هذه الحلقة بعد.</div>
        @endif
    </div>

</div>
@endsection