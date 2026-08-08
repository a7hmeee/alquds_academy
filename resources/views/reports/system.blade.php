<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تقرير النظام الشامل — Circle System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #C9A84C;
            --gold-light: #E8D48B;
            --green-900: #0B1F14;
            --green-800: #122A1C;
            --green-700: #1A3828;
            --green-600: #245035;
            --text-primary: #1a1a2e;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border: #e2e8f0;
            --bg-white: #ffffff;
            --bg-light: #f7fafc;
            --bg-card: #ffffff;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
            --info: #3182ce;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            color: var(--text-primary);
            background: var(--bg-light);
            line-height: 1.7;
            font-size: 14px;
        }

        .report-container {
            max-width: 210mm;
            margin: 0 auto;
            background: var(--bg-white);
        }

        /* ── HEADER ── */
        .report-header {
            background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 100%);
            color: white;
            padding: 40px 50px 35px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .report-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(201,168,76,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .report-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(201,168,76,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .logo-container {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .logo-container img {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            border: 3px solid rgba(201,168,76,0.4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .logo-placeholder {
            width: 100px;
            height: 100px;
            margin: 0 auto 16px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--green-900);
            font-weight: 800;
            border: 3px solid rgba(201,168,76,0.4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .report-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .report-header .subtitle {
            font-size: 1rem;
            opacity: 0.85;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .report-header .date-badge {
            display: inline-block;
            margin-top: 14px;
            padding: 6px 20px;
            background: rgba(201,168,76,0.2);
            border: 1px solid rgba(201,168,76,0.4);
            border-radius: 20px;
            font-size: 0.85rem;
            color: var(--gold-light);
            position: relative;
            z-index: 1;
        }

        /* ── CONTENT ── */
        .report-body {
            padding: 30px 50px 40px;
        }

        .section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--green-900);
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--gold);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title .icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .section-title .icon.gold { background: rgba(201,168,76,0.15); color: var(--gold); }
        .section-title .icon.green { background: rgba(56,161,105,0.15); color: var(--success); }
        .section-title .icon.blue { background: rgba(49,130,206,0.15); color: var(--info); }
        .section-title .icon.red { background: rgba(229,62,62,0.15); color: var(--danger); }
        .section-title .icon.purple { background: rgba(128,90,213,0.15); color: #805ad5; }

        /* ── STATS GRID ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 16px;
            text-align: center;
            transition: all 0.2s;
        }

        .stat-card:hover {
            border-color: var(--gold);
            box-shadow: 0 4px 12px rgba(201,168,76,0.1);
        }

        .stat-card .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--green-900);
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ── TABLE ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .data-table thead th {
            background: var(--green-900);
            color: white;
            padding: 10px 14px;
            text-align: right;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .data-table thead th:first-child { border-radius: 0 8px 0 0; }
        .data-table thead th:last-child { border-radius: 8px 0 0 0; }

        .data-table tbody td {
            padding: 9px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
        }

        .data-table tbody tr:hover {
            background: rgba(201,168,76,0.04);
        }

        .data-table tbody tr:nth-child(even) {
            background: var(--bg-light);
        }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-success { background: rgba(56,161,105,0.12); color: #276749; }
        .badge-warning { background: rgba(214,158,46,0.12); color: #975a16; }
        .badge-danger { background: rgba(229,62,62,0.12); color: #c53030; }
        .badge-info { background: rgba(49,130,206,0.12); color: #2b6cb0; }
        .badge-muted { background: #edf2f7; color: var(--text-muted); }

        /* ── ALERTS ── */
        .alert-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            border: 1px solid;
        }

        .alert-item.danger { background: #fff5f5; border-color: #fed7d7; color: #c53030; }
        .alert-item.warning { background: #fffff0; border-color: #fefcbf; color: #975a16; }
        .alert-item.info { background: #ebf8ff; border-color: #bee3f8; color: #2b6cb0; }
        .alert-item.success { background: #f0fff4; border-color: #c6f6d5; color: #276749; }

        .alert-item .alert-icon {
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-item .alert-title {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .alert-item .alert-detail {
            font-size: 0.82rem;
            opacity: 0.85;
        }

        /* ── INSIGHTS ── */
        .insight-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            background: var(--bg-light);
            border: 1px solid var(--border);
            margin-bottom: 10px;
        }

        .insight-card .insight-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .insight-card .insight-icon.success { background: rgba(56,161,105,0.15); color: var(--success); }
        .insight-card .insight-icon.warning { background: rgba(214,158,46,0.15); color: var(--warning); }
        .insight-card .insight-icon.danger { background: rgba(229,62,62,0.15); color: var(--danger); }
        .insight-card .insight-icon.info { background: rgba(49,130,206,0.15); color: var(--info); }

        .insight-card .insight-text {
            font-size: 0.88rem;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* ── SCORE BAR ── */
        .score-bar {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .score-bar .bar {
            flex: 1;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .score-bar .bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s;
        }

        .score-bar .bar-fill.green { background: var(--success); }
        .score-bar .bar-fill.gold { background: var(--gold); }
        .score-bar .bar-fill.red { background: var(--danger); }

        .score-bar .score-text {
            font-size: 0.78rem;
            font-weight: 600;
            min-width: 35px;
            text-align: left;
        }

        /* ── FOOTER ── */
        .report-footer {
            background: var(--green-900);
            color: rgba(255,255,255,0.7);
            padding: 20px 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
        }

        .report-footer span {
            color: var(--gold);
        }

        /* ── PAGE BREAK ── */
        .page-break {
            page-break-before: always;
            margin-top: 0;
            padding-top: 30px;
        }

        /* ── SUBMISSIONS MINI CHART ── */
        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 40px;
        }

        .mini-chart .bar {
            flex: 1;
            background: var(--gold);
            border-radius: 2px 2px 0 0;
            min-width: 4px;
            opacity: 0.7;
        }

        /* ── PRINT STYLES ── */
        @media print {
            body { background: white; font-size: 12px; }
            .report-container { max-width: 100%; box-shadow: none; }
            .report-header { padding: 30px 40px 25px; }
            .report-body { padding: 20px 40px 30px; }
            .section { page-break-inside: avoid; }
            .no-print { display: none !important; }
            .stat-card { break-inside: avoid; }
            .data-table { font-size: 0.78rem; }
        }

        /* ── PRINT BUTTON ── */
        .print-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            padding: 14px 28px;
            background: linear-gradient(135deg, var(--green-900), var(--green-700));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            z-index: 9999;
            font-family: 'Cairo', sans-serif;
            transition: all 0.2s;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.4);
        }

        @media (max-width: 640px) {
            .stats-grid, .overview-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media print {
            .print-btn { display: none !important; }
        }
    </style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()" title="طباعة التقرير كـ PDF">
    🖨️ طباعة التقرير
</button>

<div class="report-container">

    <!-- ═══════════════════ HEADER ═══════════════════ -->
    <div class="report-header">
        <div class="logo-container">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            @else
                <div class="logo-placeholder">ق</div>
            @endif
        </div>
        <h1>تقرير النظام الشامل</h1>
        <div class="subtitle">Circle System — تقرير تحليلي شامل لأداء المنصة التعليمية</div>
        <div class="date-badge">📅 {{ $now->format('d / m / Y') }} — {{ $now->format('l') }}</div>
    </div>

    <!-- ═══════════════════ BODY ═══════════════════ -->
    <div class="report-body">

        <!-- ══ SECTION 1: Executive Summary ══ -->
        <div class="section">
            <div class="section-title">
                <span class="icon gold">📌</span>
                الملخص التنفيذي
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🕌</div>
                    <div class="stat-value">{{ $activeCircles }}</div>
                    <div class="stat-label">حلقة نشطة من {{ $totalCircles }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🎓</div>
                    <div class="stat-value">{{ $activeStudents }}</div>
                    <div class="stat-label">طالب نشط من {{ $totalStudents }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👨‍🏫</div>
                    <div class="stat-value">{{ $totalTeachers }}</div>
                    <div class="stat-label">معلم مسجّل</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-value">{{ $totalSubmissions }}</div>
                    <div class="stat-label">إجمالي التسليمات</div>
                </div>
            </div>

            <div style="background: var(--bg-light); border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-top: 16px;">
                <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 12px; color: var(--green-900);">📊 نظرة عامة</h3>
                <div class="overview-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.88rem;">
                    <div>
                        <span style="color: var(--text-muted);">معدل التفاعل:</span>
                        <strong style="color: var(--green-900);">{{ $engagementRate }}%</strong>
                        <span style="color: var(--text-muted); font-size: 0.78rem;">(تسليمات / طالب)</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">متوسط الدرجات:</span>
                        <strong style="color: {{ $avgScore >= 70 ? 'var(--success)' : ($avgScore >= 50 ? 'var(--warning)' : 'var(--danger)') }};">{{ $avgScore }}/100</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">التسليمات المقبولة:</span>
                        <strong style="color: var(--success);">{{ $acceptedSubmissions }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">بانتظار المراجعة:</span>
                        <strong style="color: var(--warning);">{{ $pendingSubmissions }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">تحتاج تحسين:</span>
                        <strong style="color: var(--danger);">{{ $needsWorkSubmissions }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">سجلات التقدم:</span>
                        <strong style="color: var(--info);">{{ $totalProgressRecords }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ SECTION 2: General Statistics ══ -->
        <div class="section">
            <div class="section-title">
                <span class="icon green">📊</span>
                الإحصائيات العامة
            </div>

            <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
                <div class="stat-card">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-value">{{ $totalOrganizations }}</div>
                    <div class="stat-label">جهة / مؤسسة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-value">{{ $acceptedSubmissions }}</div>
                    <div class="stat-label">تسليمات مقبولة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-value">{{ $pendingSubmissions }}</div>
                    <div class="stat-label">قيد المراجعة</div>
                </div>
            </div>

            @if($submissionsByMonth->count())
            <div style="background: var(--bg-light); border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-top: 16px;">
                <h3 style="font-size: 0.9rem; font-weight: 700; margin-bottom: 12px; color: var(--green-900);">📈 التسليمات خلال آخر 6 أشهر</h3>
                <div style="display: flex; align-items: flex-end; gap: 8px; height: 60px;">
                    @php
                        $maxMonth = $submissionsByMonth->max('count') ?: 1;
                    @endphp
                    @foreach($submissionsByMonth as $m)
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                        <span style="font-size: 0.7rem; font-weight: 600; color: var(--text-primary);">{{ $m->count }}</span>
                        <div style="width: 100%; background: var(--gold); border-radius: 4px 4px 0 0; height: {{ ($m->count / $maxMonth) * 50 }}px; min-height: 4px; opacity: 0.8;"></div>
                        <span style="font-size: 0.65rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($m->month)->format('M') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- ══ SECTION 3: Circles Report ══ -->
        <div class="section page-break">
            <div class="section-title">
                <span class="icon blue">🕌</span>
                تقرير الحلقات
            </div>

            @if($circlesData->count())
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الحلقة</th>
                            <th>الحالة</th>
                            <th>النوع</th>
                            <th>الجهة</th>
                            <th>الجزء</th>
                            <th>الطلاب</th>
                            <th>التسليمات</th>
                            <th>المتوسط</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($circlesData as $i => $c)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $c['name'] }}</strong></td>
                            <td>
                                @if($c['status'] === 'active')
                                    <span class="badge badge-success">نشط</span>
                                @elseif($c['status'] === 'paused')
                                    <span class="badge badge-warning">موقوف</span>
                                @else
                                    <span class="badge badge-muted">مؤرشف</span>
                                @endif
                            </td>
                            <td>{{ $c['type'] }}</td>
                            <td>{{ $c['organization'] }}</td>
                            <td>{{ $c['juz'] }}</td>
                            <td>{{ $c['active_students'] }} / {{ $c['capacity'] }}</td>
                            <td>{{ $c['total_submissions'] }}</td>
                            <td>
                                @if($c['avg_score'] !== '—')
                                    <div class="score-bar">
                                        <div class="bar">
                                            <div class="bar-fill {{ $c['avg_score'] >= 70 ? 'green' : ($c['avg_score'] >= 50 ? 'gold' : 'red') }}" style="width: {{ $c['avg_score'] }}%"></div>
                                        </div>
                                        <span class="score-text" style="color: {{ $c['avg_score'] >= 70 ? 'var(--success)' : ($c['avg_score'] >= 50 ? 'var(--warning)' : 'var(--danger)') }}">{{ $c['avg_score'] }}</span>
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">لا توجد حلقات مسجّلة بعد</p>
            @endif

            @if($strongCircles->count())
            <div style="margin-top: 20px; padding: 16px; background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 10px;">
                <h4 style="font-size: 0.88rem; color: #276749; margin-bottom: 8px;">💪 الحلقات القوية (متوسط 80+)</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach($strongCircles as $sc)
                        <span class="badge badge-success">{{ $sc['name'] }} — {{ $sc['avg_score'] }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($weakCircles->count())
            <div style="margin-top: 12px; padding: 16px; background: #fffff0; border: 1px solid #fefcbf; border-radius: 10px;">
                <h4 style="font-size: 0.88rem; color: #975a16; margin-bottom: 8px;">⚠️ الحلقات الضعيفة (أقل من 5 تسليمات)</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach($weakCircles as $wc)
                        <span class="badge badge-warning">{{ $wc['name'] }} — {{ $wc['total_submissions'] }} تسجيل</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- ══ SECTION 4: Students Report ══ -->
        <div class="section page-break">
            <div class="section-title">
                <span class="icon green">🎓</span>
                تقرير الطلاب
            </div>

            @if($topStudents->count())
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--green-900); margin-bottom: 12px;">🏆 الطلاب المتفوقون (متوسط 85+)</h3>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الحلقات</th>
                                <th>التسليمات</th>
                                <th>المقبول</th>
                                <th>المتوسط</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStudents as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $s['name'] }}</strong></td>
                                <td>{{ $s['circle_count'] }}</td>
                                <td>{{ $s['total_submissions'] }}</td>
                                <td>{{ $s['accepted_submissions'] }}</td>
                                <td>
                                    <strong style="color: var(--success);">{{ $s['avg_score'] }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($atRiskStudents->count())
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--danger); margin-bottom: 12px;">⚠️ طلاب في خطر (درجة &lt; 50 أو يحتاجون مساعدة)</h3>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الحالة</th>
                                <th>التسليمات</th>
                                <th>المتوسط</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($atRiskStudents->take(15) as $s)
                            <tr>
                                <td><strong>{{ $s['name'] }}</strong></td>
                                <td><span class="badge badge-danger">{{ $s['status'] }}</span></td>
                                <td>{{ $s['total_submissions'] }}</td>
                                <td><strong style="color: var(--danger);">{{ $s['avg_score'] }}</strong></td>
                                <td>
                                    @if($s['needs_assistance'])
                                        <span class="badge badge-warning">يحتاج مساعدة</span>
                                    @else
                                        <span class="badge badge-danger">درجة منخفضة</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($inactiveStudents->count())
            <div style="padding: 16px; background: #ebf8ff; border: 1px solid #bee3f8; border-radius: 10px;">
                <h4 style="font-size: 0.88rem; color: #2b6cb0; margin-bottom: 8px;">📋 طلاب لم يرفعوا تسجيلات بعد: {{ $inactiveStudents->count() }} طالب</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach($inactiveStudents->take(20) as $s)
                        <span class="badge badge-info">{{ $s['name'] }}</span>
                    @endforeach
                    @if($inactiveStudents->count() > 20)
                        <span class="badge badge-muted">+{{ $inactiveStudents->count() - 20 }} طالب آخر</span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- ══ SECTION 5: Teachers Report ══ -->
        <div class="section page-break">
            <div class="section-title">
                <span class="icon purple">👨‍🏫</span>
                تقرير المعلمين
            </div>

            @if($teachersData->count())
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>التخصص</th>
                            <th>الحلقات</th>
                            <th>الطلاب</th>
                            <th>التسليمات</th>
                            <th>بانتظار</th>
                            <th>المتوسط</th>
                            <th>التقدم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachersData as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $t['name'] }}</strong></td>
                            <td>{{ $t['specialization'] }}</td>
                            <td>{{ $t['circle_count'] }}</td>
                            <td>{{ $t['student_count'] }}</td>
                            <td>{{ $t['submission_count'] }}</td>
                            <td>
                                @if($t['pending_count'] > 0)
                                    <span class="badge badge-warning">{{ $t['pending_count'] }}</span>
                                @else
                                    <span style="color: var(--success);">✓</span>
                                @endif
                            </td>
                            <td>
                                @if($t['avg_score'] !== '—')
                                    <strong style="color: {{ $t['avg_score'] >= 70 ? 'var(--success)' : 'var(--warning)' }};">{{ $t['avg_score'] }}</strong>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>{{ $t['progress_records'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">لا يوجد معلمين مسجّلين بعد</p>
            @endif
        </div>

        <!-- ══ SECTION 6: Submissions Report ══ -->
        <div class="section">
            <div class="section-title">
                <span class="icon gold">📝</span>
                تقرير التسليمات
            </div>

            <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
                <div class="stat-card">
                    <div class="stat-icon">📤</div>
                    <div class="stat-value" style="color: var(--info);">{{ $totalSubmissions }}</div>
                    <div class="stat-label">إجمالي</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-value" style="color: var(--warning);">{{ $pendingSubmissions }}</div>
                    <div class="stat-label">قيد المراجعة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-value" style="color: var(--success);">{{ $acceptedSubmissions }}</div>
                    <div class="stat-label">مقبولة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔄</div>
                    <div class="stat-value" style="color: var(--danger);">{{ $needsWorkSubmissions }}</div>
                    <div class="stat-label">تحتاج تحسين</div>
                </div>
            </div>

            @if($recentSubmissions->count())
            <div style="margin-top: 16px;">
                <h3 style="font-size: 0.9rem; font-weight: 700; color: var(--green-900); margin-bottom: 12px;">📋 أحدث 20 تسجيل</h3>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الحلقة</th>
                                <th>السورة</th>
                                <th>الدرجة</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSubmissions as $s)
                            <tr>
                                <td>{{ $s['student'] }}</td>
                                <td>{{ $s['circle'] }}</td>
                                <td>{{ $s['surah'] }}</td>
                                <td>
                                    @if($s['score'] !== null)
                                        <strong style="color: {{ $s['score'] >= 70 ? 'var(--success)' : ($s['score'] >= 50 ? 'var(--warning)' : 'var(--danger)') }};">{{ $s['score'] }}</strong>
                                    @else
                                        <span style="color: var(--text-muted);">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s['status'] === 'accepted')
                                        <span class="badge badge-success">مقبول</span>
                                    @elseif($s['status'] === 'pending')
                                        <span class="badge badge-warning">بانتظار</span>
                                    @elseif($s['status'] === 'needs_work')
                                        <span class="badge badge-danger">يحتاج تحسين</span>
                                    @else
                                        <span class="badge badge-info">{{ $s['status'] }}</span>
                                    @endif
                                </td>
                                <td style="color: var(--text-muted);">{{ $s['date'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- ══ SECTION 7: Issues & Alerts ══ -->
        <div class="section page-break">
            <div class="section-title">
                <span class="icon red">⚠️</span>
                المشاكل والتنبيهات
            </div>

            @if(count($issues))
            <div class="alert-list">
                @foreach($issues as $issue)
                <div class="alert-item {{ $issue['type'] }}">
                    <span class="alert-icon">
                        @if($issue['type'] === 'danger') 🔴
                        @elseif($issue['type'] === 'warning') 🟡
                        @elseif($issue['type'] === 'info') 🔵
                        @else 🟢
                        @endif
                    </span>
                    <div>
                        <div class="alert-title">{{ $issue['title'] }}</div>
                        <div class="alert-detail">{{ $issue['detail'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 30px; background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 10px;">
                <span style="font-size: 2rem;">✅</span>
                <p style="color: #276749; font-weight: 600; margin-top: 8px;">لا توجد مشاكل حرجة — النظام يعمل بشكل سليم</p>
            </div>
            @endif
        </div>

        <!-- ══ SECTION 8: AI Insights ══ -->
        <div class="section">
            <div class="section-title">
                <span class="icon gold">🧠</span>
                التحليل الذكي
            </div>

            @if(count($insights))
            <div>
                @foreach($insights as $insight)
                <div class="insight-card">
                    <div class="insight-icon {{ $insight['type'] }}">
                        @if($insight['type'] === 'success') ✅
                        @elseif($insight['type'] === 'warning') ⚠️
                        @elseif($insight['type'] === 'danger') 🔴
                        @else ℹ️
                        @endif
                    </div>
                    <div class="insight-text">{{ $insight['text'] }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <div style="margin-top: 20px; padding: 20px; background: var(--bg-light); border: 1px solid var(--border); border-radius: 12px;">
                <h3 style="font-size: 0.92rem; font-weight: 700; color: var(--green-900); margin-bottom: 12px;">📊 ملخص التوصيات</h3>
                <div style="font-size: 0.85rem; line-height: 2; color: var(--text-secondary);">
                    @if($pendingSubmissions > 10)
                        <div>• مراجعة التسجيلات المعلقة — هناك {{ $pendingSubmissions }} تسجيل في انتظار المراجعة</div>
                    @endif
                    @if($atRiskStudents->count() > 0)
                        <div>• التواصل مع {{ $atRiskStudents->count() }} طالب في خطر لتقديم الدعمecessary</div>
                    @endif
                    @if($inactiveStudents->count() > 0)
                        <div>• تحفيز {{ $inactiveStudents->count() }} طالب لم يرفعوا تسجيلات بعد</div>
                    @endif
                    @if($weakCircles->count() > 0)
                        <div>• مراجعة {{ $weakCircles->count() }} حلقة ضعيفة وتفعيل الأنشطة فيها</div>
                    @endif
                    @if(count($issues) === 0 && count(array_filter($insights, fn($i) => $i['type'] === 'danger')) === 0)
                        <div>• النظام يعمل بشكل ممتاز — الاستمرار على هذا النهج ✅</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- ═══════════════════ FOOTER ═══════════════════ -->
    <div class="report-footer">
        <div>
            <span>أكاديمية القدس</span> — Circle System Reports
        </div>
        <div>
            {{ $now->format('d/m/Y — H:i') }}
        </div>
    </div>

</div>

</body>
</html>