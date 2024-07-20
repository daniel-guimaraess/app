<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:daily-gemini-analysis')->dailyAt('18:00');;