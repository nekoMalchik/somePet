<?php

namespace App\Logger;

use Monolog\Attribute\AsMonologProcessor;
use Monolog\LogRecord;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsMonologProcessor]
class SessionRequestProcessor
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security
    ) {
    }

    // method is called for each log record; optimize it to not hurt performance
    public function __invoke(LogRecord $record): LogRecord
    {
        try {
            $session = $this->requestStack->getSession();
        } catch (SessionNotFoundException $e) {
            return $record;
        }
        if (!$session->isStarted()) {
            return $record;
        }

        $email = $this->security?->getToken()?->getUser()?->getUserIdentifier();

        $sessionId = substr($session->getId(), 0, 8) ?: '????????';

        $record->extra['token'] = $sessionId.'-'.substr(uniqid('', true), -8);
        $record->extra['userEmail'] = $email;

        return $record;
    }
}