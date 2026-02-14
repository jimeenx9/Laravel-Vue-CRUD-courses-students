# 🎓 Laravel + Vue CRUD — Cursos & Estudiantes (SPA)

**Mini app fullstack** con **Laravel (API REST)** + **Vue (SPA con router)** desplegada en **OpenStack**.

- 👤 **Autor:** Alberto Jiménez Rodríguez (@jimeenx9)
- 🧑‍🏫 **Profesor:** Alfredo Moreno Vozmediano
- 🧱 **Stack:** Laravel 12 + Vue 3 + Vite + SQLite + Apache2 + OpenStack
- 🌐 **Acceso (OpenStack):** `http://172.16.12.227/courses` y `http://172.16.12.227/students`

---

## ✅ Qué hace esta aplicación (resumen rápido)

📚 **Cursos (CRUD completo)**

- Crear curso (nombre + descripción)
- Listar cursos
- Editar curso
- Eliminar curso

👨‍🎓 **Estudiantes (CRUD completo)**

- Crear estudiante (nombre + email + curso)
- Listar estudiantes
- Editar estudiante
- Eliminar estudiante

🔗 **Relación 1:N real (Course → Students)**

- En el frontend se elige curso con un `<select>`
- En el listado se muestra el curso asociado usando la relación Eloquent

---

## 🖼️ Capturas principales

### 🖥️ Aplicación funcionando (Cursos)

![Aplicación Cursos](/img/app-cursos.png)


### 👨‍🎓 Aplicación funcionando (Estudiantes)

![Aplicación Estudiantes](/img/app-estudiantes.png)


### ☁️ OpenStack - Instancia creada

![OpenStack Instancia](/img/openstack-instancia.png)

### 🌐 OpenStack - IP flotante / elástica asociada

![OpenStack Floating IP](/img/openstack-floating-ip.png)


### 🔥 Reglas de seguridad / puertos abiertos

![OpenStack Security Group](/img/openstack-security-group.png)

### 🧪 Terminal servidor - app desplegada en /var/www

![Servidor Laravel en producción](/img/servidor-laravel-produccion.png)

---

## 🧠 Arquitectura del proyecto (cómo funciona por dentro)

📌 Esto es una **SPA real**:

- Laravel devuelve siempre la vista base (`welcome.blade.php`)
- Vue Router decide qué pantalla mostrar (Cursos / Estudiantes)
- Vue consume la API REST de Laravel mediante `fetch('/api/...')`

🧩 **Esquema mental:**

🖥️ Vue SPA

⬇️ (fetch JSON)

🧠 Laravel API REST

⬇️ (Eloquent ORM)

🗄️ SQLite

---

## 🗂️ Estructura del proyecto

La estructura principal (desarrollo) incluye:

- `resources/js/components/` → Vue Components (`App.vue`, `Courses.vue`, `Students.vue`)
- `routes/api.php` → Endpoints REST con `apiResource`
- `app/Http/Controllers/Api/` → Controladores de la API
- `app/Models/` → Modelos Eloquent + relaciones
- `database/migrations/` → Migraciones DB
- `public/` → Entrada Laravel + Vite build en `public/build`

---

## 🧱 Backend (Laravel) — API REST + ORM

### ✅ Endpoints REST

Definidos en `routes/api.php`:

- `/api/courses` (GET, POST, PUT, DELETE)
- `/api/students` (GET, POST, PUT, DELETE)

📌 Se usó `Route::apiResource()` para generar CRUD rápido y correcto.

---

### 🔗 Relación 1:N (Course → Students)

📌 En Eloquent:

- Un **Course tiene muchos Students**
- Un **Student pertenece a un Course**

Esto permite:

- En estudiantes usar `Student::with('course')` para devolver el curso embebido en JSON
- En Vue imprimir `s.course?.name`

---

### 🗄️ Base de datos SQLite

📌 Se eligió SQLite para simplicidad de despliegue:

- Fichero: `database/database.sqlite`
- Migraciones aplicadas con `php artisan migrate`
- Funciona perfecto para práctica y despliegue rápido

---

## 🎨 Frontend (Vue) — Componentes + Router + CRUD

### 🧩 Componentes Vue

- `App.vue` → layout general (sidebar + contenido)
- `Courses.vue` → CRUD cursos
- `Students.vue` → CRUD estudiantes

📌 Buena separación de responsabilidades y lógica.

---

### 🧭 Vue Router (SPA real)

Rutas:

- `/courses`
- `/students`

Laravel siempre sirve la vista principal gracias al **catch-all**:

📌 `routes/web.php`:

```php
Route::view('/{any}','welcome')->where('any','.*');
```

---

### 🧾 CRUD completo desde Vue (fetch)

Cada componente:

- hace `fetch()` para listar
- hace `POST/PUT/DELETE` contra la API
- actualiza la UI sin recargar la página

📌 En Estudiantes, además, se carga lista de cursos para el `<select>`.

---

## ☁️ Despliegue en OpenStack

