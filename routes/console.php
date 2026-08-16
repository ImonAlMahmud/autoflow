<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('content:scan-due')->everyMinute();
Schedule::command('content:cleanup-workspaces')->daily();
Schedule::command('content:health-check')->everyFifteenMinutes();
