@extends('layouts.student')

@section('page-title', 'تقدمك في الحفظ')

@section('content')
<div>
    @php
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;
        $progressRecords = $student?->progressRecords()->orderBy('created_at', 'desc')->get() ?? collect();
    @endphp

    @if(!$circle)
        <div class="card" style="text-align: center; padding: 40px 24px;">
            <i class="fas fa-exclamation-circle" style="font-size: 40px; color: var(--slate-blue); margin-bottom: 16px; display: block;"></i>
            <p style="color: var(--cream); font-size: 18px; margin-bottom: 16px;">لم تُسجل في أي حلقة</p>
        </div>
        @endsection
        @stop
    @endif

    {{-- Progress Summary --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
        {{-- Overall Progress --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-tachometer-alt" style="color: var(--gold);"></i>
                التقدم الإجمالي
            </div>

            <div style="text-align: center; padding: 20px 0;">
                <div style="position: relative; width: 100px; height: 100px; margin: 0 auto 16px;">
                    <svg style="transform: rotate(-90deg);" width="100" height="100" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="var(--dark-bg)" stroke-width="8"/>
                        <circle 
                            cx="60" cy="60" r="54" 
                            fill="none" 
                            stroke="var(--gold)" 
                            stroke-width="8"
                            stroke-dasharray="{{ 340 * ($student->progress_percent ?? 0) / 100 }} 340"
                            stroke-linecap="round"
                        />
                    </svg>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                        <div style="color: var(--gold); font-size: 28px; font-weight: bold;">
                            {{ $student->progress_percent ?? 0 }}%
                        </div>
                    </div>
                </div>
                <div style="color: var(--slate-blue); font-size: 12px;">من الحفظ</div>
            </div>
        </div>

        {{-- Current Position --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-map-marker-alt" style="color: var(--gold);"></i>
                الموضع الحالي
            </div>

            <div style="padding: 12px 0;">
                <div style="margin-bottom: 16px;">
                    <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">الجزء</div>
                    <div style="color: var(--cream); font-size: 20px; font-weight: bold;">
                        {{ $student->current_juz ?? 'لم يُحدد' }}
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">السورة</div>
                    <div style="color: var(--cream); font-size: 18px; font-weight: bold;">
                        {{ $student->current_surah ?? 'لم تُحدد' }}
                    </div>
                </div>

                @if($student->current_ayah)
                    <div>
                        <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">الآية</div>
                        <div style="color: var(--cream); font-weight: 500;">
                            رقم {{ $student->current_ayah }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Memorization Level --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-graduation-cap" style="color: var(--gold);"></i>
                مستوى الحفظ
            </div>

            <div style="padding: 12px 0;">
                <div style="color: var(--cream); font-size: 16px; font-weight: bold; margin-bottom: 8px;">
                    {{ $student->memorization_level ?? 'لم يُحدد' }}
                </div>

                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 12px;">
                    تقييم من المعلم
                </div>

                <div style="text-align: center; color: var(--gold); font-size: 14px;">
                    @if($student->memorization_level)
                        ✓ تم تحديثه مؤخراً
                    @else
                        <span style="color: var(--slate-blue);">قيد المراجعة</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tajweed Level --}}
        <div class="card">
            <div class="card-title">
                <i class="fas fa-quran" style="color: var(--gold);"></i>
                مستوى التجويد
            </div>

            <div style="padding: 12px 0;">
                <div style="color: var(--cream); font-size: 16px; font-weight: bold; margin-bottom: 8px;">
                    {{ $student->tajweed_level ?? 'لم يُحدد' }}
                </div>

                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 12px;">
                    تقييم من المعلم
                </div>

                <div style="text-align: center; color: var(--gold); font-size: 14px;">
                    @if($student->tajweed_level)
                        ✓ تم تحديثه مؤخراً
                    @else
                        <span style="color: var(--slate-blue);">قيد المراجعة</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Progress History --}}
    <div class="card">
        <div class="card-title">
            <i class="fas fa-history" style="color: var(--gold);"></i>
            سجل التقدم
        </div>

        @if($progressRecords->count() > 0)
            <div style="display: grid; gap: 12px;">
                @foreach($progressRecords as $record)
                    <div style="background: var(--dark-bg)/30; border-left: 3px solid var(--gold); padding: 16px; border-radius: 6px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <div>
                                <div style="color: var(--cream); font-weight: 500; margin-bottom: 4px;">
                                    📖 جزء {{ $record->juz }} • {{ $record->surah }}
                                    @if($record->ayah)
                                        (آية {{ $record->ayah }})
                                    @endif
                                </div>
                                <div style="color: var(--slate-blue); font-size: 12px;">
                                    {{ $record->created_at->format('d/m/Y - H:i') }}
                                </div>
                            </div>

                            @if($record->created_by)
                                <div style="text-align: left; color: var(--slate-blue); font-size: 12px;">
                                    <span title="المعلم المسؤول">👨‍🏫 {{ \App\Models\User::find($record->created_by)?->name ?? 'معلم' }}</span>
                                </div>
                            @endif
                        </div>

                        @if($record->notes)
                            <div style="background: var(--dark-bg)/50; padding: 8px; border-radius: 4px; margin-top: 8px;">
                                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">📝 ملاحظات:</div>
                                <div style="color: var(--cream); font-size: 13px;">{{ $record->notes }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Summary Stats --}}
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                    <div class="stat-box">
                        <div class="stat-value">{{ $progressRecords->count() }}</div>
                        <div class="stat-label">إجمالي المسجلات</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value">
                            {{ 
                                $progressRecords
                                    ->map(function($r) { 
                                        return $r->juz;
                                    })->unique()->count()
                            }}
                        </div>
                        <div class="stat-label">أجزاء مُحفوظة</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value">
                            {{ 
                                $progressRecords
                                    ->map(function($r) { 
                                        return $r->surah;
                                    })->unique()->count()
                            }}
                        </div>
                        <div class="stat-label">سور مُحفوظة</div>
                    </div>

                    @php
                        $latestDate = $progressRecords->first()?->created_at;
                        $daysAgo = $latestDate ? now()->diffInDays($latestDate) : null;
                    @endphp

                    @if($daysAgo !== null)
                        <div class="stat-box">
                            <div class="stat-value" style="font-size: 20px;">
                                {{ $daysAgo === 0 ? 'اليوم' : $daysAgo . ' يوم' }}
                            </div>
                            <div class="stat-label">آخر تسجيل</div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 40px 24px; color: var(--slate-blue);">
                <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                <p>لا توجد سجلات تقدم بعد</p>
                <p style="font-size: 12px; margin-top: 8px;">سيتابع المعلم تقدمك بعد رفعك للتسجيلات</p>
            </div>
        @endif
    </div>

    {{-- Tips Section --}}
    <div style="margin-top: 24px;">
        <h3 style="color: var(--cream); font-size: 16px; font-weight: bold; margin-bottom: 12px;">
            <i class="fas fa-lightbulb" style="color: var(--gold);"></i> نصائح للنجاح
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
            <div style="background: var(--deep-green)/10; border-left: 3px solid var(--deep-green); padding: 12px; border-radius: 4px;">
                <div style="color: var(--deep-green); font-weight: 600; margin-bottom: 4px;">📚 التسميع المنتظم</div>
                <div style="color: var(--cream); font-size: 13px;">
                    حاول تسميع ما تحفظه بانتظام مرة واحدة على الأقل كل أسبوع
                </div>
            </div>

            <div style="background: var(--gold)/10; border-left: 3px solid var(--gold); padding: 12px; border-radius: 4px;">
                <div style="color: var(--gold); font-weight: 600; margin-bottom: 4px;">🎤 جودة التسجيل</div>
                <div style="color: var(--cream); font-size: 13px;">
                    تأكد من وضوح الصوت والخلفية الهادئة عند التسجيل
                </div>
            </div>

            <div style="background: var(--slate-blue)/10; border-left: 3px solid var(--slate-blue); padding: 12px; border-radius: 4px;">
                <div style="color: var(--slate-blue); font-weight: 600; margin-bottom: 4px;">⏰ المراقبة المستمرة</div>
                <div style="color: var(--cream); font-size: 13px;">
                    تابع ملاحظات المعلم وحسّن من نقاط الضعف التي يشير إليها
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
