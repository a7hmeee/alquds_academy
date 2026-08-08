@extends('layouts.app')

@section('title','تعديل حلقة')

@section('content')
<div class="max-w-xl space-y-6">

    <h1 class="text-2xl font-bold">تعديل حلقة</h1>

    <form method="POST" action="{{ route('circles.update',$circle) }}"
          class="space-y-4 p-4 rounded border border-white/10 bg-[var(--surface)]">
        @csrf
        @method('PUT')

        <input name="name" value="{{ $circle->name }}"
               class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">

        <select name="type" class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
            @foreach(['onsite','online','hybrid'] as $type)
                <option value="{{ $type }}" @selected($circle->type==$type)>
                    {{ $type }}
                </option>
            @endforeach
        </select>

        <input name="level" value="{{ $circle->level }}"
               class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">

        <input name="capacity" type="number" value="{{ $circle->capacity }}"
               class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">

        <select name="juz_id" class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
            <option value="">-- اختر الجزء --</option>
            @foreach(\App\Models\Juz::orderBy('id')->get() as $juz)
                <option value="{{ $juz->id }}" @selected($circle->juz_id == $juz->id)>
                    {{ $juz->name }} (جزء {{ $juz->id }})
                </option>
            @endforeach
        </select>

        <select name="status" class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">
            @foreach(['active','paused','archived'] as $status)
                <option value="{{ $status }}" @selected($circle->status==$status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        <textarea name="description"
                  class="w-full rounded px-3 py-2 bg-black/20 border border-white/10">{{ $circle->description }}</textarea>

        <button class="px-4 py-2 bg-[var(--primary)] text-white rounded">
            تحديث
        </button>
    </form>

</div>
@endsection