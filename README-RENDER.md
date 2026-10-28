# Render deploy kit (Soko Intel)

Nakili faili hizi kwenye ROOT ya mradi wako wa Laravel (zinaandika juu ya zile zilizopo):

- Dockerfile
- .dockerignore
- docker/start.sh
- app/Providers/AppServiceProvider.php
- app/Http/Controllers/HomeController.php
- database/seeders/DatabaseSeeder.php

Kisha:  git add . && git commit -m "Render deploy" && git push

Environment variables za Render:
APP_NAME=Soko Intel
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...            (php artisan key:generate --show)
APP_URL=https://JINA-LAKO.onrender.com
DB_CONNECTION=pgsql
DB_URL=<Internal Database URL ya Render Postgres>
SESSION_SECURE_COOKIE=true
LOG_CHANNEL=stderr
ADMIN_EMAIL=barua-yako@example.com
ADMIN_PASSWORD=<password ndefu na ya kipekee>
SEED_DEMO=true               (hiari: data ya mfano tu)
