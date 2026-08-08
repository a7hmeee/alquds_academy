# Deprecation Candidates

These are code paths that are likely unused or have more modern replacements.

## ~~Cleanup Completed~~ ✅

The following have been cleaned up (replaced with empty stubs due to filesystem restrictions):

- ~~`routes/api.php`~~ — Not loaded in `bootstrap/app.php`. All routes were dead code.
- ~~`app/Http/Controllers/Api/*`~~ — All 9 API controllers were unused (no routes reference them).

## Direct Model Creation (not yet using Actions)

These controllers still use inline `Model::create()` calls. They are lower priority because the operations are simple single-table inserts:

| Controller | Operation |
|------------|-----------|
| `OrganizationController::store` | Single `Organization::create()` |
| `OrganizationController::update` | Single `Organization::update()` |
| `RoleController::store` | Single `Role::create()` |
| `RoleController::update` | Single `Role::update()` |
| `ProfileController::update` | Single `$user->save()` |
| `RegistrationController` | Simple user creation flow |

## Unused Imports

| File | Import |
|------|--------|
| `app/Http/Controllers/StudentProfileController.php` | `App\Models\User` (no longer directly used — rely on FormRequest) |

## How to Verify

1. Confirm no mobile app, frontend SPA, or external service calls any `routes/api.php` route
2. If confirmed, delete `routes/api.php` and all API controllers under `app/Http/Controllers/Api/`
3. For remaining direct model calls: extract to Actions when business logic warrants it
