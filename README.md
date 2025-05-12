# Translation Management Service (TMS)

A high‑performance, API‑driven service for managing translations, locales, and tags, with a Vue 3 + Vite frontend.

---

## 📦 Features

* **API** (Laravel 10): CRUD for Locales, Tags, and Translations
* **Search & JSON Export**: Streamed export with Redis caching
* **Authentication**: Laravel Sanctum token-based
* **Performance**: Handles 100k+ records (<200 ms queries, <500 ms exports)
* **Dockerized**: PHP‑FPM, Nginx, MySQL, Redis, Node/Vite dev server
* **Swagger/OpenAPI**: Auto‑generated docs via **l5‑swagger**
* **Testing**: PHPUnit unit & feature tests (>96 % coverage)

---

## 🚀 Quick Start

1. **Clone & enter** the repo:

   ```bash
   git clone https://github.com/itss-maaann/Translation-Management-Service.git
   cd into repo
   ```

2. **Create** a `.env` from the example:

   ```bash
   cp .env.example .env
   ```

3. **Configure** environment variables in `.env` (DB, Redis, etc.). But keep in mind that they are linked to configurations in docker-compose.yml file

4. **Build & start** services (dev mode):

   ```bash
   docker-compose up -d --build
   ```

4. **setup.sh** Entry point for pre running required commands like migrations, seeders and swagger etc:

6. **Access**:

   * **Frontend (Vite)**: [http://localhost:8085/](http://localhost:8085/)
   * **API**:             [http://localhost:8085/api](http://localhost:8085/api)
   * **Swagger UI**:    [http://localhost:8085/api/documentation](http://localhost:8085/api/documentation)
   * **phpMyAdmin**:    [http://localhost:8081/](http://localhost:8081/)

7. **Stop & remove**:

   ```bash
   docker-compose down
   ```

---


* **Login Account**:
   ```Admin Credendtials created through seeder in automation of docker compose
   email: itssmaaann@gmail.com
   password: Majid123
   ```

---

## 🔧 Architecture & Design

* **Laravel** backend follows **PSR‑12** and **SOLID** principles.
* **Layered** structure: Controllers → Services → Repositories → Eloquent Models
* **Eloquent Observers** for cache invalidation
* **Redis** for caching JSON export (
  `translations:export`
  )
* **Pinia** + **Vue Router** + **Axios** on the frontend (live reload via Vite)

---

## 🛠️ Backend Commands

* **Migrate & seed**:

  ```bash
  docker-compose exec app php artisan migrate --seed
  ```
* **Seed 100k+ translations**:

  ```bash
  docker-compose exec app php artisan tms:seed-translations 100000
  ```
* **Generate Swagger JSON**:

  ```bash
  docker-compose exec app php artisan l5-swagger:generate
  ```
* **Run tests**:

  ```bash
  docker-compose exec app php artisan test --coverage
  ```

---

## 🔑 Environment Variables

```dotenv
APP_NAME=TMS
APP_ENV=local
APP_KEY=base64:…
APP_DEBUG=true
APP_URL=http://localhost:8085

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=tms
DB_USERNAME=tms_user
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

TRANSLATIONS_EXPORT_CACHE_KEY=translations:export
TRANSLATIONS_EXPORT_TTL=3600
```

---

## 📚 Documentation

* **Swagger UI**: Browse API endpoints and schemas at `/api/documentation`.

---

## 👤 Frontend

* **Location**: `src/tms-frontend`
* **Login**: `/login` (acquires Sanctum token)
* **Dashboard**: Protected routes for Locales, Tags, Translations
* **Export Viewer**: Visualize JSON export under **Translations → Export**

Commands:

```bash
cd src/tms-frontend
npm install
npm run dev    # Vite dev server
npm run build  # Production bundle
```

---

## 🎯 Design Choices

* **Streaming** large exports to keep memory footprint low
* **Repository pattern** for testable, SOLID data access
* **Observers** to centralize cache invalidation
* **Pinia stores** for state management in Vue 3
* **L5‑Swagger** for up‑to‑date API docs

---

## 📝 License

MIT © Majid Shahzeb
