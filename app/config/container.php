<?php

use App\Domain\Repository\SiteRepositoryInterface;
use App\Infrastructure\Client;
use App\Infrastructure\Repository\FileJson\Site\FileJsonDataObject;
use App\Infrastructure\Repository\FileJson\Site\ParserFromJsonToObject;
use App\Infrastructure\Repository\FileJson\Site\SiteRepositoryFileJson;
use App\UseCase\SiteCheckerUseCase;
use App\UserInterface\Console\SiteCheckerCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

$containerBuilder = new ContainerBuilder();

$containerBuilder->register(ParserFromJsonToObject::class, ParserFromJsonToObject::class);
$containerBuilder->register(FileJsonDataObject::class, FileJsonDataObject::class)
    ->setArguments([
        STORAGE_PATH,
        STORAGE_PATH . 'sites_config.json',
        new Reference(ParserFromJsonToObject::class)
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
    ]);
$containerBuilder->register(SiteCheckerCommand::class, SiteCheckerCommand::class)
    ->setArguments([
        new Reference(SiteCheckerUseCase::class)
    ]);


return $containerBuilder;
