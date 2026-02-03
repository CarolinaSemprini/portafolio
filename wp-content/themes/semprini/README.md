# Tema WordPress: Semprini

Portafolio profesional de **Carolina Semprini** (desarrollo full-stack / data), construido como **tema propio** de WordPress con estilo **dark + glassmorphism**, animaciones y ACF.

## 📁 Estructura principal

Este repositorio **NO contiene WordPress completo**, ni plugins, ni uploads.  
Está diseñado para ser **liviano, reutilizable, versionable y fácil de reinstalar** en cualquier entorno.

---

## 📦 ¿Qué incluye este repositorio?

✅ Tema WordPress completo (`semprini`)  
✅ Templates personalizados  
✅ Custom Post Types  
✅ Campos personalizados con **Advanced Custom Fields (ACF)**  
✅ Sincronización ACF vía JSON (ACF Local JSON)  
✅ Export del contenido WordPress (`.xml`)  
✅ Código optimizado para performance (Lighthouse / PageSpeed)

❌ WordPress core  
❌ Plugins  
❌ Uploads  
❌ Base de datos  

---

## 🗂️ Estructura del proyecto
wp-content/
└── themes/
└── semprini/
├── css/
├── js/
├── img/
├── inc/
├── acf-json/
│ ├── group_*.json
├── docs/
│ └── copiawordpress.xml
├── functions.php
├── style.css
├── README.md

---

## ⚙️ Requisitos del entorno

- WordPress **6.x**
- PHP **8.0 o superior**
- Plugin **Advanced Custom Fields (FREE)**
- Servidor local o remoto (Local, XAMPP, WAMP, hosting)

---

## 🚀 Instalación desde cero (paso a paso)

### 1️⃣ Instalar WordPress
Instalar WordPress de forma normal (local o producción).

⚠️ Este repositorio **no incluye WordPress**, solo el theme.

---

### 2️⃣ Instalar el theme

Copiar la carpeta `semprini` dentro de:
wp-content/themes/

---

### 3️⃣ Instalar plugins necesarios

Instalar **únicamente** el siguiente plugin:

- **Advanced Custom Fields (FREE)**  
  https://wordpress.org/plugins/advanced-custom-fields/

⚠️ Los plugins **NO se suben al repositorio**.  
Siempre se instalan desde WordPress.

---

## 🧩 ACF – Campos personalizados (PASO CLAVE)

Este theme utiliza **ACF Local JSON**, por lo tanto **NO es necesario crear los campos manualmente**.

### Pasos para sincronizar ACF:

1. Ir a:
WP Admin → Herramientas → ACF → Sincronizar
2. Seleccionar **todos los grupos**
3. Hacer click en **Sincronizar**

Los archivos ACF se encuentran en:
wp-content/themes/semprini/acf-json/
Esto recrea automáticamente:
- Campos del Home
- Campos del About
- Certificados
- Proyectos (Portfolio)

---

## 📄 Importar contenido (opcional pero recomendado)

Si se desea recuperar **páginas, entradas y proyectos**, se incluye un export liviano de WordPress.

### Importar contenido XML:

1. Ir a:
WP Admin → Herramientas → Importar
2. Elegir **WordPress**
3. Subir el archivo:
wp-content/themes/semprini/docs/copiawordpress.xml
4. Asignar autores
5. (Opcional) Importar medios

⚠️ Las imágenes deberán volver a subirse si no existen en el nuevo entorno.

---

## 🧱 Custom Post Types incluidos

- **Proyecto** (Portfolio)
- **Certificados**

Definidos en:
/inc/custom-post-types.php
---

## 🧠 Templates principales

- `template-home.php` → Home
- `template-about.php` → Sobre mí
- `template-services.php` → Servicios
- `template-contact.php` → Contacto (AJAX)
- `archive-proyecto.php` → Archivo del portfolio
- `single-proyecto.php` → Proyecto individual

---

## ✉️ Formulario de contacto (AJAX)

El formulario de contacto utiliza AJAX nativo de WordPress.

Características:
- Envío vía `admin-ajax.php`
- Protección con:
  - Nonce
  - Honeypot
- Compatible con SMTP

Archivos principales:
/functions.php
/js/contact-form.js
Para producción se recomienda configurar:
- **WP Mail SMTP**
- Un proveedor SMTP (Brevo, Gmail, SMTP propio)

---

## ⚡ Performance & optimización

El theme ya incluye optimizaciones de base:

- Scripts cargados en footer
- Carga condicional de librerías externas
- Lazy loading en imágenes e iframes
- Gutenberg desactivado
- Sin dependencias innecesarias
- Canvas de partículas optimizado
- Código defensivo (file_exists / filemtime)

Testeado con:
- Lighthouse
- PageSpeed Insights
- Chrome DevTools (modo incógnito)

---

## 🧪 Testing recomendado

- Lighthouse (Mobile / Desktop)
- PageSpeed Insights
- Chrome DevTools → Network / Performance

---

## 🔐 Git & control de versiones

Este repositorio **versiona únicamente el theme**.

Se excluye explícitamente:
- WordPress core
- Plugins
- Uploads
- Cache
- Configuración sensible (`wp-config.php`)

Ventajas:
- Repositorio liviano
- Fácil reutilización
- Deploy limpio
- Ideal para portfolio profesional

---

## ♻️ Reutilización del theme

Para reutilizar este theme en otro proyecto:

1. Copiar la carpeta `semprini`
2. Instalar ACF
3. Sincronizar ACF
4. (Opcional) Importar el XML
5. Ajustar textos, estilos y contenido

---

## 👩‍💻 Autora

**Carolina Semprini**  
Frontend & WordPress Developer  

---

## 📌 Notas finales

- Proyecto pensado como base profesional
- Independiente del entorno
- Seguro para versionar y compartir
- Ideal como portfolio técnico y base de futuros proyectos