**La aplicación está desplegada y accesible desde la red del departamento (o mediante VPN)**.

### 🧠 Red / IPs (explicación clara)

✅ OpenStack del instituto vive dentro de la red del departamento.

✅ El acceso se realiza desde la red del departamento o mediante VPN

- La instancia tiene IP privada interna: `10.0.*`
- Se asocia una **IP flotante (elástica)** del rango del instituto (ejemplo): `172.16.*12.227*`

📌 Esa `172.16...` **NO es pública de Internet**, es **privada del instituto** (solo accesible desde su red o por VPN).

---

### 🔥 Puertos / Security Group

Para que cargue desde navegador, se abrieron reglas típicas:

- `80/tcp` → Apache (web)
- (opcional) `22/tcp` → SSH (administración)

📌 Para pruebas usamos también `8000` con `php artisan serve`, pero el despliegue final es por **Apache en 80**.

---

### 🧰 Instalación en servidor (resumen realista de lo hecho)

1. Entrar por SSH:

```bash
ssh ubuntu@172.16.12.227
```

2. Clonar repo:

```bash
git clone https://github.com/jimeenx9/Laravel-Vue-CRUD-courses-students.git
cd Laravel-Vue-CRUD-courses-students
```

3. Instalar dependencias PHP:

```bash
composer install
```

4. Instalar dependencias JS:

```bash
npm install
```

5. Compilar frontend (importantísimo para producción):

```bash
npm run build
```

📌 Esto genera `public/build/manifest.json`

➡️ Si no existe, Laravel lanza la excepción:

`ViteManifestNotFoundException`

6. Crear `.env` y clave:

```bash
php artisan key:generate
```

7. Configurar SQLite:

```bash
touch database/database.sqlite
```

8. Migrar DB:

```bash
php artisan migrate --force
```

---

### 🧾 Permisos (Configuración de permisos en entorno de producción)

En producción, **Apache (www-data)** necesita poder escribir en:

- `storage/`
- `bootstrap/cache/`
- `database/database.sqlite`

Por eso se ajustaron permisos/propietarios para que:

✅ la web pueda escribir logs

✅ la web pueda escribir caché

✅ SQLite no quede “readonly”

---

### ⚙️ Apache apuntando a `/public`

Para que Laravel funcione bien, el DocumentRoot debe ser:

`/var/www/Laravel-Vue-CRUD-courses-students/public`

Y se activó `rewrite`:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

### 🚀 Optimización (modo “producción”)

En servidor se ejecutaron caches de Laravel como **www-data**:

```bash
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan optimize
```

📌 ¿Por qué como www-data?

Porque en producción el que escribe esos archivos es el usuario del servidor web.

---

## 🧪 Cómo ejecutar en local (desarrollo)

📌 En tu PC:

1. Instalar dependencias:

```bash
composer install
npm install
```

2. Crear `.env`:

```bash
cp .env.example .env
php artisan key:generate
```

3. SQLite + migraciones:

```bash
touch database/database.sqlite
php artisan migrate
```

4. En desarrollo (Vite + Laravel):
- Terminal A:

```bash
npm run dev
```

- Terminal B:

```bash
php artisan serve
```

---

## 🌐 Cómo ejecutar en OpenStack (servidor ya desplegado)

✅ Si Apache está configurado, basta con:

- Encender la instancia
- Entrar desde navegador con:

`http://172.16.12.227/courses`

`http://172.16.12.227/students`

📌 Si la instancia está apagada → no responde.

📌 Si está encendida → la web funciona sin lanzar artisan serve (porque Apache sirve Laravel).

---

## 💡 Flujo de trabajo recomendado (pro)

✅ **Desarrollo en tu PC** (rápido, cómodo, VSCode, Vite dev)

✅ **Git push** a GitHub

✅ En servidor: **git pull + npm build + caches**

🔥 Deploy típico:

```bash
cd /var/www/Laravel-Vue-CRUD-courses-students
git pull
composer install --no-dev --optimize-autoloader
npm install
npm run build
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan optimize
```

---

## ✅ Checklist de la práctica

### 🟣 Vue (Frontend)

✅ Componentes Vue definidos

✅ Separación lógica (cursos / estudiantes)

✅ CRUD cursos completo

✅ CRUD estudiantes completo

✅ Relación 1:N en interfaz (select + visualización)

✅ Vue Router (SPA real)

### 🟥 Laravel (Backend)

✅ API REST completa

✅ Migraciones correctas

✅ Modelos y relación Eloquent 1:N correcta

### ☁️ OpenStack (Extra)

✅ Desplegada y accesible desde red del departamento/VPN

---

## 📌 Notas finales

- Se mantiene persistencia de datos con SQLite en servidor
- Interfaz mejorada con diseño moderno (sidebar + cards + morado neon)

### 🏁 Versión final

**Versión:** v1.0.0  
**Autor:** @jimeenx9  
**Fecha:** Febrero 2026 

---

© 2026 — Práctica elaborada por **Alberto Jiménez**