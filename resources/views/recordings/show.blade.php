@extends('layouts.student')

@section('page-title', 'عرض التسجيل')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    {{-- Header --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('recordings.dashboard') }}" style="color: var(--gold); text-decoration: none; font-size: 14px; margin-bottom: 16px; display: inline-block;">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
        <h1 style="color: var(--cream); font-size: 28px; font-weight: 700; margin-bottom: 8px;">
            <i class="fas fa-book-quran" style="color: var(--gold);"></i> {{ $submission->surah }}
        </h1>
    </div>

    {{-- Main Content --}}
    <div class="card" style="margin-bottom: 24px;">
        {{-- Recording Info --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
            <div>
                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">السورة</div>
                <div style="color: var(--cream); font-weight: 600;">{{ $submission->surah }}</div>
            </div>

            @if($submission->ayah_from)
                <div>
                    <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">الآيات</div>
                    <div style="color: var(--cream); font-weight: 600;">
                        من {{ $submission->ayah_from }}
                        @if($submission->ayah_to)
                            إلى {{ $submission->ayah_to }}
                        @endif
                    </div>
                </div>
            @endif

            <div>
                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">الجزء</div>
                <div style="color: var(--cream); font-weight: 600;">{{ $submission->juz }}</div>
            </div>

            <div>
                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">تاريخ التسجيل</div>
                <div style="color: var(--cream); font-weight: 600;">{{ $submission->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        {{-- Audio Player --}}
        @if($submission->file_path)
            <div style="margin-bottom: 24px;">
                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 12px; font-weight: 600;">مشغل الصوت</div>
                <audio controls style="width: 100%; background: var(--dark-bg)/30; border-radius: 6px; padding: 12px; display: block;">
                    <source src="{{ Storage::url($submission->file_path) }}" type="audio/mpeg">
                    متصفحك لا يدعم مشغل الصوت
                </audio>
            </div>
        @endif

        {{-- Image Display --}}
        @if($submission->image_path)
            <div style="margin-bottom: 24px;">
                <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 12px; font-weight: 600;">الصورة</div>
                <img src="{{ Storage::url($submission->image_path) }}" alt="صورة التسجيل" style="max-width: 100%; height: auto; border-radius: 6px;">
            </div>
        @endif

        {{-- Status --}}
        <div style="background: var(--gold)/10; padding: 16px; border-radius: 6px; margin-bottom: 24px;">
            <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; align-items: center;">
                <div>
                    <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">حالة التسجيل</div>
                    @switch($submission->status)
                        @case('pending')
                            <span class="badge" style="background: #F59E0B/20; color: #F59E0B; padding: 6px 12px; border-radius: 12px; font-size: 12px;">⏳ قيد المراجعة</span>
                            @break
                        @case('accepted')
                            <span class="badge" style="background: #10B981/20; color: #10B981; padding: 6px 12px; border-radius: 12px; font-size: 12px;">✓ مقبول</span>
                            @break
                        @case('needs_work')
                            <span class="badge" style="background: #EF4444/20; color: #EF4444; padding: 6px 12px; border-radius: 12px; font-size: 12px;">⚠ يحتاج تحسين</span>
                            @break
                    @endswitch
                </div>

                @if($submission->rating)
                    <div style="text-align: right;">
                        <div style="color: var(--slate-blue); font-size: 12px; margin-bottom: 4px;">تقييم المعلم</div>
                        <div style="display: flex; gap: 2px; justify-content: flex-end;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $submission->rating)
                                    <i class="fas fa-star" style="color: var(--gold);"></i>
                                @else
                                    <i class="far fa-star" style="color: var(--slate-blue);"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        @if($submission->review_notes)
            <div style="background: var(--gold)/10; border-right: 3px solid var(--gold); padding: 16px; border-radius: 4px; margin-bottom: 16px;">
                <div style="color: var(--gold); font-weight: 600; margin-bottom: 8px;">📝 ملاحظات المعلم</div>
                <div style="color: var(--cream); overflow-wrap: break-word;">{{ $submission->review_notes }}</div>
            </div>
        @endif

        @if($submission->notes)
            <div style="background: var(--slate-blue)/10; border-right: 3px solid var(--slate-blue); padding: 16px; border-radius: 4px; margin-bottom: 24px;">
                <div style="color: var(--slate-blue); font-weight: 600; margin-bottom: 8px;">💬 ملاحظاتك</div>
                <div style="color: var(--cream); overflow-wrap: break-word;">{{ $submission->notes }}</div>
            </div>
        @endif
    </div>

    {{-- Self Rating Card --}}
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-title">
            <i class="fas fa-star" style="color: var(--gold);"></i>
            قيّم تسجيلك
        </div>

        <form id="ratingForm" method="POST" action="{{ route('recordings.rate', $submission) }}">
            @csrf

            {{-- Self Rating Stars --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: var(--cream); font-weight: 600; margin-bottom: 12px;">كم تقيّم تسجيلك؟</label>
                <div style="display: flex; gap: 8px; font-size: 32px;">
                    @for($i = 1; $i <= 5; $i++)
                        <i 
                            class="far fa-star" 
                            data-rating="{{ $i }}"
                            style="cursor: pointer; color: {{ $submission->self_rating && $i <= $submission->self_rating ? 'var(--gold)' : 'var(--slate-blue)' }}; transition: all 0.2s;"
                            onmouseover="highlightStars(this, {{ $i }})"
                            onmouseout="resetStars()"
                            onclick="setRating({{ $i }})"
                        ></i>
                    @endfor
                </div>
                <input type="hidden" name="self_rating" id="selfRatingInput" value="{{ $submission->self_rating ?? '' }}">
            </div>

            {{-- Self Notes --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: var(--cream); font-weight: 600; margin-bottom: 12px;">ملاحظاتك على أدائك</label>
                <textarea 
                    name="notes" 
                    id="selfNotes"
                    rows="4"
                    class="form-control"
                    placeholder="شارك ملاحظاتك عن تسجيلك..."
                    style="font-family: inherit; resize: vertical;">{{ $submission->self_notes ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 20px; width: 100%;">
                <i class="fas fa-save"></i> حفظ التقييم
            </button>
        </form>
    </div>

    {{-- Download Button --}}
    @if($submission->file_path)
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('submissions.download', $submission) }}" class="btn btn-secondary" style="padding: 12px 24px; flex: 1; text-align: center;">
                <i class="fas fa-download"></i> تحميل الملف
            </a>
        </div>
    @endif
</div>

<script>
    const starInputs = document.querySelectorAll('[data-rating]');
    const selfRatingInput = document.getElementById('selfRatingInput');

    function highlightStars(element, rating) {
        document.querySelectorAll('[data-rating]').forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= rating) {
                star.style.color = 'var(--gold)';
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.style.color = 'var(--slate-blue)';
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }

    function resetStars() {
        const currentRating = selfRatingInput.value || 0;
        document.querySelectorAll('[data-rating]').forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= currentRating) {
                star.style.color = 'var(--gold)';
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.style.color = 'var(--slate-blue)';
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }

    function setRating(rating) {
        selfRatingInput.value = rating;
        highlightStars(null, rating);
    }

    document.getElementById('ratingForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('self_rating', selfRatingInput.value);
        formData.append('notes', document.getElementById('selfNotes').value);

        try {
            const response = await fetch('{{ route("recordings.rate", $submission) }}', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();
            
            if (data.success) {
                alert('تم حفظ التقييم بنجاح ✓');
            } else {
                alert('حدث خطأ: ' + (data.error || 'فشل الحفظ'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
        }
    });

    // تهيئة النجوم عند تحميل الصفحة
    resetStars();
</script>

<style>
    .form-control {
        background: var(--dark-bg);
        color: var(--cream);
        border: 1px solid var(--border);
        padding: 12px;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 2px var(--gold)/20;
    }

    audio {
        outline: none;
    }

    audio::-webkit-media-controls {
        background: var(--dark-bg);
    }
</style>
@endsection
