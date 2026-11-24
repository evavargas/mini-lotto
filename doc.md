Quiero armar una prueba de concepto chica para mostrar en una entrevista con una empresa que tiene sitios de lotería online (123millonario.mx y 123loterias.com). Tiene que usar el mismo stack que ellos:

- PHP 8 puro, sin framework, corriendo con Apache (con .htaccess) o con `php -S` para desarrollo
- jQuery 3.3.1 y Bootstrap 4.6.2 por CDN
- SQLite con PDO (fácil de cambiar a MySQL)
- Sin build step, sin npm

## Qué tiene que hacer

Se llama "Mini Lotto". Es una lotería ficticia sin plata real.

1. **Home:** el próximo sorteo con cuenta regresiva y el pozo acumulado.
2. **Selector de números:** una grilla del 1 al 45 donde se eligen 6 números, con un botón "Al azar". La validación se hace en el cliente y también en el servidor.
3. **Carrito:** se guarda en la sesión de PHP. Se pueden agregar varias jugadas, sacar jugadas y ver el total.
4. **Login y registro por AJAX:** con endpoints en `/_ajax/login.php` y `/_ajax/register.php`, `password_hash`, token CSRF y respuestas JSON.
5. **Checkout simulado:** con dos opciones, tarjeta de prueba u OXXO (que genera una referencia de pago falsa). Al confirmar, se guarda la orden con sus jugadas.
6. **Minijuego de sorteo instantáneo:** después de comprar, un botón "Jugar sorteo" saca 6 bolillas con una animación hecha en jQuery y CSS. El servidor decide el resultado con `random_int` y el cliente solo lo anima. Se marcan los aciertos y se muestra el premio según una tabla de premios.
7. **Mis jugadas:** el historial del usuario con sus resultados.

## Estructura y calidad

- Separar en `public/` (entry points y assets), `src/` (lógica, repositorios, helpers) y `views/` (partials).
- Usar prepared statements siempre, escapar el output y aplicar CSRF en todos los POST.
- Hacerlo responsive y mobile first, con estética de lotería pero prolija.
- Incluir un script `setup.php` que cree la base y cargue datos de ejemplo.
- Hacer un `CLAUDE.md` en la raíz con convenciones de código, estructura y cómo correrlo, y una carpeta `docs/` con decisiones técnicas (por qué sesión para el carrito, por qué el resultado lo decide el servidor, etc.).
- Hacer un `README.md` con cómo correrlo en 2 comandos y una sección "Qué mejoraría en producción": pasarela real, colas para los sorteos, rate limiting, logs, tests y accesibilidad.
- Hacer todo en ingles y no usar comentarios.
- Utilizar internacionalización
- Utilizar accesibilidad
- Utilizar tema claro y oscuro
- Quiero deployarlo de manera gratuita asi que recomendame donde y como hacemos, y aseguremos utlizar buenas practicas tanto de desarrollo como de infraestructura