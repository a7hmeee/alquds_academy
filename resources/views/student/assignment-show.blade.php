@extends('layouts.student')
@section('page-title', 'تفاصيل المهمة')

@section('content')
<div class="card">
    <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: start; margin-bottom: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-tasks" style="color: var(--gold);"></i>
                <span class="card-title" style="margin: 0;">{{ $assignment->surah?->name_ar ?? 'مهمة حفظ' }}</span>
            </div>
            @if($assignment->ayah_from)
                <div style="color: var(--slate-blue); margin-top: 4px;">الآيات: {{ $assignment->ayah_from }} → {{ $assignment->ayah_to }}</div>
            @endif
        </div>
        @php
            $statusData = [
                'assigned' => ['color' => 'var(--gold)', 'bg' => 'rgba(201,168,76,0.15)', 'label' => 'مكلف به'],
                'in_progress' => ['color' => '#60a5fa', 'bg' => 'rgba(96,165,250,0.15)', 'label' => 'جاري العمل'],
                'submitted' => ['color' => '#fbbf24', 'bg' => 'rgba(251,191,36,0.15)', 'label' => 'بانتظار المراجعة'],
                'reviewed' => ['color' => '#4ade80', 'bg' => 'rgba(74,222,128,0.15)', 'label' => 'تمت المراجعة'],
                'completed' => ['color' => '#4ade80', 'bg' => 'rgba(74,222,128,0.15)', 'label' => 'مكتمل ✓'],
                'needs_revision' => ['color' => '#f87171', 'bg' => 'rgba(248,113,113,0.15)', 'label' => 'يحتاج مراجعة'],
                'cancelled' => ['color' => 'var(--slate-blue)', 'bg' => 'rgba(138,154,142,0.15)', 'label' => 'ملغي'],
            ];
            $s = $statusData[$assignment->status] ?? $statusData['assigned'];
        @endphp
        <span style="display: inline-block; padding: 4px 14px; border-radius: 12px; font-size: 12px; font-weight: 600; color: {{ $s['color'] }}; background: {{ $s['bg'] }};">
            {{ $s['label'] }}
        </span>
    </div>

    @if(session('success'))
        <div style="padding: 12px; border-radius: 8px; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; margin-bottom: 16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 12px; border-radius: 8px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; margin-bottom: 16px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Details --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 20px;">
        <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px;">
            <div style="color: var(--slate-blue); font-size: 11px;">المعلم</div>
            <div style="color: var(--cream); font-weight: 500;">{{ $assignment->teacher?->user?->name ?? '—' }}</div>
        </div>
        <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px;">
            <div style="color: var(--slate-blue); font-size: 11px;">الحلقة</div>
            <div style="color: var(--cream); font-weight: 500;">{{ $assignment->circle?->name ?? '—' }}</div>
        </div>
        <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px;">
            <div style="color: var(--slate-blue); font-size: 11px;">تاريخ التسليم</div>
            <div style="color: var(--cream); font-weight: 500;">{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y') : 'غير محدد' }}</div>
        </div>
        @if($assignment->priority)
        <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px;">
            <div style="color: var(--slate-blue); font-size: 11px;">الأولوية</div>
            <div style="color: var(--cream); font-weight: 500;">{{ $assignment->priority === 'high' ? 'عالية' : ($assignment->priority === 'medium' ? 'متوسطة' : 'عادية') }}</div>
        </div>
        @endif
    </div>

    {{-- Instructions --}}
    @if($assignment->instructions)
        <div style="margin-bottom: 20px;">
            <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 6px;">تعليمات المعلم</div>
            <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-right: 3px solid var(--gold); border-radius: 6px; padding: 12px; color: var(--cream);">
                {{ $assignment->instructions }}
            </div>
        </div>
    @endif

    {{-- Linked Submissions --}}
    @if($assignment->submissions && $assignment->submissions->count() > 0)
        <div style="margin-bottom: 20px;">
            <div style="color: var(--gold); font-weight: 600; margin-bottom: 8px;">
                <i class="fas fa-file-audio"></i> التسجيلات المرتبطة
            </div>
            @foreach($assignment->submissions as $sub)
                <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-radius: 6px; padding: 10px; margin-bottom: 6px; display: flex; justify-content: space-between;">
                    <span style="color: var(--cream);">{{ $sub->surah_display ?? 'تسجيل' }}</span>
                    <span style="color: var(--slate-blue);">{{ $sub->created_at->format('d/m/Y') }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Actions --}}
    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-color);">
        @if($assignment->status === 'assigned')
            <form method="POST" action="{{ route('student.assignments.status', $assignment) }}" style="display: inline;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-play"></i> بدء العمل
                </button>
            </form>
        @endif
        @if(in_array($assignment->status, ['in_progress', 'needs_revision']))
            <form method="POST" action="{{ route('student.assignments.status', $assignment) }}" style="display: inline;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="submitted">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> تسليم المهمة
                </button>
            </form>
        @endif
        <a href="{{ route('student.assignments') }}" class="btn" style="border: 1px solid var(--border-color);">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
</div>
@endsection
