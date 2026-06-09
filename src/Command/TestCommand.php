<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'pet:test:command',
    description: 'opisanie',
)]
readonly class TestCommand
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Демонстрация Input/Output helper');

        $io->section('1. Интерактивный ввод');

        $name = $io->ask('Имя', 'Гость');

        $role = $io->choice('Выбери роль', ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'], 'ROLE_USER');

        $email = $io->ask('Введи email', null, $this->validateEmail(...));

        $confirmed = $io->confirm(sprintf('Создать запись для %s (%s) с ролью %s?', $name, $email, $role), true);

        if (!$confirmed) {
            $io->warning('Отменено пользователем.');

            return Command::SUCCESS;
        }

        $io->section('2. Progress bar');

        $steps = 50;
        $io->progressStart($steps);
        for ($i = 0; $i < $steps; ++$i) {
            usleep(20000);
            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success('Обработка завершена.');

        $io->section('3. Результат в виде таблицы');

        $rows = [];
        try {
            $users = $this->userRepository->findBy([], ['id' => 'ASC'], 10);
            foreach ($users as $user) {
                $rows[] = [
                    $user->getId(),
                    $user->getEmail(),
                    implode(', ', $user->getRoles()),
                ];
            }
        } catch (\Throwable $e) {
            $io->warning('БД недоступна, показываю только введённые данные: '.$e->getMessage());
        }

        $rows[] = ['—', $email, $role];
        $rows[] = ['—', '—', '—'];

        $io->table(['ID', 'Email', 'Roles'], $rows);

        $io->note(sprintf('Привет, %s! Показано записей: %d.', $name, count($rows)));

        return Command::SUCCESS;
    }

    private function validateEmail(?string $value): string
    {
        if (!$value || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Это не похоже на email.');
        }

        return $value;
    }
}
