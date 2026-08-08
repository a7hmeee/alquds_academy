@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم التنفيذية')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg text-sm" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg text-sm" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
            {{-- الطلاب --}}
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; display: flex; align-items: center; justify-content: space-between; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <div>
                    <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">👨‍🎓 الطلاب</div>
                    <div style="color: var(--text-primary); font-size: 28px; font-weight: 700;">{{ number_format($totalStudents ?? 0) }}</div>
                    <div style="color: var(--gold); font-size: 12px; margin-top: 4px;">{{ number_format($activeStudents ?? 0) }} نشط</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(201,168,76,0.1); display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 22px;">🎓</span>
                </div>
            </div>

            {{-- المعلمون --}}
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; display: flex; align-items: center; justify-content: space-between; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <div>
                    <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">👨‍🏫 المعلمون</div>
                    <div style="color: var(--text-primary); font-size: 28px; font-weight: 700;">{{ number_format($totalTeachers ?? 0) }}</div>
                    <div style="color: var(--gold); font-size: 12px; margin-top: 4px;">{{ number_format($activeTeachers ?? 0) }} نشط</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(201,168,76,0.1); display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 22px;">📚</span>
                </div>
            </div>

            {{-- الحلقات --}}
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; display: flex; align-items: center; justify-content: space-between; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <div>
                    <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">🕌 الحلقات</div>
                    <div style="color: var(--text-primary); font-size: 28px; font-weight: 700;">{{ number_format($totalCircles ?? 0) }}</div>
                    <div style="color: var(--gold); font-size: 12px; margin-top: 4px;">{{ number_format($activeCircles ?? 0) }} نشطة</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(201,168,76,0.1); display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 22px;">🕌</span>
                </div>
            </div>

            {{-- التسجيلات --}}
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; display: flex; align-items: center; justify-content: space-between; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <div>
                    <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">🎤 التسجيلات</div>
                    <div style="color: var(--text-primary); font-size: 28px; font-weight: 700;">{{ number_format($totalSubmissions ?? 0) }}</div>
                    <div style="color: #F59E0B; font-size: 12px; margin-top: 4px;">{{ number_format($pendingSubmissions ?? 0) }} قيد المراجعة</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(201,168,76,0.1); display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 22px;">🎵</span>
                </div>
            </div>
        </div>

        {{-- Second Row --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">🏢 المؤسسات</div>
                <div style="color: var(--text-primary); font-size: 28px; font-weight: 700;">{{ number_format($totalOrganizations ?? 0) }}</div>
            </div>
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">👥 المستخدمون</div>
                <div style="color: var(--text-primary); font-size: 28px; font-weight: 700;">{{ number_format($totalUsers ?? 0) }}</div>
            </div>
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">⭐ متوسط الدرجات</div>
                <div style="color: var(--gold); font-size: 28px; font-weight: 700;">{{ number_format($avgScore ?? 0, 1) }}</div>
            </div>
        </div>

        {{-- Third Row: New Feature Stats --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">📋 مهام الحفظ</div>
                <div style="color: var(--gold); font-size: 28px; font-weight: 700;">{{ number_format($totalAssignments ?? 0) }}</div>
                <div style="color: #60a5fa; font-size: 11px; margin-top: 4px;">{{ number_format($pendingAssignments ?? 0) }} قيد التنفيذ</div>
            </div>
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">🎤 جلسات التسميع</div>
                <div style="color: var(--gold); font-size: 28px; font-weight: 700;">{{ number_format($totalSessions ?? 0) }}</div>
            </div>
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">📝 الاختبارات</div>
                <div style="color: var(--gold); font-size: 28px; font-weight: 700;">{{ number_format($totalExams ?? 0) }}</div>
            </div>
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; text-align: center;">
                <div style="color: var(--text-secondary); font-size: 13px; margin-bottom: 6px;">🔄 خطط المراجعة النشطة</div>
                <div style="color: var(--gold); font-size: 28px; font-weight: 700;">{{ number_format($activeRevisionPlans ?? 0) }}</div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
                <h3 style="color: var(--text-primary); font-size: 16px; font-weight: 700; margin-bottom: 16px;">📊 توزيع حالات التسجيلات</h3>
                <canvas id="statusChart" height="200"></canvas>
            </div>
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
                <h3 style="color: var(--text-primary); font-size: 16px; font-weight: 700; margin-bottom: 16px;">📈 النشاط آخر 30 يوم</h3>
                <canvas id="activityChart" height="200"></canvas>
            </div>
        </div>

        {{-- Top Students & Circles --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            {{-- Top Students --}}
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color);">
                    <h3 style="color: var(--text-primary); font-size: 16px; font-weight: 700;">🏆 أفضل الطلاب</h3>
                </div>
                <div style="padding: 12px;">
                    @if(isset($topStudents) && count($topStudents) > 0)
                        @foreach($topStudents as $index => $item)
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; margin-bottom: 4px; {{ $index < 3 ? 'background: rgba(201,168,76,0.08);' : '' }}">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: {{ $index < 3 ? 'var(--gold)' : 'var(--text-muted)' }}; font-weight: 700; font-size: 14px;">{{ $index + 1 }}</span>
                                    <span style="color: var(--text-primary);">{{ $item->student?->full_name ?? 'غير معروف' }}</span>
                                </div>
                                <span style="color: var(--gold); font-weight: 700;">{{ number_format($item->avg_score, 1) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--text-muted); text-align: center; padding: 16px;">لا توجد بيانات كافية</p>
                    @endif
                </div>
            </div>

            {{-- Top Circles --}}
            <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color);">
                    <h3 style="color: var(--text-primary); font-size: 16px; font-weight: 700;">🏅 أكثر الحلقات نشاطاً</h3>
                </div>
                <div style="padding: 12px;">
                    @if(isset($topCircles) && count($topCircles) > 0)
                        @foreach($topCircles as $index => $circle)
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; margin-bottom: 4px; background: rgba(11,31,20,0.3);">
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <span style="color: var(--text-muted);">{{ $index + 1 }}</span>
                                    <span style="color: var(--text-primary);">{{ $circle->name }}</span>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px;">
                                    <span style="color: var(--gold);">{{ $circle->submissions_count ?? 0 }} تسجيل</span>
                                    <span style="color: #6ee7b7;">{{ $circle->circle_students_count ?? 0 }} طالب</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--text-muted); text-align: center; padding: 16px;">لا توجد بيانات كافية</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div style="background: var(--green-800); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color);">
                <h3 style="color: var(--text-primary); font-size: 16px; font-weight: 700;">🔄 آخر النشاطات</h3>
            </div>
            <div style="padding: 12px;">
                @if(isset($recentActivity) && count($recentActivity) > 0)
                    @foreach($recentActivity as $activity)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; margin-bottom: 4px; background: rgba(11,31,20,0.3); font-size: 13px;">
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                                <span style="color: var(--text-primary);">{{ $activity->student?->full_name ?? 'غير معروف' }}</span>
                                <span style="color: var(--text-muted);">—</span>
                                <span style="color: var(--text-secondary);">{{ $activity->surah_display }}</span>
                                <span style="color: var(--text-muted);">في</span>
                                <span style="color: var(--gold);">{{ $activity->circle?->name ?? 'غير معروف' }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($activity->score)
                                    <span style="color: var(--gold); font-weight: 600;">{{ $activity->score }}/100</span>
                                @endif
                                <span style="color: var(--text-muted);">{{ $activity->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="color: var(--text-muted); text-align: center; padding: 16px;">لا توجد نشاطات حديثة</p>
                @endif
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(isset($statusDistribution))
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['قيد المراجعة ({{ $statusDistribution["pending"] }})', 'مُراجَع ({{ $statusDistribution["reviewed"] }})', 'يحتاج تحسين ({{ $statusDistribution["needs_work"] }})'],
                datasets: [{
                    data: [{{ $statusDistribution["pending"] }}, {{ $statusDistribution["reviewed"] }}, {{ $statusDistribution["needs_work"] }}],
                    backgroundColor: ['#C9A84C', '#6ee7b7', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#8A9A8E' }
                    }
                }
            }
        });
        @endif

        @if(isset($submissionsByDay) && count($submissionsByDay) > 0)
        new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($submissionsByDay->pluck('date')->toArray()) !!},
                datasets: [{
                    label: 'التسجيلات',
                    data: {!! json_encode($submissionsByDay->pluck('count')->toArray()) !!},
                    borderColor: '#C9A84C',
                    backgroundColor: 'rgba(201, 168, 76, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { color: '#8A9A8E' }
                    }
                },
                scales: {
                    x: { ticks: { color: '#5C6D60' } },
                    y: { ticks: { color: '#5C6D60' } }
                }
            }
        });
        @endif
    </script>
    @endpush
@endsection
