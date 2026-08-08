@extends('layouts.student')
@section('page-title', 'مهام الحفظ')

@section('content')
<div class="card">
    <div class="card-title">
        <i class="fas fa-tasks" style="color: var(--gold);"></i>
        مهام الحفظ
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

    @forelse($assignments as $assignment)
        <div style="background: rgba(11,31,20,0.3); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <span style="font-weight: bold; color: var(--cream); font-size: 16px;">
                            {{ $assignment->surah?->name_ar ?? 'سورة' }}
                            @if($assignment->ayah_from)
                                ({{ $assignment->ayah_from }}→{{ $assignment->ayah_to }})
                            @endif
                        </span>
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
                        <span style="display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; color: {{ $s['color'] }}; background: {{ $s['bg'] }};">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    <div style="color: var(--slate-blue); font-size: 12px; display: flex; gap: 16px; flex-wrap: wrap;">
                        <span><i class="fas fa-graduation-cap"></i> {{ $assignment->circle?->name ?? '—' }}</span>
                        <span><i class="fas fa-chalkboard-teacher"></i> {{ $assignment->teacher?->user?->name ?? '—' }}</span>
                        @if($assignment->due_date)
                            <span><i class="fas fa-calendar-alt"></i> تسليم: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y') }}</span>
                        @endif
                    </div>
                    @if($assignment->instructions)
                        <div style="margin-top: 8px; color: var(--text-secondary); font-size: 13px; background: rgba(11,31,20,0.3); padding: 8px 12px; border-radius: 6px; border-right: 3px solid var(--gold);">
                            {{ $assignment->instructions }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div style="margin-top: 12px; display: flex; flex-wrap: wrap; gap: 8px;">
                <a href="{{ route('student.assignments.show', $assignment) }}"
                   class="btn btn-primary" style="font-size: 12px; padding: 6px 12px;">
                    <i class="fas fa-eye"></i> عرض
                </a>
                @if($assignment->status === 'assigned')
                    <form method="POST" action="{{ route('student.assignments.status', $assignment) }}" style="display: inline;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn" style="font-size: 12px; padding: 6px 12px; background: rgba(96,165,250,0.2); color: #60a5fa;">
                            <i class="fas fa-play"></i> بدء العمل
                        </button>
                    </form>
                @endif
                @if(in_array($assignment->status, ['in_progress', 'needs_revision']))
                    <form method="POST" action="{{ route('student.assignments.status', $assignment) }}" style="display: inline;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="submitted">
                        <button type="submit" class="btn" style="font-size: 12px; padding: 6px 12px; background: rgba(251,191,36,0.2); color: #fbbf24;">
                            <i class="fas fa-paper-plane"></i> تسليم
                        </button>
                    </form>
                @endif
            </div>

            {{-- Progress bar --}}
            @if($assignment->completion_percent)
                <div style="margin-top: 12px;">
                    <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--slate-blue); margin-bottom: 4px;">
                        <span>التقدم</span>
                        <span>{{ $assignment->completion_percent }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $assignment->completion_percent }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div style="text-align: center; padding: 40px 20px;">
            <i class="fas fa-inbox" style="font-size: 40px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
            <p style="color: var(--slate-blue);">لا توجد مهام حالياً</p>
        </div>
    @endforelse

    @if($assignments->hasPages())
        <div style="margin-top: 16px;">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
@endsection
