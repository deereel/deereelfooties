# DeeReel Footies - Agent Guide

## Build/Test Commands
- **Database Test**: `php test-db.php` (browser) - Test main DB connection
- **Wishlist Test**: `php test-wishlist.php` (browser) - Test wishlist functionality  
- **Auth Test**: `php auth/test_db.php` (browser) - Test JSON database connection
- **Tailwind Build**: `npx tailwindcss -i input.css -o output.css --watch` (if needed)

## Architecture
- **Database**: Two MySQL DBs - `deereelfooties` (main) via `config/database.php`, `drf_database` (auth) via `auth/db.php`
- **Auth System**: Session-based in `auth/` with Google OAuth, uses helper functions (fetchData, insertData, updateData, deleteData)
- **API Layer**: REST endpoints in `api/` for cart, orders, users, products, wishlist
- **Components**: Reusable PHP includes in `components/` (header, navbar, modals, product cards)
- **Frontend**: Tailwind CSS, Swiper.js, vanilla JavaScript with DOM manipulation
- **Special**: Python-based foot measurement app in `foot_measurement_app/`

## Code Style
- **PHP**: Snake_case for DB fields, camelCase for JS variables, kebab-case for HTML IDs
- **Includes**: Use `require_once $_SERVER['DOCUMENT_ROOT'] . '/path/file.php'` 
- **Sessions**: Always check `if (session_status() === PHP_SESSION_NONE) session_start();`
- **Database**: PDO with prepared statements, use helper functions from `auth/db.php`
- **Security**: Always escape output with `htmlspecialchars()`, use `<?= ?>` for simple echoes
- **Error Handling**: Try-catch blocks for PDO, `error_log()` for logging, JSON responses with `success` boolean
- **Structure**: Logic at top, HTML below; component-based organization
