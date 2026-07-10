<?php

use App\Console\Commands\DeleteTempUploadedFilesCommand;
use App\Console\Commands\UpdateBookingStatuses;

Schedule::command(DeleteTempUploadedFilesCommand::class)->hourly();
Schedule::command(UpdateBookingStatuses::class)->everyFifteenMinutes();
