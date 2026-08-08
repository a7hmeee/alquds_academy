@extends('layouts.student')

@section('page-title', 'الحلقات')

@section('content')
<div>
    {{-- Success Messages --}}
    @if(session('success'))
        <div style="background: #10B981/20; border: 1px solid #10B981/30; color: #10B981; padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Error Messages --}}
    @if(session('error'))
        <div style="background: #EF4444/20; border: 1px solid #EF4444/30; color: #EF4444; padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @php
        $student = auth()->user()->studentProfile;
        $currentCircle = $student?->circle;
        $allCircles = $student?->circles()->with('circle')->get()->pluck('circle')->filter() ?? collect();
    @endphp

    {{-- Current Circle --}}
    @if($currentCircle)
    <div style="margin-bottom: 30px;">
        <h2 style="color: var(--cream); font-size: 18px; font-weight: bold; margin-bottom: 16px;">
            <i class="fas fa-star" style="color: var(--gold);"></i> حلقتك الحالية
        </h2>

        <div class="card">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 24px; align-items: start;">
                <div>
                    <div style="margin-bottom: 16px;">
                        <h3 style="color: var(--cream); font-size: 20px; font-weight: bold; margin-bottom: 4px;">
                            {{ $currentCircle->name }}
                        </h3>
                        <div style="display: flex; align-items: center; gap: 8px; color: var(--slate-blue);">
                            <i class="fas fa-user-tie" style="color: var(--gold);"></i>
                            <span>{{ $currentCircle->teacher?->user?->name ?? 'بدون معلم' }}</span>
                        </div>
                    </div>

                    {{-- Circle Stats --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 16px;">
                        <div class="stat-box">
                            <div class="stat-value">
                                {{ $currentCircle->students()->count() }}
                            </div>
                            <div class="stat-label">الطلاب</div>
                        </div>

                        @if($student?->current_juz)
                            <div class="stat-box">
                                <div class="stat-value">{{ $student->current_juz }}</div>
                                <div class="stat-label">الجزء الحالي</div>
                            </div>
                        @endif

                        <div class="stat-box">
                            <div class="stat-value">{{ $student?->progress_percent ?? 0 }}%</div>
                            <div class="stat-label">نسبة التقدم</div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-value">{{ $student?->submissions()->count() ?? 0 }}</div>
                            <div class="stat-label">التسجيلات</div>
                        </div>
                    </div>

                    {{-- Circle Description --}}
                    @if($currentCircle->description)
                        <div style="background: var(--dark-bg)/30; border-left: 3px solid var(--gold); padding: 12px; border-radius: 4px;">
                            <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">📝 نبذة عن الحلقة:</div>
                            <div style="color: var(--cream);">{{ $currentCircle->description }}</div>
                        </div>
                    @endif
                </div>

                {{-- Quick Actions --}}
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="{{ route('student.recordings.upload') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-microphone"></i> رفع تسجيل
                    </a>
                    <a href="{{ route('circles.my-progress', $currentCircle) }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-chart-line"></i> عرض التقدم
                    </a>
                    <a href="{{ route('circles.show', $currentCircle) }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-info-circle"></i> تفاصيل
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Other Circles --}}
    @if($allCircles->count() > 0)
    <div>
        <h2 style="color: var(--cream); font-size: 18px; font-weight: bold; margin-bottom: 16px;">
            <i class="fas fa-users" style="color: var(--gold);"></i> حلقاتك الأخرى
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            @foreach($allCircles as $circle)
                @if($circle->id !== $currentCircle?->id)
                    <div class="card">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <h3 style="color: var(--cream); font-weight: bold; font-size: 16px;">
                                {{ $circle->name }}
                            </h3>
                            <span class="badge badge-info">إضافية</span>
                        </div>

                        <div style="margin-bottom: 12px; color: var(--slate-blue); font-size: 14px;">
                            <i class="fas fa-user-tie" style="color: var(--gold);"></i>
                            {{ $circle->teacher?->user?->name ?? 'بدون معلم' }}
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 8px; margin-bottom: 16px;">
                            <div class="stat-box">
                                <div class="stat-value" style="font-size: 20px;">{{ $circle->students()->count() }}</div>
                                <div class="stat-label">طالب</div>
                            </div>

                            @php
                                $studentInCircle = $student->circles()->where('circle_id', $circle->id)->first();
                                $circleStudent = $studentInCircle;
                            @endphp

                            <div class="stat-box">
                                <div class="stat-value" style="font-size: 14px;">
                                    @if($circleStudent?->pivot?->status)
                                        <span class="badge badge-success">{{ $circleStudent->pivot->status }}</span>
                                    @else
                                        <span class="badge badge-warning">غير محدد</span>
                                    @endif
                                </div>
                                <div class="stat-label">الحالة</div>
                            </div>
                        </div>

                        <a href="{{ route('circles.show', $circle) }}" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- No Circles --}}
    @if(!$currentCircle && $allCircles->count() === 0)
        <div class="card" style="text-align: center; padding: 40px 24px;">
            <i class="fas fa-exclamation-circle" style="font-size: 40px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
            <p style="color: var(--cream); font-size: 18px; margin-bottom: 8px;">لم تُسجل في أي حلقة</p>
            <p style="color: var(--slate-blue); margin-bottom: 20px;">يرجى التواصل مع المسؤولين لتسجيلك في حلقة</p>
        </div>
    @endif

    {{-- Available Circles to Join --}}
    @php
        $availableCircles = \App\Models\Circle::all();
        $enrolledCircleIds = auth()->user()->studentProfile?->circles()->pluck('circle_id')->toArray() ?? [];
        $availableCircles = $availableCircles->filter(function($circle) use ($enrolledCircleIds) {
            return !in_array($circle->id, $enrolledCircleIds);
        });
    @endphp

    @if($availableCircles->count() > 0)
    <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--border);">
        <h2 style="color: var(--cream); font-size: 18px; font-weight: bold; margin-bottom: 16px;">
            <i class="fas fa-plus-circle" style="color: var(--gold);"></i> حلقات متاحة للانضمام
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            @foreach($availableCircles as $circle)
                <div class="card" style="opacity: 0.8;">
                    <h3 style="color: var(--cream); font-weight: bold; font-size: 16px; margin-bottom: 8px;">
                        {{ $circle->name }}
                    </h3>

                    <div style="margin-bottom: 12px; color: var(--slate-blue); font-size: 14px;">
                        <i class="fas fa-user-tie" style="color: var(--gold);"></i>
                        {{ $circle->teacher?->user?->name ?? 'بدون معلم' }}
                    </div>

                    <div class="stat-box" style="margin-bottom: 12px;">
                        <div class="stat-value" style="font-size: 20px;">{{ $circle->students()->count() }}</div>
                        <div class="stat-label">طالب مسجل</div>
                    </div>

                    <div style="color: var(--slate-blue); font-size: 13px; line-height: 1.6; margin-bottom: 12px;">
                        {{ Str::limit($circle->description ?? 'لا توجد معلومات', 100) }}
                    </div>

                    <form method="POST" action="{{ route('student.join-circle', $circle) }}" style="width: 100%;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 8px;">
                            <i class="fas fa-plus"></i> انضم الآن
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection
