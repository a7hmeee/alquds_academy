@extends('layouts.student')

@section('page-title', 'لوحة التسجيلات الصوتية')

@section('content')
<div class="container mx-auto">
    {{-- Header --}}
    <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: var(--cream); font-size: 28px; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-microphone" style="color: var(--gold);"></i> تسجيلاتي الصوتية
            </h1>
            <p style="color: var(--slate-blue); font-size: 14px;">
                إدارة كاملة لتسجيلاتك الصوتية والملفات
            </p>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="{{ route('recordings.upload') }}" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="fas fa-plus"></i> تسجيل جديد
            </a>
            <a href="{{ route('recordings.bulkImport.page') }}" class="btn btn-secondary" style="padding: 12px 24px;">
                <i class="fas fa-cloud-upload-alt"></i> استيراد جماعي
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 30px;">
        {{-- Total --}}
        <div class="card" style="background: linear-gradient(135deg, var(--gold)/10, var(--gold)/5); border-left: 4px solid var(--gold);">
            <div style="text-align: center;">
                <i class="fas fa-music" style="font-size: 24px; color: var(--gold); margin-bottom: 12px;"></i>
                <div style="color: var(--cream); font-size: 24px; font-weight: 700;">{{ $stats['total'] }}</div>
                <div style="color: var(--slate-blue); font-size: 12px;">إجمالي التسجيلات</div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="card" style="background: linear-gradient(135deg, #F59E0B/10, #F59E0B/5); border-left: 4px solid #F59E0B;">
            <div style="text-align: center;">
                <i class="fas fa-clock" style="font-size: 24px; color: #F59E0B; margin-bottom: 12px;"></i>
                <div style="color: var(--cream); font-size: 24px; font-weight: 700;">{{ $stats['pending'] }}</div>
                <div style="color: var(--slate-blue); font-size: 12px;">قيد المراجعة</div>
            </div>
        </div>

        {{-- Accepted --}}
        <div class="card" style="background: linear-gradient(135deg, #10B981/10, #10B981/5); border-left: 4px solid #10B981;">
            <div style="text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 24px; color: #10B981; margin-bottom: 12px;"></i>
                <div style="color: var(--cream); font-size: 24px; font-weight: 700;">{{ $stats['accepted'] }}</div>
                <div style="color: var(--slate-blue); font-size: 12px;">مقبول</div>
            </div>
        </div>

        {{-- Needs Work --}}
        <div class="card" style="background: linear-gradient(135deg, #EF4444/10, #EF4444/5); border-left: 4px solid #EF4444;">
            <div style="text-align: center;">
                <i class="fas fa-exclamation-circle" style="font-size: 24px; color: #EF4444; margin-bottom: 12px;"></i>
                <div style="color: var(--cream); font-size: 24px; font-weight: 700;">{{ $stats['needs_work'] }}</div>
                <div style="color: var(--slate-blue); font-size: 12px;">يحتاج تحسين</div>
            </div>
        </div>

        {{-- Average Rating --}}
        <div class="card" style="background: linear-gradient(135deg, var(--gold)/10, var(--gold)/5); border-left: 4px solid var(--gold);">
            <div style="text-align: center;">
                <i class="fas fa-star" style="font-size: 24px; color: var(--gold); margin-bottom: 12px;"></i>
                <div style="color: var(--cream); font-size: 24px; font-weight: 700;">
                    {{ $stats['avg_rating'] ? number_format($stats['avg_rating'], 1) : '-' }}
                </div>
                <div style="color: var(--slate-blue); font-size: 12px;">متوسط التقييم</div>
            </div>
        </div>
    </div>

    {{-- Recordings List --}}
    @if($submissions->count() > 0)
        <div style="display: grid; gap: 16px;">
            @foreach($submissions as $submission)
                <div class="card" style="margin-bottom: 0;">
                    <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between;">
                        {{-- Main Content --}}
                        <div style="flex: 1; min-width: 0;">
                            {{-- Title --}}
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                                <i class="fas fa-book-quran" style="color: var(--gold); font-size: 18px;"></i>
                                <div>
                                    <div style="color: var(--cream); font-weight: 600; font-size: 16px;">
                                        {{ $submission->surah }}
                                        @if($submission->ayah_from)
                                            - آية {{ $submission->ayah_from }}
                                            @if($submission->ayah_to)
                                                إلى {{ $submission->ayah_to }}
                                            @endif
                                        @endif
                                    </div>
                                    <div style="color: var(--slate-blue); font-size: 12px;">
                                        الجزء {{ $submission->juz }} | {{ $submission->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Status & Rating --}}
                            <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 12px;">
                                {{-- Status Badge --}}
                                <div>
                                    <div style="color: var(--slate-blue); font-size: 11px; margin-bottom: 4px;">الحالة</div>
                                    @switch($submission->status)
                                        @case('pending')
                                            <span class="badge" style="background: #F59E0B/20; color: #F59E0B; padding: 4px 10px; border-radius: 12px; font-size: 12px;">⏳ قيد المراجعة</span>
                                            @break
                                        @case('accepted')
                                            <span class="badge" style="background: #10B981/20; color: #10B981; padding: 4px 10px; border-radius: 12px; font-size: 12px;">✓ مقبول</span>
                                            @break
                                        @case('needs_work')
                                            <span class="badge" style="background: #EF4444/20; color: #EF4444; padding: 4px 10px; border-radius: 12px; font-size: 12px;">⚠ يحتاج تحسين</span>
                                            @break
                                        @default
                                            <span class="badge" style="background: var(--slate-blue)/20; color: var(--slate-blue); padding: 4px 10px; border-radius: 12px; font-size: 12px;">{{ $submission->status }}</span>
                                    @endswitch
                                </div>

                                {{-- Teacher Rating --}}
                                @if($submission->rating)
                                    <div>
                                        <div style="color: var(--slate-blue); font-size: 11px; margin-bottom: 4px;">تقييم المعلم</div>
                                        <div style="display: flex; gap: 2px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $submission->rating)
                                                    <i class="fas fa-star" style="color: var(--gold); font-size: 12px;"></i>
                                                @else
                                                    <i class="far fa-star" style="color: var(--slate-blue); font-size: 12px;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @endif

                                {{-- Self Rating --}}
                                @if($submission->self_rating)
                                    <div>
                                        <div style="color: var(--slate-blue); font-size: 11px; margin-bottom: 4px;">تقييمك</div>
                                        <div style="display: flex; gap: 2px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $submission->self_rating)
                                                    <i class="fas fa-star" style="color: #10B981; font-size: 12px;"></i>
                                                @else
                                                    <i class="far fa-star" style="color: var(--slate-blue); font-size: 12px;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Notes --}}
                            @if($submission->review_notes)
                                <div style="background: var(--gold)/10; border-right: 3px solid var(--gold); padding: 12px; border-radius: 4px; margin-bottom: 12px;">
                                    <div style="color: var(--gold); font-weight: 600; margin-bottom: 4px; font-size: 11px;">📝 ملاحظات المعلم</div>
                                    <div style="color: var(--cream); font-size: 13px; overflow-wrap: break-word;">{{ $submission->review_notes }}</div>
                                </div>
                            @endif

                            @if($submission->self_notes)
                                <div style="background: #10B981/10; border-right: 3px solid #10B981; padding: 12px; border-radius: 4px;">
                                    <div style="color: #10B981; font-weight: 600; margin-bottom: 4px; font-size: 11px;">💬 ملاحظاتك</div>
                                    <div style="color: var(--cream); font-size: 13px; overflow-wrap: break-word;">{{ $submission->self_notes }}</div>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div style="display: flex; flex-direction: column; gap: 8px; align-items: center;">
                            @if($submission->file_path)
                                <a href="{{ route('submissions.download', $submission) }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                                    <i class="fas fa-download"></i> تحميل
                                </a>
                            @endif

                            <a href="{{ route('recordings.show', $submission) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">
                                <i class="fas fa-eye"></i> عرض
                            </a>

                            @if($submission->status === 'pending')
                                <form action="{{ route('recordings.delete', $submission) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: 8px 16px; font-size: 12px; background: #EF4444/20; color: #EF4444; border: none; border-radius: 6px; cursor: pointer;">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card" style="text-align: center; padding: 60px 24px;">
            <i class="fas fa-inbox" style="font-size: 48px; color: var(--slate-blue); margin-bottom: 20px; display: block;"></i>
            <p style="color: var(--cream); font-size: 18px; margin-bottom: 12px;">لم تُرفع أي تسجيلات بعد</p>
            <p style="color: var(--slate-blue); margin-bottom: 24px;">ابدأ برفع تسجيلاتك الأولى لتساعدك المعلم على متابعة تقدمك</p>
            <a href="{{ route('recordings.upload') }}" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="fas fa-microphone"></i> رفع التسجيل الأول
            </a>
        </div>
    @endif
</div>

<style>
    .container {
        max-width: 1000px;
    }

    .badge {
        display: inline-block;
    }
</style>
@endsection
