@extends('layouts.student')

@section('page-title', 'الرئيسية')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    {{-- Student Info Card --}}
    <div class="card">
        <div class="card-title">
            <i class="fas fa-user" style="color: var(--gold);"></i>
            معلومات الطالب
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div>
                <div style="color: var(--slate-blue); font-size: 12px;">الاسم الكامل</div>
                <div style="color: var(--cream); font-weight: 500;">{{ auth()->user()->name }}</div>
            </div>
            <div>
                <div style="color: var(--slate-blue); font-size: 12px;">البريد الإلكتروني</div>
                <div style="color: var(--cream); font-weight: 500; direction: ltr; text-align: left; overflow-wrap: anywhere;">{{ auth()->user()->email }}</div>
            </div>
            <div>
                <div style="color: var(--slate-blue); font-size: 12px;">حالة الحساب</div>
                <span class="badge badge-success">نشط ✓</span>
            </div>
        </div>
    </div>

    {{-- Circle Info Card --}}
    @php
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;
    @endphp

    @if($circle)
    <div class="card">
        <div class="card-title">
            <i class="fas fa-graduation-cap" style="color: var(--gold);"></i>
            الحلقة
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div>
                <div style="color: var(--slate-blue); font-size: 12px;">اسم الحلقة</div>
                <div style="color: var(--cream); font-weight: 500;">{{ $circle->name }}</div>
            </div>
            <div>
                <div style="color: var(--slate-blue); font-size: 12px;">المعلم</div>
                <div style="color: var(--cream); font-weight: 500;">{{ $circle->teacher?->user?->name ?? 'لم يحدد بعد' }}</div>
            </div>
            <a href="{{ route('student.circles') }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                عرض الحلقات
            </a>
        </div>
    </div>

    {{-- Progress Summary Card --}}
   
    {{-- Latest Rating Card --}}
    <div class="card">
        <div class="card-title">
            <i class="fas fa-star" style="color: var(--gold);"></i>
            آخر تقييم
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @if($student?->latestProgress)
                <div>
                    <div style="color: var(--slate-blue); font-size: 12px;">التقييم</div>
                    <div style="display: flex; gap: 4px; margin-top: 4px;">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($student->latestProgress->rating ?? 0))
                                <i class="fas fa-star" style="color: var(--gold);"></i>
                            @else
                                <i class="far fa-star" style="color: var(--slate-blue);"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                <div>
                    <div style="color: var(--slate-blue); font-size: 12px;">التاريخ</div>
                    <div style="color: var(--cream); font-weight: 500;">{{ $student->latestProgress->created_at->format('d/m/Y') }}</div>
                </div>
            @else
                <div style="color: var(--slate-blue); text-align: center; padding: 16px 0;">
                    لم تُسجَّل تقييمات بعد
                </div>
            @endif
        </div>
    </div>

    {{-- Submissions Count Card --}}
    <div class="card">
        <div class="card-title">
            <i class="fas fa-microphone" style="color: var(--gold);"></i>
            التسجيلات
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div>
                <div style="font-size: 28px; font-weight: bold; color: var(--gold); margin-bottom: 4px;">{{ $submissions->count() }}</div>
                <div style="color: var(--slate-blue); font-size: 12px;">تسجيل مرفوع</div>
            </div>
            <a href="{{ route('recordings.dashboard') }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                عرض التسجيلات
            </a>
        </div>
    </div>

    {{-- Quick Actions Card --}}
   
</div>

{{-- Recent Submissions --}}
@if($submissions->count() > 0)

