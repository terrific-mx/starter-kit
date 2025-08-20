## IMPORTANT

- Always use the `artisan make:*` command when creating models, migrations, factories, controllers, and other Laravel files. This ensures proper boilerplate, registration, and maintainability.
- Use the imported class directly (not the fully qualified namespace) when referencing classes in your code.
- Always use models unguarded (`protected $guarded = [];`), so all attributes are mass assignable.
