@extends('layouts.app')

@section('title','تعديل معلم')

@section('content')
<div class="max-w-xl space-y-6">

    <h1 class="text-2xl font-bold">تعديل معلم</h1>

    <form action="{{ route('teachers.update',$teacher) }}" method="POST" enctype="multipart/form-data"
          class="space-y-4 p-4 rounded border border-white/10 bg-[var(--surface)]">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">الدرجة العلمية</label>
            <select name="academic_degree" class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
                @foreach(['hafiz','ijazah','bachelor','master','doctorate'] as $degree)
                    <option value="{{ $degree }}"
                        @selected($teacher->academic_degree === $degree)>
                        {{ ucfirst($degree) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">صورة جديدة</label>
            <input type="file" name="photo"
                   class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
        </div>

        <div>
            <label class="block mb-1">نبذة</label>
            <textarea name="bio"
                      class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
                {{ $teacher->bio }}
            </textarea>
        </div>

        <div>
            <label class="block mb-1">الحالة</label>
            <select name="status" class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
                <option value="active" @selected($teacher->status==='active')>نشط</option>
                <option value="paused" @selected($teacher->status==='paused')>موقوف</option>
            </select>
        </div>

        <button class="px-4 py-2 rounded bg-[var(--accent)] text-black font-semibold">
            تحديث
        </button>
    </form>

</div>
@endsection