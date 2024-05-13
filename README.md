# Site status

Проверяет состояние сайтов расположенному по URL и анализируя полученный HTTP код. 
В качестве хранилища используются json файлы.
Можно использовать несколько сайтов для проверки (_смотри раздел ниже sites_config.json_). 

Точка входа приложения `php bin/console`. Используется пакет `symfony/console`, поэтому для проверки статуса сайта(сайтов)
необходимо вызывать команда `app:check` (в дальнейшем можно будет сделать статистику отдельной командой).
Полная команда в консоли `php bin/console app:check`.

**Примечание**: все symfony команды должны размещаться в директории: `app/UserInterface/Console` и файл класса
должен иметь префикс `*Command.php`. (_смотри класс CommandLoader_)

## Развертывание

1. Клонировать проект


2. Создать `.env` файл
    ```bash
    cp docker/.env.example docker/.env && cp app/.env.example app/.env 
    ```
3. Отредактировать `docker/.env` файл. 

   Изменить переменные `APP_NAME=Status Site` и `COMPOSE_PROJECT_NAME=status_site`.  


4. Отредактировать `app/.env` файл. Установи переменную `TRANSPORT_SLACK_DSN`.

   **Примечание**: Создать приложение в (Slack API)[https://api.slack.com/apps]. Нажимаешь кнопку **Create new App** → 
   выбираешь **From scratch** → вводишь имя бота → выбираешь workspace → затем идешь в **Incoming Webhooks** → 
   активируешь **Activate Incoming Webhooks** → добавляешь webhook в соответствующий канал → получишь строку в 
   виде https://hooks.slack.com/services/XXXXX/YYYYY/ZZZZZZZ это и есть `TRANSPORT_SLACK_DSN`. 
   Также в этом файле указаны переменные `SLACK_TMP_MSG_SITE_DOWN` и `SLACK_TMP_MSG_SITE_UP`, которые являются шаблонами для сообщений.


5. В данном проекте в качестве хранилища данных используется json файлы `app/storage/. 

   Перед первым запуском необходимо, создать `sites_config.json`
    ```bash
    cp app/storage/sites_config.json.example app/storage/sites_config.json 
    ```
   _Описание структуры sites_config.json смотри ниже._  
   

6. Запуск контейнера для разработки выполняется командой `make start_development`.


7. В Makefile доступные другие команды. `make help`


## Принцип работы json-хранилища.
Запуская приложение (`php bin/console`) сразу же считывается файл app/storage/sites_config.json из которого
для каждого сайта считывается параметр `system_storage_file` и проверяется если нет файл по пути app/storage/$system_storage_file,
то создается со значениями указанными в app/storage/sites_config.json. 
В работе если меняется состояние(доступен/недоступен) сайта, его состояние записывается в 
app/storage/$system_storage_file и время когда изменилось состояние.

Есть интерфейс SiteRepositoryInterface в котором есть пока только два метода:
1. `getAll()` - считывает system_storage_file для сайтов и возвращается массив объектов Site.
2. `update(Site $site)` - сохраняет состояние сайта в файле указанном в system_storage_file.


## Структура файла sites_config.json
   Данный файл отвечает за сайтами которые необходимо отслеживать. Если нужно добавить сайт, то добавь структуру формат json представленную ниже. 
   Также на основе этого файла идет привязка к другому файлу в котором хранится текущее состояние.
   ```hjson
   [
        {
            "name": "staging", - имя сайта, должно быть одним словом, используется как в коде так и отображается в канале
            "url": "https://staging.example.com/api/health", - url по которому будет проходить проверка
            "status": "up", - допустимые занчения up/down. Зависит в каком состояние находится в момент добавления сайт
            "system_storage_file": "staging_storage.json", - название файла в котором будет хранится текущее состояние сайта.
            "time": "", - пустая строка
            "success_code": "204" - HTTP код при котором сайт является в рабочем состоянии.
        }
    ]
```

## FAQ.

  1. Как добавить сайт в приложение? Для добавления сайта в приложение нужно добавить в `app/storage/sites_config.json`.
  2. Как удалить сайт из приложения? Для удаления сайта из приложения нужно удалить из `app/storage/sites_config.json`.
