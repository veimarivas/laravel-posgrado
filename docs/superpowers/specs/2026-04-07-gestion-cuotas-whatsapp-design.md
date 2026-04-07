# Especificación: Gestión de Cuotas y Envío de Mensajes WhatsApp

## 1. Resumen del Proyecto

Agregar en el dashboard de ofertas (`admin.ofertas.dashboard.blade.php`) una nueva pestaña para:
1. Visualizar todos los planes de pago y cuotas de cada participante
2. Editar las fechas de pago de forma masiva para cuotas pendientes
3. Listar participantes con cuotas cercanas (próximos 5 días) y cuotas atrasadas
4. Generar mensajes personalizados por estudiante para envío vía WhatsApp

## 2. Ubicación

- **Vista principal:** `resources/views/admin/ofertas/dashboard.blade.php`
- **Partial sugerido:** `resources/views/admin/ofertas/partials/gestion-cuotas.blade.php`
- **Controller:** `app/Http/Controllers/Admin/OfertaDashboardController.php`

## 3. Datos a Mostrar

### 3.1 Tabla de Planes por Participante

| Campo | Descripción |
|-------|-------------|
| Nombre completo | Nombre del estudiante |
| Carnet | CI del participante |
| Plan de Pago | Nombre del plan |
| Cuotas | Lista de cuotas con: número, monto (Bs), fecha de vencimiento, estado (pagada/pendiente) |

**Estados de cuota:**
- Pagada: ✓ en verde, sin opción de edición
- Pendiente: editable, campo input type="date" con la fecha actual

### 3.2 Edición Masiva de Fechas

- Input global "Nueva fecha para todas las cuotas pendientes"
- Botón "Aplicar a todos" que actualiza las fechas de vencimiento de cuotas no pagadas

## 4. Listas de Envío de Mensajes

### 4.1 Cuotas Cercanas (≤5 días)

**Criterio:** `fecha_vencimiento <= hoy + 5 días` Y `estado = pendiente`

**Información por fila:**
- Nombre del estudiante
- Monto pendiente de la cuota
- Fecha de vencimiento
- Botón "Generar Mensaje"

**Mensaje generado:**
```
Hola [nombre], te recordamos que tu siguiente cuota de [nombre_oferta] vence el [fecha]. Monto a pagar: [monto] Bs. Ya pagaste: [total_pagado] Bs.
```

### 4.2 Cuotas Atrasadas

**Criterio:** `fecha_vencimiento < hoy` Y `estado = pendiente`

**Información por fila:**
- Nombre del estudiante
- Monto vencido
- Días de retraso
- Botón "Generar Mensaje"

**Mensaje generado:**
```
Hola [nombre], tu pago de [nombre_oferta] se encuentra atrasado. Monto pendiente: [monto] Bs. Total pagado: [total_pagado] Bs. Por favor comunícate para regularizar tu situación.
```

## 5. Comportamiento del Botón de Mensaje

- Abrir nueva pestaña/ventana del navegador
- Mostrar el mensaje formateado en un área de texto editable
- El usuario puede modificar el mensaje antes de copiar/enviar
- No requiere integración API externa (solo muestra el texto para copia manual)

## 6. Estructura de Datos del Controller

### Métodos requeridos en `OfertaDashboardController`:

```php
public function getGestionCuotas($ofertaId)
{
    // Retorna:
    // - participantes: lista con todos los participantes y sus cuotas
    // - cuotas_cercanas: participantes con cuota en los próximos 5 días
    // - cuotas_atrasadas: participantes con cuota vencida
}

public function actualizarFechasCuotas(Request $request)
{
    // Recibe: oferta_id, nueva_fecha
    // Actualiza fecha_pago de todas las cuotas pendientes
}
```

## 7. UI/UX

### Pestaña "Gestión de Cuotas"

```
+------------------------------------------+
| [Resumen] [Participantes] [Finanzas]    |
| [Académico] [Demográfico] [Gestión] [Gestión de Cuotas]  |
+------------------------------------------+

+-- Sección: Planes y Cuotas ------------+
| [_INPUT FECHA GLOBAL_] [Aplicar a todos]|
+------------------------------------------+
| Tabla de participantes con cuotas...   |
+------------------------------------------+

+-- Sección: Mensajes --------------------+
| [Cuotas Cercanas] [Cuotas Atrasadas]    |
+------------------------------------------+
| Lista de participantes + botón mensaje |
+------------------------------------------+
```

### Estados visuales

- Cuota pagada: icono ✓ verde, fondo sutil verde
- Cuota pendiente: input date editable, fondo blanco
- Cuota cercana: badge amarillo "Próxima"
- Cuota atrasada: badge rojo "Atrasada"

## 8. Validaciones

- Solo editar fechas de cuotas con `pago_terminado = false`
- La fecha no puede ser anterior a hoy (solo postergar, no anticipar)
- Validar que el monto mostrado sea el correcto según la tabla de cuotas

## 9. Criterios de Éxito

- [ ] La pestaña se muestra en el dashboard de ofertas
- [ ] Se visualizan todas las cuotas de cada participante
- [ ] Las cuotas pagadas se distinguen visualmente de las pendientes
- [ ] Las fechas de cuotas pendientes son editables
- [ ] Existe edición masiva de fechas
- [ ] Lista de cuotas cercanas (≤5 días) se muestra correctamente
- [ ] Lista de cuotas atrasadas se muestra correctamente
- [ ] El botón de mensaje abre ventana con texto formateado por estudiante