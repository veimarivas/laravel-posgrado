# Diseño: Bloqueo de Usuarios Inactivos

**Fecha:** 2026-04-26
**Proyecto:** laravel-posgrado

## Objetivo

1. Impedir que usuarios con `estado = 'Inactivo'` puedan iniciar sesión
2. Ocultar trabajadores inactivos en la página welcome (sección "Nuestro Personal")

## Valores de Referencia

- Usuario activo: `estado = 'Activo'`
- Usuario inactivo: `estado = 'Inactivo'`

---

## Implementación

### 1. Bloqueo de Login para Usuarios Inactivos

**Archivo:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**Cambio en el método `store()`:**

```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = $request->user();
    if ($user->estado === 'Inactivo') {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return back()->withErrors(['email' => 'Usuario inactivo. No tiene acceso al sistema.']);
    }

    $request->session()->regenerate();

    if ($user->role === 'admin') {
        return redirect()->intended('admin/dashboard');
    }

    return redirect()->intended('/dashboard');
}
```

---

### 2. Filtrar Trabajadores Inactivos en Welcome

Agregar filtro para excluir trabajadores con usuario inactivo. Modificar la query en `BienvenidosController.php` línea 72-88:

```php
$trabajadores = Trabajadore::with([
    'persona',
    'trabajadores_cargos' => function ($query) {
        $query->where('principal', 1)
            ->where('estado', 'Vigente')
            ->whereIn('cargo_id', [2, 3, 6]) // Solo cargos 2, 3 y 6
            ->with(['cargo', 'sucursal.sede']);
    }
])
    ->whereHas('trabajadores_cargos', function ($query) {
        $query->where('principal', 1)
            ->where('estado', 'Vigente')
            ->whereIn('cargo_id', [2, 3, 6]); // Solo cargos 2, 3 y 6
    })
    // NUEVO: Filtrar usuarios que no estén inactivos
    ->whereHas('persona.users', function ($q) {
        $q->where('estado', '!=', 'Inactivo');
    })
    // Fin nuevo
    ->orderBy('id')
    ->get()
    // ... resto del map() sin cambios

---

## Verificación

1. **Login:** Crear usuario de prueba con estado "Inactivo" y verificar que muestre error al intentar login
2. **Welcome:** Verificar que los trabajadores con usuario inactivo no aparezcan en la sección "Nuestro Personal"