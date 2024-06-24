<?php

namespace App\Logging;

use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{

    protected $request;

    public function __construct(Request $request = null)
    {
      $this->request = $request;
    }

    /**
     * Customize the given logger instance.
     */
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
              "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
            ));

            $handler->pushProcessor([$this, 'processLogRecord']);
        }
    }

    public function processLogRecord($record)
    {
        $debugBacktrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 8);

        $record->extra = [
          'client_ip' => $this->request->getClientIp() ?? '-',
          'method' => $this->request->getMethod() ?? '-',
          'base_url' => $this->request->getUri() ?? '-',
          'user_id' => Auth::id() ?? '-',
          'session_id' => Session::getId() ?? '-',
          'file' => isset($debugBacktrace[6]['file']) && isset($debugBacktrace[6]['line']) ? $debugBacktrace[6]['file'] . ':' . $debugBacktrace[6]['line'] : '-'
        ];
        return $record;
    }
}
