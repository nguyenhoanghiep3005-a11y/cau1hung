# Backend - Student Management (DB & Connection)

## Files added
- `backend/database/create_database.sql` — SQL to create `student_management` DB, two tables `classes` and `students`, and sample data.
- `backend/config/config.php` — default config (prefer `config.local.php` for secrets).
- `backend/config/config.local.example.php` — copy to `config.local.php` and set your local credentials.
- `backend/config/db.php` — PDO helper; use `getPDO()` to obtain the connection.
- `backend/test_db.php` — quick test page to verify connection and list tables.

## Quick instructions ✅
1. Import DB
   - Open phpMyAdmin (e.g., http://localhost/phpmyadmin).
   - Click "Import" → choose `backend/database/create_database.sql` → Go.

2. Configure credentials
   - Copy `backend/config/config.local.example.php` -> `backend/config/config.local.php` and update host/user/pass if needed.
   - `config.local.php` is ignored by `.gitignore` by default.

3. Test connection
   - Visit: `http://localhost/web-don-gian/backend/test_db.php`
   - You should see the DB name and the tables list.

4. Push to GitHub (if desired)
   - git add . && git commit -m "Add DB and PDO connection"
   - git remote add origin <your-repo-url>
   - git push -u origin main

If you want, I can now add REST endpoints for CRUD (backend) and a simple frontend to manage students. What would you like me to do next?

---

## API: Students
Base URL (local): `http://localhost/web-don-gian/backend/api/students.php`

Endpoints:
- **GET**    `/students.php`            — list all students
- **GET**    `/students.php?id=1`      — get student by id
- **POST**   `/students.php`            — create (JSON body: `name`, `email` (optional), `class_id` (optional))
- **PUT**    `/students.php?id=1`       — update (JSON body: any of `name`, `email`, `class_id`)
- **DELETE** `/students.php?id=1`       — delete

Responses are JSON. See `backend/api` for implementation and example usage.

---

## CI/CD (FTP deploy)
I've added a GitHub Actions workflow at `.github/workflows/deploy-ftp.yml` which deploys the `backend` folder to your FTP server on push to `main` using `SamKirkland/FTP-Deploy-Action`.

Before this workflow can run, add the following **Repository secrets** (Settings → Secrets and variables → Actions) in your GitHub repository:
- `FTP_HOST` — e.g. `ftpupload.net`
- `FTP_USERNAME` — e.g. `if0_40099280`
- `FTP_PASSWORD` — your FTP password
- `FTP_PORT` — optional (defaults to `21`)
- `FTP_TARGET` — remote directory on the server (e.g. `/public_html`)

Important: do **not** commit credentials into the repo. The workflow reads the secrets and will not run until they are configured.
