<p align="center"><img src="public/imagen/logo1.jpeg" alt="AE Logo" height="220"></p>

# <p align="center"> ABARROTES EXPRESS</p>

Proyecto usado para la gestión de una abarrotería. Desarrollado usando como base el framework de PHP, Laravel. La aplicación permite administrar productos, ordenes, inventarios y clientes, proporcionando una interfaz amigable y sencilla para los usuarios y administradores.

### Características Principales

---
- **Compras:** Permite a los usuarios poder agregar elementos al carrito, agregarlos a lista de deseos y proceder a comprar, usando los distintos métodos de pagos.
- **Gestiona:** Datos relacionados al usuario, como las direcciones para facilitar las compras y evitar que el usuario llene los mismos datos, de manera manual, en cada compra, además le permite.
- **Administra:** todos los datos sobre las categorías, marcas y productos, entre otros datos de importancia que se encuentre disponible tanto para los administradores como para los usuarios invitados y los clientes. 

### Para empezar

---
#### Prerequisitos

- **NVM** (recomendado para asegurar versión de Node) ver [documentación oficial](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating).


```bash
nvm use
# o
nvm use <version>
```

#### Instalación
1. Clona el repositorio
   ```bash
    git clone https://github.com/davidtriminio/abarrotes-express.git 
   ```
   
2. Instala los paquetes de NPM
    ```bash
    npm run install
   ```
3. Construir estilos de Tailwind
    ```bash
    npm run build
   ```
4. Modificar archivo .env (Guiarse por el archivo .envexample) y obtener key del proyecto
   ```bash
    php artisan key:generate
   ```
5. Ejecutar migraciones y seeders (Usar datos en RolesPermisosSeeder o modificarlos con datos propios)
    ```bash
    php artisan migrate --seed
   ```
6. Enlazar los archivos en el almacenamiento
    ```bash
    php artisan storage:link
   ```
7. Iniciar el servidor PHP
    ```bash
    php artisan serve
   ```
### 🛠️Stack

---
- ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) -A popular general-purpose scripting language that is especially suited to web development.
  Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.
- ![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white) - The PHP Framework
  for Web Artisans
- ![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white) - Rapidly build modern websites without ever leaving your HTML.
- ![Livewire](https://img.shields.io/badge/livewire-%234e56a6.svg?style=for-the-badge&logo=livewire&logoColor=white) - The most productive way to build your next web app.
