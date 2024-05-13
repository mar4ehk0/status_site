<?php

use App\Domain\Repository\SiteRepositoryInterface;
use App\Domain\Service\NotifierInterface;
use App\Infrastructure\Client;
use App\Infrastructure\Repository\FileJson\Site\FileJsonDataObject;
use App\Infrastructure\Repository\FileJson\Site\SiteFileJsonDataMapper;
use App\Infrastructure\Repository\FileJson\Site\SiteRepositoryFileJson;
use App\Infrastructure\Repository\FileJson\Site\Validator;
use App\Infrastructure\Service\MessageGenerator;
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
        new Reference(HttpClientInterface::class),
        getenv('HTTP_GET_REQUEST_MAX_DURATION')
    ]);

$containerBuilder->register('slack.message.generator', MessageGenerator::class)
    ->setArguments([
        getenv('SLACK_TMP_MSG_SITE_DOWN'),
        getenv('SLACK_TMP_MSG_SITE_UP')
    ]);
$containerBuilder->register(SlackNotifier::class, SlackNotifier::class)
    ->setArguments([
        new Reference(Client::class),
        getenv('TRANSPORT_SLACK_DSN'),
        new Reference('slack.message.generator'),
    ]);

$containerBuilder->register(NotifierInterface::class, Notifier::class)
    ->setArguments([
        new Reference(SlackNotifier::class),
    ]);

$containerBuilder->register(SiteCheckerUseCase::class, SiteCheckerUseCase::class)
    ->setArguments([
        new Reference(Client::class),
        new Reference(SiteRepositoryInterface::class),
        new Reference(NotifierInterface::class),
    ]);

$containerBuilder->register(SiteCheckerCommand::class, SiteCheckerCommand::class)
    ->setArguments([
        new Reference(SiteCheckerUseCase::class)
    ]);

return $containerBuilder;
