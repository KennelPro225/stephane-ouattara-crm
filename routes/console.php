<?php

use App\Jobs\SendProgrammeReminder;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SendProgrammeReminder)->daily()->at('08:00');