{{-- قسم تقدم الحفظ التلقائي --}}
@if($progress->count())
<div class="card" style="margin-bottom: 20px;">
    <div class="card-title">
        <i class="fas fa-chart-line" style="color: var(--gold);"></i>
        تقدم الحفظ (التسجيلات المعتمدة — درجة 70 فأعلى)
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
        @foreach($progress as $item)
        <div style="background: rgba(255,215,0,0.05); border: 1px solid rgba(255,215,0,0.15); border-radius: 8px; padding: 16px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <i class="fas fa-book-quran" style="color: var(--gold);"></i>
                <span style="color: var(--cream); font-weight: bold; font-size: 16px;">{{ $item['surah'] }}</span>
            </div>
            <div style="color: var(--cream); font-size: 14px; margin-bottom: 4px;">
                آية {{ $item['min_ayah'] ?? '—' }} → آية {{ $item['max_ayah'] ?? '—' }}
            </div>
            <div style="display: flex; justify-content: space-between; color: var(--slate-blue); font-size: 12px;">
                <span>{{ $item['count'] }} تسجيل معتمد</span>
                <span>متوسط: {{ $item['avg_score'] }}/100</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card">
    <div class="card-title">
        <i class="fas fa-history" style="color: var(--gold);"></i>
        آخر التسجيلات
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>السورة</th>
                    <th>من آية</th>
                    <th>إلى آية</th>
                    <th>الدرجة</th>
                    <th>ملاحظات المعلم</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions->take(5) as $submission)
                    <tr>
                        <td style="color: var(--cream); font-weight: bold;">{{ $submission->surah_display ?? 'لم تُحدد' }}</td>
                        <td style="color: var(--cream);">{{ $submission->ayah_from ?? $submission->ayah ?? '—' }}</td>
                        <td style="color: var(--cream);">{{ $submission->ayah_to ?? '—' }}</td>
                        <td>
                            @if(!is_null($submission->score))
                                <span style="font-weight: bold; font-size: 16px; color: {{ $submission->score >= 90 ? '#4ade80' : ($submission->score >= 70 ? '#60a5fa' : ($submission->score >= 50 ? '#fbbf24' : '#f87171')) }};">
                                    {{ $submission->score }}
                                </span>
                                <span style="color: var(--slate-blue); font-size: 11px;">/ 100</span>
                            @else
                                <span style="color: var(--slate-blue);">—</span>
                            @endif
                        </td>
                        <td style="max-width: 180px; color: var(--slate-blue); font-size: 12px;">
                            {{ Str::limit($submission->review_notes, 40) ?? '—' }}
                        </td>
                        <td>
                            @if($submission->status === 'pending')
                                <span class="badge badge-warning">قيد المراجعة</span>
                            @elseif($submission->status === 'reviewed')
                                <span class="badge badge-success">تم التصحيح ✓</span>
                            @elseif($submission->status === 'accepted')
                                <span class="badge badge-success">مقبول ✓</span>
                            @elseif($submission->status === 'needs_work')
                                <span class="badge badge-warning" style="background: rgba(239,68,68,0.2); color: #fca5a5;">يحتاج تحسين</span>
                            @else
                                <span class="badge badge-info">{{ $submission->status }}</span>
                            @endif
                        </td>
                        <td style="color: var(--slate-blue); white-space: nowrap;">{{ $submission->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('student.recordings.list') }}" style="display: inline-block; margin-top: 16px;" class="btn btn-secondary">
        عرض جميع التسجيلات
    </a>
</div>
@else
<div class="card" style="text-align: center; padding: 40px 24px;">
    <i class="fas fa-inbox" style="font-size: 40px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
    <p style="color: var(--slate-blue); margin-bottom: 16px;">لم تُرفع أي تسجيلات بعد</p>
    <a href="{{ route('recordings.upload') }}" class="btn btn-primary">
        <i class="fas fa-microphone"></i> ابدأ برفع تسجيل
    </a>
</div>
@endif

@else
<div class="card" style="text-align: center; padding: 40px 24px;">
    <i class="fas fa-exclamation-circle" style="font-size: 40px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
    <p style="color: var(--cream); font-size: 18px; margin-bottom: 8px;">لم تُسجل في أي حلقة</p>
    <p style="color: var(--slate-blue); margin-bottom: 20px;">يرجى التواصل مع المسؤولين لتسجيلك في حلقة</p>
    <a href="{{ route('student.circles') }}" class="btn btn-primary">
        <i class="fas fa-users"></i> عرض الحلقات المتاحة
    </a>
</div>
@endif

@endsection
