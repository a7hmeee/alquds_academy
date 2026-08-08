@php
    $navigation = \App\Helpers\NavigationHelper::getNavigation();
    $currentRoute = Route::currentRouteName() ?? '';
@endphp

@foreach($navigation as $section)
    @if(!empty($section['items']))
        <div class="nav-group">
            <div class="nav-group-label">{{ $section['title'] }}</div>
            @foreach($section['items'] as $item)
                @if(\App\Helpers\NavigationHelper::canAccess($item['route']))
                    @php
                        $routePrefix = explode('.', $item['route'])[0];
                        $isActive = str_starts_with($currentRoute, $routePrefix);
                    @endphp
                    <a href="{{ route($item['route']) }}"
                       class="nav-item {{ $isActive ? 'active' : '' }}"
                       data-tip="{{ $item['name'] }}">
                        <i class="{{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    @endif
@endforeach

<div class="nav-group" style="margin-top: auto; padding-top: 8px; border-top: 1px solid var(--border-color);">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-item logout" data-tip="تسجيل الخروج">
            <i class="fas fa-sign-out-alt"></i>
            <span>تسجيل الخروج</span>
        </button>
    </form>
</div>
