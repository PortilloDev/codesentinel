.PHONY: up down build shell worker-logs db-shell redis-shell \
        install migrate fixtures test cs-fix analyse

# ── Entorno ────────────────────────────────────────────────────────────────
up:
	docker compose up -d --build
	@echo ""
	@echo "CodeSentinel arrancado:"
	@echo "  App:     http://localhost:$${APP_PORT:-8080}"
	@echo "  Mailpit: http://localhost:8025"

down:
	docker compose down

build:
	docker compose build --no-cache

# ── Acceso a contenedores ──────────────────────────────────────────────────
shell:
	docker compose exec php sh

worker-logs:
	docker compose logs -f worker

nginx-logs:
	docker compose logs -f nginx

# ── Base de datos ──────────────────────────────────────────────────────────
db-shell:
	docker compose exec postgres psql -U $${POSTGRES_USER} -d $${POSTGRES_DB}

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrate-diff:
	docker compose exec php php bin/console doctrine:migrations:diff

fixtures:
	docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

# ── Redis ──────────────────────────────────────────────────────────────────
redis-shell:
	docker compose exec redis redis-cli -a $${REDIS_PASSWORD}

redis-flush:
	docker compose exec redis redis-cli -a $${REDIS_PASSWORD} FLUSHALL

# ── Symfony ───────────────────────────────────────────────────────────────
install:
	docker compose exec php composer install

cache-clear:
	docker compose exec php php bin/console cache:clear

routes:
	docker compose exec php php bin/console debug:router

# ── Calidad de código ──────────────────────────────────────────────────────
test:
	docker compose exec php php bin/phpunit

cs-fix:
	docker compose exec php vendor/bin/php-cs-fixer fix

analyse:
	docker compose exec php vendor/bin/phpstan analyse

# ── Webhook local (via ngrok) ─────────────────────────────────────────────
# Instala ngrok (https://ngrok.com) y configura NGROK_AUTHTOKEN en .env.local
tunnel:
	ngrok http $${APP_PORT:-8080}
