<?php

use App\Domain\Repository\SiteRepositoryInterface;
use App\Domain\Service\NotifierInterface;
use App\Infrastructure\Client;
use App\Infrastructure\Repository\FileJson\Site\FileJsonDataObject;
use App\Infrastructure\Repository\FileJson\Site\SiteFileJsonDataMapper;
use App\Infrastructure\Repository\FileJson\Site\SiteRepositoryFileJson;
use App\Infrastructure\Repository\FileJson\Site\Validator;
use App\Infrastructure\Service\Message;
use App\Infrastructure\Service\Notifier\EmailNotifier;
use App\Infrastructure\Service\Notifier\Notifier;
use App\Infrastructure\Service\Notifier\SlackNotifier;
use App\UseCase\SiteCheckerUseCase;
use App\UserInterface\Console\SiteCheckerCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

$containerBuilder = new ContainerBuilder();

$containerBuilder->register(SiteFileJsonDataMapper::class, SiteFileJsonDataMapper::class);
$containerBuilder->register(Validator::class, Validator::class);
$containerBuilder->register(FileJsonDataObject::class, FileJsonDataObject::class)
    ->setArguments([
        STORAGE_PATH,
        STORAGE_PATH . 'sites_config.json',
        new Reference(SiteFileJsonDataMapper::class),
        new Reference(Validator::class),
    ]);

$containerBuilder->register(SiteRepositoryInterface::class, SiteRepositoryFileJson::class)
    ->setArguments([
        new Reference(FileJsonDataObject::class),
    ]);
$containerBuilder->register(HttpClientInterface::class, HttpClient::class)
    ->setFactory([HttpClient::class, 'create']);

$containerBuilder->register(Client::class, Client::class)
    ->setArguments([
        new Reference(HttpClientInterface::class)
    ]);
$containerBuilder->register(SiteCheckerUseCase::class, SiteCheckerUseCase::class)
    ->setArguments([
        new Reference(Client::class),
        new Reference(SiteRepositoryInterface::class),
        new Reference(NotifierInterface::class),
        new Reference(Message::class),
    ]);
$containerBuilder->register(SiteCheckerCommand::class, SiteCheckerCommand::class)
    ->setArguments([
        new Reference(SiteCheckerUseCase::class)
    ]);

$containerBuilder->register(EmailNotifier::class, EmailNotifier::class);
$containerBuilder->register(SlackNotifier::class, SlackNotifier::class);
$containerBuilder->register(NotifierInterface::class, Notifier::class)
    ->setArguments([
        new Reference(EmailNotifier::class),
        new Reference(SlackNotifier::class)
    ]);

$containerBuilder->register(Message::class, Message::class)
    ->setArguments([
        getenv('TMP_MSG_SITE_DOWN'),
        getenv('TMP_MSG_SITE_UP')
    ]);



return $containerBuilder;
