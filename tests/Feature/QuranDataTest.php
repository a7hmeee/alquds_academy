<?php

// هذا الملف منقول إلى tests/Feature/Quran/QuranDataTest.php
// تم نقله ليتجنب RefreshDatabase الذي يمسح بيانات القرآن
test('placeholder', function () {
    expect(true)->toBeTrue();
})->skip();
