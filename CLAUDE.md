# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Quotify** (formerly "Product Quotation for WooCommerce") is a WordPress plugin that replaces WooCommerce's "Add to cart" functionality with a quotation request system. Customers can add products to a quotation cart and submit a form to request price quotes.

**Key Features:**
- Quotation cart system (products stored in session/database)
- Custom quotation forms with customer information
- Admin dashboard for managing quotations (React-based SPA)
- Email notifications for both admin and customer
- Support for variable products
- Customizable "Add to Quotation" buttons

**Tech Stack:**
- Backend: PHP with WordPress/WooCommerce
- Frontend Admin: React with Redux Toolkit
- Build: @wordpress/scripts (webpack-based)
- Development: Docker (nginx, PHP 8.0+, MySQL 8.0, MailHog for email testing)

## Development Commands

### Build Commands
```bash
# From wordpress/wp-content/plugins/quotify/

# Build all assets for production
npm run build

# Development builds with file watching
npm run start                    # Main build (SPA + Frontend + Admin)
npm run start:spa               # Admin dashboard only
npm run start:frontend          # Frontend components only
npm run start:admin             # Admin utilities only

# Fresh builds (clean build directory first)
npm run start:fresh             # Clean all builds
npm run start:fresh:spa        # Clean SPA build only
npm run start:fresh:frontend    # Clean frontend builds only
npm run start:fresh:admin      # Clean admin builds only

# Production builds
npm run build                   # Main production build
npm run build:spa              # SPA production build
npm run build:frontend         # Frontend production builds
npm run build:admin            # Admin production build
npm run build:fresh           # Clean all production builds
```

### Code Quality
```bash
# Linting
npm run lint:js       # JavaScript linting
npm run lint:css      # CSS/SCSS linting
npm run lint:md:docs  # Markdown documentation linting
npm run lint:pkg-json # Package.json linting

# PHP Code Quality (requires Composer dependencies)
composer lint              # Run PHP CodeSniffer
composer lint:staged       # Run CodeSniffer with cache for staged files
composer phpcs             # Run CodeSniffer directly
composer phpcbf            # Fix CodeSniffer auto-fixable issues

# Formatting
npm run format              # Format code with Prettier
```

### Testing
```bash
npm run test:unit     # Run unit tests
npm run test:e2e      # Run end-to-end tests
```

### Other Commands
```bash
npm run plugin-zip   # Create plugin distribution ZIP
npm run check-engines  # Check Node.js engines compatibility
npm run check-licenses # Check license compliance
npm run packages-update # Update WordPress packages
```

**Docker Environment:**
```bash
# From project root
docker-compose up -d    # Start all services
docker-compose down     # Stop all services
```

Services available after start:
- WordPress: http://localhost
- phpMyAdmin: http://localhost:8080
- MailHog (email testing): http://localhost:8025

## Architecture

### Plugin Structure

```
quotify/
├── product-quotation-for-woocommerce.php  # Main plugin file
├── app/                                     # Core application code (PHP)
│   ├── autoload.php                         # Autoloader (maps namespaces to directories)
│   ├── quotify.php                          # Main bootstrap class (Singleton)
│   ├── ajax.php                             # AJAX handler manager
│   ├── assets.php                           # Asset loading manager
│   ├── admin.php                            # Admin interface manager
│   ├── library/                             # Utility classes
│   │   ├── settings.php                     # Settings management
│   │   ├── mail.php                         # Email handling
│   │   ├── session.php                      # Session management
│   │   ├── menu.php                         # Admin menu
│   │   └── helper.php                       # Helper functions
│   ├── ajax/                                # AJAX handlers
│   │   ├── quotations.php                  # Quotation CRUD
│   │   ├── cart.php                         # Cart operations
│   │   ├── settings.php                     # Settings handlers
│   │   ├── form.php                         # Form submission
│   │   └── addons.php                       # Addon operations
│   ├── forms/                               # Form handling classes
│   │   └── controls.php                     # Form controls generator
│   ├── internals/                           # Core business logic
│   │   ├── cart.php                         # Cart functionality
│   │   ├── buttons.php                      # "Add to Quote" buttons
│   │   ├── quotations.php                   # Quotation management
│   │   ├── frontend.php                     # Frontend functionality
│   │   ├── product.php                      # Product integration
│   │   ├── addons.php                       # Addon system
│   │   └── hooks.php                        # WordPress hooks
│   ├── abstracts/                           # Abstract base classes
│   ├── interfaces/                          # Interface definitions
│   └── shortcodes/                          # Shortcode implementations
├── assets/                                  # Frontend assets
│   ├── build/                               # Compiled JavaScript/CSS (output of webpack)
│   ├── css/                                 # Source stylesheets
│   └── images/                              # Images
├── src/                                      # React frontend source
│   ├── dashboard.js                            # Admin dashboard entry point
│   ├── button.js                             # "Add to Quotation" button
│   ├── cart.js                               # Frontend cart
│   ├── form.js                               # Quotation form
│   ├── components/                           # React components
│   ├── containers/                           # Connected components (pages)
│   ├── redux/                                # Redux store
│   │   ├── actions/                          # Redux actions
│   │   ├── reducers/                         # Redux reducers
│   │   └── types/                            # TypeScript-like JSDoc types
│   └── utils/                                # Utility functions
└── addons/                                   # Plugin integrations
    └── contact-form-7/                       # Contact Form 7 integration
```

### Key Architectural Patterns

**1. Autoloading System**
The plugin uses a custom autoloader that maps namespaces to directories:
- Namespace `Quotify` maps to `app/` directory
- Class names are converted to lowercase filenames with hyphens
- Example: `Quotify\Ajax\Quotations` → `app/ajax/quotations.php`

