@extends('layouts.student')

@section('page-title', 'سجل التسجيلات')

@section('content')
<div>
    <div style="display: flex; gap: 12px; margin-bottom: 24px;">
        <a href="{{ route('recordings.upload') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> رفع تسجيل جديد
        </a>
    </div>

    {{-- قسم تقدم الطالب تلقائياً --}}
    @if(isset($progress) && $progress->count())
    <div class="card" style="margin-bottom: 24px;">
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

    @if($submissions->count() > 0)
        {{-- جدول التسجيلات الكامل --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-microphone" style="color: var(--gold);"></i>
                جميع التسجيلات ({{ $submissions->count() }})
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>السورة</th>
                            <th>من آية</th>
                            <th>إلى آية</th>
                            <th>استماع</th>
                            <th>الدرجة</th>
                            <th>ملاحظات المعلم</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                        <tr>
                            <td style="color: var(--cream); font-weight: bold;">{{ $submission->surah_display ?? 'لم تُحدد' }}</td>
                            <td style="color: var(--cream);">{{ $submission->ayah_from ?? $submission->ayah ?? '—' }}</td>
                            <td style="color: var(--cream);">{{ $submission->ayah_to ?? '—' }}</td>
                            <td>
                                @if($submission->file_path)
                                    <audio controls preload="none" style="height: 32px; max-width: 180px;">
                                        <source src="{{ asset('storage/' . $submission->file_path) }}" type="audio/mpeg">
                                    </audio>
                                @else
                                    <span style="color: var(--slate-blue);">—</span>
                                @endif
                            </td>
                            <td>
                                @if(!is_null($submission->score))
                                    <span style="font-weight: bold; font-size: 18px; color: {{ $submission->score >= 90 ? '#4ade80' : ($submission->score >= 70 ? '#60a5fa' : ($submission->score >= 50 ? '#fbbf24' : '#f87171')) }};">
                                        {{ $submission->score }}
                                    </span>
                                    <span style="color: var(--slate-blue); font-size: 12px;">/ 100</span>
                                @else
                                    <span style="color: var(--slate-blue);">—</span>
                                @endif
                            </td>
                            <td style="max-width: 200px;">
                                @if($submission->review_notes)
                                    <div style="background: rgba(255,215,0,0.05); border-right: 3px solid var(--gold); padding: 8px; border-radius: 4px;">
                                        <div style="color: var(--cream); font-size: 13px;">{{ $submission->review_notes }}</div>
                                    </div>
                                @else
                                    <span style="color: var(--slate-blue);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($submission->status === 'pending')
                                    <span class="badge badge-warning">قيد المراجعة ⏳</span>
                                @elseif($submission->status === 'accepted')
                                    <span class="badge badge-success">مقبول ✓</span>
                                @elseif($submission->status === 'reviewed')
                                    <span class="badge badge-success">تم التصحيح ✓</span>
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
        </div>

        <div style="text-align: center; margin-top: 24px; color: var(--slate-blue); font-size: 12px;">
            إجمالي التسجيلات: {{ $submissions->count() }}
        </div>
    @else
        <div class="card" style="text-align: center; padding: 40px 24px;">
            <i class="fas fa-inbox" style="font-size: 40px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
            <p style="color: var(--cream); font-size: 18px; margin-bottom: 8px;">لم تُرفع أي تسجيلات بعد</p>
            <p style="color: var(--slate-blue); margin-bottom: 20px;">ابدأ برفع تسجيلاتك الأولى لتساعدك المعلم على متابعة تقدمك</p>
            <a href="{{ route('recordings.upload') }}" class="btn btn-primary">
                <i class="fas fa-microphone"></i> رفع تسجيل الآن
            </a>
        </div>
    @endif
</div>

@endsection
