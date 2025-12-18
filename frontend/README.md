# Frontend - Student Management

Simple frontend to manage students using the backend API.

Files:
- `index.html` — list students, delete, link to add/edit.
- `student-form.html` — add or edit a student; loads classes from `backend/api/classes.php`.

How to use:
1. Ensure backend is configured and DB imported.
2. Open in browser: `http://localhost/web-don-gian/frontend/index.html`.
3. Use the UI to add, edit, delete students. The frontend calls the backend API at `/web-don-gian/backend/api/*`.