**2. Singleton Pattern**
The main plugin class uses Singleton pattern:
```php
// In quotify.php
final class Quotify {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance || !self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

**3. AJAX Handler Pattern**
All AJAX handlers follow a consistent pattern:
- Actions registered in `app/ajax/*.php` files
- Nonce verification using `check_ajax_referer('pqfw_nonce', 'nonce')`
- Capability checks using `current_user_can()`
- Responses via `wp_send_json_success()` and `wp_send_json_error()`

Example from `app/ajax/quotations.php`:
```php
public function __construct() {
    add_action('wp_ajax_quotify/ajax/quotations/load', [$this, 'load']);
    add_action('wp_ajax_quotify/ajax/quotations/delete', [$this, 'delete_item']);
}

public function load() {
    check_ajax_referer('pqfw_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Permission denied', 'quotify'));
    }
    // Logic here
    wp_send_json_success($data);
}
```

**4. Module Initialization**
The main `Quotify` class initializes all modules in `loader()` method. Each module follows a static `init()` pattern returning singleton instance.

**5. Redux State Management (Admin Dashboard)**
- Store: `src/redux/store.js`
- Slices: `adminmenu`, `settings`, `quotations`, `quotation`, `addons`
- Actions in `src/redux/actions/*.js`
- Reducers in `src/redux/reducers/*.js`
- Types in `src/redux/types/*.js`

**6. Webpack Build Configuration**
The project uses multiple webpack configurations for different parts of the application:

**Main Configuration** (`webpack.config.js`):
- `backend` → `backend.{version}.js` (Admin dashboard SPA)
- `button` → `button.{version}.js` (Add to Quote button)
- `cart` → `cart.{version}.js` (Frontend cart)
- `form` → `form.{version}.js` (Quotation form)
- `admin` → `admin.{version}.js` (Admin utilities)

Note: The file `src/common/quotify-admin-common.js` is referenced in webpack config but appears to be deleted. The actual admin utilities are likely included in the React components.

**Separate Configurations**:
- `webpack/webpack.spa.config.js` - Admin dashboard only
- `webpack/webpack.frontend.config.js` - Frontend components only
- `webpack/webpack.admin.config.js` - Admin utilities only

Path aliases configured:
- `@src` → `src/`
- `@Components` → `src/components/`
- `@Containers` → `src/containers/`
- `@Global` → `src/global/`
- `@Utils` → `src/utils/`
- `@Assets` → `src/assets/`
- `@Redux` → `src/redux/`
- `@Images` → `src/images/`
- `@Scss` → `src/scss/`

## Important Files

- `product-quotation-for-woocommerce.php` - Plugin bootstrap, defines constants
- `app/autoload.php` - Autoloader configuration
- `app/quotify.php` - Main class, initializes all modules
- `app/ajax.php` - AJAX handler manager
- `webpack.config.js` - Build configuration with entry points
- `src/dashboard.js` - React admin dashboard entry point
- `src/redux/store.js` - Redux store configuration

## Data Storage

### Custom Post Types
The plugin uses WordPress custom post types:
- `quotify_quotation` - Stores quotation requests
- Accessed via `Quotify\Internals\Quotations` class
- Custom meta fields for quotation data

### Session Management
- Quote cart stored in WordPress session (`pqfw_products_quotations_list`)
- Uses WordPress options table for persistence
- Session-based with database fallback for logged-in users

### Database Migration System
- Migration system in `app/database/migration.php`
- Handles database schema updates
- Version-controlled migrations

## Adding New Features

**New AJAX Handler:**
1. Create class in `app/ajax/` following existing pattern
2. Register action in class `__construct()` with prefix `quotify/ajax/`
3. Add nonce verification: `check_ajax_referer('pqfw_nonce', 'nonce')`
4. Add capability check: `current_user_can()`
5. Instantiate handler in `app/ajax.php`

**New React Container/Page:**
1. Create in `src/containers/FeatureName/index.js`
2. Add route case in `src/components/BackendDashboard/index.js`
3. Create Redux actions/reducers in `src/redux/`

**New Form Integration:**
1. Implement form class
2. Register via addon system in `app/internals/addons.php`

## Conventions

- **Namespace**: `Quotify\{Namespace}` matches directory structure
- **Text Domain**: Use `'quotify'` for i18n
- **Capability Checks**: Always check permissions in AJAX handlers
- **Nonce Verification**: Use `pqfw_nonce` for all AJAX requests
- **ABSPATH Check**: All PHP files must have `defined('ABSPATH') || exit;`
- **File Naming**: Use lowercase with hyphens for PHP files
- **Component Organization**: Separate presentational components (components/) from connected containers (containers/)

## PHP Development

The project uses PHP CodeSniffer with WordPress coding standards:

**Setup**:
```bash
composer install          # Install dependencies including code quality tools
composer install-codestandards  # Install PHPCS standards
```

**Quality Checks**:
```bash
composer lint              # Run PHP CodeSniffer
composer lint:staged       # Run on staged files (optimized for Git)
composer phpcs             # Direct CodeSniffer execution
composer phpcbf            # Auto-fix issues
```

**Standards Configuration**:
- Uses WordPress-Extra standard with custom rules in `phpcs.xml`
- PHP 7.4+ compatibility enforced
- Custom escaping rules for WordPress functions
- 200-character line limit enforced
- Excludes build directories and vendor files

## Testing

**JavaScript Testing**:
- Unit tests: `npm run test:unit`
- End-to-end tests: `npm run test:e2e`
- Uses @wordpress/scripts test runner

**PHP Testing**:
- PHPUnit configured via composer.json
- WordPress testing libraries included in dependencies
- Tests would run from the WordPress test environment

## Testing Email

Use MailHog at http://localhost:8025 when developing locally. The `Quotify\Library\Mail` class handles email sending.
