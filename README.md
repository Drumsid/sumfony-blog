# Avido Training Soloduhin

## Setup

### Required

* docker
* docker compose
* php >= 8.0.2

### Steps

**Быстрая установка**


```sh
cp -n app/.env.example app/.env || true

$ make build

$ make install
```

* открываем проект по ссылке http://localhost:8080/

**Если нужно, чтоб проект открывался по адресу http://avido.test/**

* перед установкой добавляем в host файл запись `127.0.0.1 avido.test`
* меняем порт nginx в docker-compose на ports: - "80:80"
* на убунту потребуется так же остановить службы которые работают на 80 порту, например,  apache? вводим в терминал команду `sudo service apache2 stop`
* * открываем проект по ссылке http://avido.test/
