<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LogBoilerReader extends AbstractLog
{
    protected string $widgetCode = 'log-reader';
    protected string $logFile = 'cron-boiler-run.log';
}
