# Modo Mantenimiento Personalizado para WordPress

Un plugin de WordPress flexible y fácil de usar para poner tu sitio en modo mantenimiento con opciones de personalización avanzadas.

![Versión](https://img.shields.io/badge/versión-1.0.2-blue)
![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-0073aa)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb3)
![License](https://img.shields.io/badge/licencia-GPL--2.0%2B-green)

## ✨ Características

✅ **Control por roles de usuario**: Decide qué roles de usuario pueden acceder al frontend cuando el modo mantenimiento está activo.

✅ **Aplicación selectiva**: Aplica el modo mantenimiento a todo el sitio o solo a URLs específicas.

✅ **Mensaje personalizable**: Editor completo para crear tu propio mensaje de mantenimiento con formato.

✅ **HTML personalizado**: Opción para usar código HTML completamente personalizado para tu página de mantenimiento.

✅ **Personalización visual**: Cambia colores de fondo y texto para adaptarse a tu marca.

✅ **Fácil de configurar**: Interfaz intuitiva integrada en el panel de administración de WordPress.

## 📥 Instalación

### Método manual
1. Descarga el archivo ZIP del plugin desde este repositorio
2. Ve a tu panel de administración de WordPress > Plugins > Añadir nuevo > Subir plugin
3. Selecciona el archivo ZIP y haz clic en "Instalar ahora"
4. Activa el plugin desde la sección Plugins

### Vía FTP
1. Descarga y descomprime el plugin
2. Sube la carpeta `modo-mantenimiento-personalizado` al directorio `/wp-content/plugins/` de tu instalación de WordPress
3. Activa el plugin desde la sección Plugins de tu panel de administración

## 🔧 Uso

Una vez activado el plugin, encontrarás un nuevo menú llamado "Modo Mantenimiento" en tu panel de administración de WordPress.

## ⚙️ Configuración

1. Ve a **Modo Mantenimiento** en el menú lateral
2. Activa el modo mantenimiento marcando la casilla correspondiente
3. Selecciona los roles de usuario que tendrán acceso al frontend
4. Elige si quieres aplicar el mantenimiento a todo el sitio o solo a URLs específicas
5. Guarda los cambios

### Personalización del mensaje

1. Usa el editor visual para modificar el mensaje que verán los visitantes
2. Personaliza el título de la página
3. Cambia los colores de fondo y texto según tus preferencias
4. Guarda los cambios para aplicarlos

### Uso de HTML personalizado

1. Activa la opción "Usar HTML personalizado" en la sección de Contenido
2. Introduce tu código HTML completo en el campo habilitado
3. Asegúrate de incluir todas las etiquetas necesarias (DOCTYPE, html, head, body, etc.)
4. El HTML personalizado reemplazará completamente el diseño predeterminado
5. Guarda los cambios para aplicarlos

### Aplicación a URLs específicas

Si seleccionas "Solo URLs específicas", podrás especificar qué páginas o secciones de tu sitio estarán en mantenimiento:

1. Escribe cada URL en una línea separada
2. Usa patrones con asteriscos como comodines (ejemplo: `/productos/*`)
3. No incluyas el dominio, solo la ruta relativa que comienza con `/`

## 📋 Ejemplos de uso

### Bloquear todo el sitio excepto para administradores
- Activa el modo mantenimiento
- Selecciona "Toda la web"
- Marca solo el rol "Administrador" en la sección de roles

### Bloquear solo la sección de eventos
- Activa el modo mantenimiento
- Selecciona "Solo URLs específicas"
- Añade `/eventos/*` en el campo de URLs

### Personalizar para anunciar un próximo lanzamiento
- Escribe un mensaje atractivo anunciando la fecha de lanzamiento
- Personaliza los colores para que coincidan con tu marca
- Considera añadir enlaces a tus redes sociales en el mensaje

### Crear una página de mantenimiento 100% personalizada
- Activa la opción "Usar HTML personalizado"
- Inserta tu propio código HTML con todos los elementos diseñados a tu medida
- Incluye recursos externos como imágenes, CSS o JavaScript si lo necesitas
- Crea una experiencia única para tus visitantes durante el mantenimiento

## 💡 Casos de uso

Este plugin es ideal para:

- **Sitios en desarrollo**: Mantén tu sitio oculto mientras trabajas en él
- **Mantenimiento programado**: Avisa a los usuarios de actualizaciones o cambios importantes
- **Eventos con inscripción cerrada**: Muestra un mensaje personalizado cuando la inscripción ha finalizado
- **Lanzamientos de nuevos productos**: Crea expectación mostrando una página de "Próximamente"

## ❓ Solución de problemas

### El modo mantenimiento no se aplica a URLs específicas
- Asegúrate de que las URLs comienzan con `/`
- No incluyas el dominio (ejemplo correcto: `/mi-pagina` y no `https://midominio.com/mi-pagina`)
- Recuerda que los patrones con asterisco son comodines (ejemplo: `/productos/*`)

### Algunos usuarios no pueden acceder aunque tengan el rol permitido
- Asegúrate de que los usuarios han iniciado sesión
- Comprueba que el rol está correctamente seleccionado en la configuración
- Verifica si los usuarios tienen múltiples roles (prevalecerá el que esté permitido)

## 🛠️ Contribución

¡Las contribuciones son bienvenidas! Si deseas contribuir:

1. Haz un fork del repositorio
2. Crea una nueva rama (`git checkout -b feature/nueva-caracteristica`)
3. Realiza tus cambios
4. Haz commit de tus cambios (`git commit -m 'Añade nueva característica'`)
5. Sube tus cambios (`git push origin feature/nueva-caracteristica`)
6. Abre un Pull Request

### Directrices para contribuciones
- Sigue las convenciones de codificación de WordPress
- Asegúrate de que tu código sea compatible con la última versión de WordPress y WooCommerce
- Incluye comentarios claros en tu código
- Actualiza la documentación si es necesario

## 📜 Licencia

Este plugin está licenciado bajo [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.txt). Puedes usar, modificar y distribuir este software bajo los términos de esta licencia.

## 🔄 Registro de Cambios

### 1.0.2 (03-04-2025)
- Añadida funcionalidad para usar HTML personalizado como página de mantenimiento
- Agregada opción para activar/desactivar el uso de HTML personalizado
- Implementado campo para insertar código HTML personalizado
- Mejorada la interfaz de usuario para mostrar/ocultar opciones según configuración

### 1.0.1 (31-03-2025)
- Corregido bug en la verificación de URLs específicas
- Mejorada la expresión regular para patrones de URL
- Añadidas instrucciones más claras en el panel de administración
- Implementado soporte para depuración con WP_DEBUG

### 1.0.0 (31-03-2025)
- Lanzamiento inicial del plugin
- Implementación de funcionalidad base de mantenimiento
- Soporte para selección de roles de usuario
- Opción para aplicar a toda la web o URLs específicas
- Personalización completa del mensaje y apariencia

## 👥 Créditos

Desarrollado por n3uron4

## 📧 Contacto

Para soporte, sugerencias o reportar bugs, por favor:
- Abre un [Issue](https://github.com/n3uron4/modo-mantenimiento-personalizado/issues) en GitHub

---

Desarrollado con ❤️ para la comunidad WordPress