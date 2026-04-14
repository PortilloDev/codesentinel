# CodeSentinel

Agente de revisión de Pull Requests con IA — Symfony 8.0 + PHP 8.4 + PostgreSQL + pgvector + Redis

## Requisitos

- Docker + Docker Compose v2
- Make
- ngrok (para recibir webhooks de GitHub en local)

## Arranque rápido

```bash
# 1. Clona y entra al proyecto
git clone <repo> codesentinel && cd codesentinel

# 2. Configura las variables de entorno
cp .env .env.local
# Edita .env.local con tus valores reales:
#   - ANTHROPIC_API_KEY
#   - GITHUB_APP_ID, GITHUB_WEBHOOK_SECRET
#   - POSTGRES_PASSWORD, REDIS_PASSWORD

# 3. Coloca la clave privada de tu GitHub App
cp ~/Downloads/your-app.2024-01-01.private-key.pem docker/secrets/github_app.pem

# 4. Arranca el entorno
make up

# 5. Instala dependencias PHP
make install

# 6. Ejecuta las migraciones (crea tablas en PostgreSQL + activa pgvector)
make migrate

# 7. (Opcional) Carga datos de prueba
make fixtures
```

## Servicios disponibles

| Servicio   | URL                        | Descripción                    |
|------------|----------------------------|-------------------------------|
| App        | http://localhost:8080      | Symfony (via Nginx)           |
| Mailpit    | http://localhost:8025      | Captura de emails en dev      |
| PostgreSQL | localhost:5432             | Base de datos                 |
| Redis      | localhost:6379             | Colas Messenger + caché       |

## Configurar la GitHub App

1. Ve a https://github.com/settings/apps/new
2. Nombre: `codesentinel-dev-<tu-usuario>`
3. Homepage URL: `http://localhost:8080`
4. Webhook URL: la URL de ngrok (ej: `https://xxxx.ngrok.io/webhook/github`)
5. Webhook secret: el valor de `GITHUB_WEBHOOK_SECRET` en tu `.env.local`
6. Permisos:
   - Pull requests: Read & Write
   - Repository contents: Read
7. Events: Pull request
8. Genera y descarga la clave privada → colócala en `docker/secrets/github_app.pem`

## Exponer el webhook local

```bash
make tunnel
# Copia la URL https://xxxx.ngrok.io y actualiza el webhook en tu GitHub App
```

## Comandos útiles

```bash
make shell          # Acceso al contenedor PHP
make worker-logs    # Ver logs del worker Messenger en tiempo real
make db-shell       # Acceso a PostgreSQL
make redis-shell    # Acceso a Redis
make migrate        # Ejecutar migraciones pendientes
make test           # Tests unitarios y de integración
make cs-fix         # Formatear código con PHP CS Fixer
make analyse        # Análisis estático con PHPStan
```

## Arquitectura

```
webhook de GitHub → Nginx → Symfony Controller
                                 ↓
                    Valida firma HMAC del webhook
                                 ↓
                    Despacha AnalyzePullRequestMessage
                                 ↓
                         Redis (cola async)
                                 ↓
                         Messenger Worker
                                 ↓
                    AnalyzePullRequestHandler
                     ├── GitHub API → descarga diff
                     ├── pgvector → busca bugs similares
                     ├── Claude API → analiza el código
                     └── GitHub API → publica comentarios
```
