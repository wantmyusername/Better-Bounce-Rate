# Better Bounce Rate

Plugin de **WordPress** que ayuda a **reducir el "bounce rate" (tasa de rebote)** en **Google Analytics 4**: inyecta el `gtag` de GA4 y, tras un tiempo configurable, dispara un evento **`LowBounce`** para que la sesión cuente como **no-rebote**.

- **Autor:** BunkerLATAM — <https://codecanyon.net/user/bunkerlatam>
- **Licencia:** GPLv2
- **Versión:** 1.0

## Cómo funciona

1. En `wp_head` añade el script de **GA4** (`googletagmanager.com/gtag/js?id=G-XXXX-XX`) y hace `gtag('config', …)`.
2. Con `setTimeout`, tras **`10 × time` ms**, envía:

```js
gtag('event', 'LowBounce', {
  event_category: 'LowBounce',
  event_label: 'LowBounce'
});
```

3. Opcionalmente añade un **"complementary code"** (código extra) al final del bloque de GA.

> Es una "optimización" **de métrica**: lo que hace es enviar un evento de interacción para que GA4 no marque la visita como rebote, aunque el usuario no haya hecho nada más.

## Configuración

En **Ajustes → Better Bounce Rate**:

| Campo | Clave | Descripción |
|---|---|---|
| **Tracking Code ID** | `trid` | Measurement ID de GA4 (`G-XXXX-XX`). |
| **Time on event** | `toe` | Tiempo (ver nota sobre las unidades). Recomiendan **1000–5000**. |
| **I want to add more code** | `mcode` | Casilla para activar el código complementario. |
| **Complementary code** | `ccode` | Código extra que se añade al bloque de GA. |

> **Unidades:** la etiqueta dice "milliseconds", pero el código usa `10 × time`. Es decir, el retardo real es **10 veces** el valor introducido (p. ej. `1000` → `10 000 ms` = 10 s). Tenlo en cuenta al configurarlo.

La configuración se guarda por **AJAX** en la opción `bbr_settings` (`wp_ajax_guardar_bbr_AJAX`) y se marca `bbr_configured`.

## Estructura

```
Better Bounce Rate/
  index.php              Bootstrap del plugin (admin, AJAX, hooks, wp_head)
  inc/admin-html.php     Página de ajustes (formulario + JS)
  inc/header-html.php    El código GA4 + evento LowBounce que se inyecta
  assets/analytics.png   Imagen de ayuda (dónde encontrar el Measurement ID)
  readme.txt             Readme en formato WordPress (GPLv2)
```

## Instalación

1. Sube la **carpeta** a `/wp-content/plugins/` **o** el **`.zip`** desde *Plugins → Añadir nuevo → Subir plugin*.
2. **Actívalo**.
3. Ve a **Ajustes → Better Bounce Rate**, pega tu **Measurement ID** de GA4 y ajusta el tiempo.

## Requisitos

- **WordPress 4.7+** (probado hasta 5.7)
- **PHP**
- Comprueba que el Measurement ID corresponde a un stream de GA4.

## Notas y advertencias

- La función `add_analytics_code` solo inyecta el código cuando `bbr_configured === 'on'`.
- ⚠️ **Manipula una métrica de analítica**: al forzar el evento `LowBounce` la tasa de rebote deja de reflejar el comportamiento real. Úsalo con criterio.
- ⚠️ **Seguridad del guardado AJAX:** `guardar_bbr_AJAX` **no verifica nonce ni capacidad** (`current_user_can('manage_options')`); solo exige estar logueado. Cualquier usuario autenticado (incluso un suscriptor) podría cambiar los ajustes. Recomendable añadir `check_ajax_referer()` + chequeo de capacidad.
- El **código complementario** se imprime sin escapar (`stripslashes`), porque está pensado para pegar código de GA; solo debería editarlo un administrador.
- Compatible con traducciones vía `.po/.mo`.

## Licencia

**GPLv2** — ver `readme.txt`. Autor original: **BunkerLATAM**.
