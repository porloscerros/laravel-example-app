
### Run following commands on terminal:

1. Clone the repository and go into project directory.
```bash
git clone git@github.com:porloscerros/laravel-example-app.git

cd laravel-example-app
```

2. Install composer dependencies.
```bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php83-composer:latest \
composer install --ignore-platform-reqs
```


3. Set the `.env` from `env.example`.
```bash
cp .env.example .env
```

4. Start docker.
```bash
./vendor/bin/sail up
```

5. Generate `.env` `APP_KEY` variable.
```bash
./vendor/bin/sail artisan key:generate
```

6. Run migrations.
```bash
./vendor/bin/sail artisan migrate
```

7. Install node dependencies and build.
```bash
./vendor/bin/sail yarn

./vendor/bin/sail yarn build
```

### ISSUES
- Laravel sail mysql
> [ERROR] [MY-010259] [Server] Another process with pid 62 is using unix socket file.
```bash
docker-compose down --volumes
sail up --build
```
