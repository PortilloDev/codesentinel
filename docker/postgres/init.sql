-- Habilita la extensión pgvector (viene incluida en la imagen pgvector/pgvector:pg16)
CREATE EXTENSION IF NOT EXISTS vector;

-- Extensión para UUIDs nativos
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Índice GIN para búsquedas de texto completo (útil para buscar en diffs)
-- Las tablas las creará Doctrine Migrations, pero la extensión debe estar antes.
