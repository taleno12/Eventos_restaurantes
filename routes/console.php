<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notificaciones:verificar')->dailyAt('07:00');

Schedule::command('notificaciones:vencimiento')->dailyAt('08:00');
