<?php

namespace App\Console\Commands;

use App\Models\PendingRegistration;
use Illuminate\Console\Command;

class AuthCleanCommand extends Command
{
    protected $signature = 'pending:clean';
    protected $description = 'حذف طلبات التسجيل المعلقة المنتهية الصلاحية';

    public function handle()
    {
        $deleted = PendingRegistration::expired()->delete();

        $this->info("تم حذف {$deleted} طلب تسجيل منتهي الصلاحية.");
    }
}
