<?php

namespace App\Logger;

use App\Entity\PgLog;
use App\Repository\PgLogRepository;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;

class DoctrineLogHandler extends AbstractProcessingHandler
{
    public function __construct(
        private RequestStack $requestStack,
        private PgLogRepository $pgRep,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    // method is called for each log record; optimize it to not hurt performance
    protected function write(LogRecord $record): void
    {
        $pgLog = new PgLog();
        $pgLog->setLevel($record->level->getName());
        $pgLog->setMessage($record->message);
        $pgLog->setCreatedAt($record->datetime);

        $this->pgRep->insert($pgLog);
    }
}
