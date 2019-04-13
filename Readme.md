##Запуск проекта
Для запуска требуется docker-compose
```
docker-compose up -d
```
##Redis
Редис хранит в себе связи между пользователями (подписки), а так же индивидуальные ленты зависящие от подписок. 
В редис загружается дамп с данными, на которых производилось тестирование.

Зайти в консоль редиса можно с помощью последовательности команд:
```
docker-compose exec redis bash
redis-cli
```
#####Где-что лежит
**followers#** *userID* - set - список пользователей подписанных на *userID*.

**subscriptions#** *userID* - set - список пользователей на которых подписан *userID*. В проекте не используется, но оставлен на случай, если мы не захотим хранить ленты для неактивных пользователей.

**home_timeline#** *userID* - sorted set - список постов пользователей на которых подписан *userID*. Сортировка пидет по timestamp'у создания поста.

**user_timeline#** *userID* - sorted set - список постов созданных *userID*. Сортировка пидет по timestamp'у создания поста.

##MySQL
Дамп mysql находится в *docker/mysql/sql-scripts*. Данные для подключения к mysql находятся в файле *./.env*

Зайти в консоль можно с помощью последовательности команд:
```
docker-compose exec mysql bash
mysql -udummy -pdummy dummy
```
#####Где-что лежит
**access_tokens** - список токенов пользователей для их авторизации в системе. Сделал для имитации авторизационного сервиса, потому что не хотелось хардкодить токены.

**users** - список пользователей. Сгенерировал 2000 записей, чтобы было с чем работать.

**posts** - список постов пользователей.
##Примеры запросов к api
Создание поста от лица юзера с id 1
```
curl -X POST \
  http://127.0.0.1/v1/createPost \
  -H 'Content-Type: application/json' \
  -H 'x-access-token: $2y$10$8kU8uy.EFLcM.HRG44jKpuPY0xgknbEs1P6mr7JYOIl6olEFt9Le.' \
  -d '{"text":"Message from user#1"}'
```

Получение постов юзера с id 1
```
curl -X GET 'http://127.0.0.1/v1/getUserTimeline?user_id=1'
```

Удаление поста по post_id от лица пользователя с id 1
```
curl -X POST \
  'http://127.0.0.1/v1/deletePost?post_id=5' \
  -H 'x-access-token: $2y$10$8kU8uy.EFLcM.HRG44jKpuPY0xgknbEs1P6mr7JYOIl6olEFt9Le.'
```

Редактирование поста от лица пользователя с id 1
```
curl -X POST \
  http://127.0.0.1/v1/updatePost \
  -H 'Content-Type: application/json' \
  -H 'x-access-token: $2y$10$8kU8uy.EFLcM.HRG44jKpuPY0xgknbEs1P6mr7JYOIl6olEFt9Le.' \
  -d '{"id":2, "text":"i change you"}'
```

Получение постов интересных пользователей от лица пользователя с id 2 (подписан на пользователя с id 1)
```
curl -X GET \
  'http://127.0.0.1/v1/getHomeTimeline?limit=3&offset=3' \
  -H 'x-access-token: $2y$10$gnFd538LIfPklT8Rg09Nkuu8zoZX/pXmj8Nv/SnD.Q6HRar3m6AaS'
```

Получение поста по id
```
curl -X GET 'http://127.0.0.1/v1/getPost?post_id=9'
```

Создание поста от лица пользователя с id 2
```
curl -X POST \
  http://127.0.0.1/v1/createPost \
  -H 'Content-Type: application/json' \
  -H 'x-access-token: $2y$10$gnFd538LIfPklT8Rg09Nkuu8zoZX/pXmj8Nv/SnD.Q6HRar3m6AaS' \
  -d '{"text":"Сообщение от второго пользователя"}'
```

Получение постов интересных пользователей от лица пользователя с id 1 (подписан на пользователя с id 2)
```
curl -X GET \
  http://127.0.0.1/v1/getHomeTimeline \
  -H 'x-access-token: $2y$10$8kU8uy.EFLcM.HRG44jKpuPY0xgknbEs1P6mr7JYOIl6olEFt9Le.'
```

