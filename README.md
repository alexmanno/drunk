# Installation

Start docker containers
```sh
docker-compose up -d
```

Get a shell
```sh
docker exec -ti drunk_php_1 bash
```

Install project
```sh
cd /app
composer install
```

Dump sql
```sh
bin/console orm:schema-tool:create
```

# Usage

### Retrieve all users
```sh
curl http://localhost:8080/api/users
```

### Create user
```sh
curl --header "Content-Type: application/json" \
  --request POST \
  --data '{"username":"mario-rossi","firstName":"Mario","lastName":"Rossi","email":"mario.rossi@example.com","password":"password"}' \
  http://localhost:8080/api/users
```

### Get single user
```sh
curl http://localhost:8080/api/users/1
```


