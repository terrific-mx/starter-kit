## IMPORTANT

- Always use the `artisan make:*` command when creating models, migrations, factories, controllers, and other Laravel files. This ensures proper boilerplate, registration, and maintainability.
- Use the imported class directly (not the fully qualified namespace) when referencing classes in your code.
- Always use models unguarded (`protected $guarded = [];`), so all attributes are mass assignable.
- Use the `protected casts()` method when casting model attributes, e.g.:
  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
      return [
          'is_admin' => 'boolean',
      ];
  }
- Never use the `.prevent` modifier for `wire:submit` in Livewire forms. The latest Livewire version does not require it.
