# Configuración de Seguridad - Cercle d'Art Foios

## Variables de Entorno

Este proyecto utiliza variables de entorno para gestionar credenciales sensibles y configuraciones específicas del entorno.

### Configuración Inicial

1. **Copiar el archivo de ejemplo:**
   ```bash
   cp .env.example .env
   ```

2. **Actualizar las credenciales en `.env`:**
   - Configuración de base de datos
   - Credenciales SMTP/Email
   - Keys de APIs (ReCAPTCHA, QuickEmail)
   - Contraseñas por defecto (solo para desarrollo)

3. **IMPORTANTE: NUNCA comitear el archivo `.env` al repositorio**
   - El archivo `.env` está incluido en `.gitignore`
   - Solo compartir `.env.example` como referencia

### Variables de Entorno Requeridas

#### Base de Datos
```env
database.default.hostname = your_db_host
database.default.database = your_db_name
database.default.username = your_db_user
database.default.password = your_db_password
database.default.DBDriver = MySQLi
database.default.DBDebug = false  # Siempre false en producción
database.default.port = 3306
```

#### Email/SMTP
```env
email.fromEmail = your_email@example.com
email.fromName = Your Application Name
email.SMTPHost = smtp.example.com
email.SMTPUser = your_smtp_user@example.com
email.SMTPPass = your_smtp_password
email.SMTPPort = 465
email.SMTPCrypto = ssl
```

#### APIs Externas
```env
recaptchaSiteKey = your_recaptcha_site_key
recaptchaSecretKey = your_recaptcha_secret_key
QUICKEMAIL_API_KEY = your_quickemail_api_key
```

## Configuraciones de Seguridad

### Producción

En el archivo `.env` de producción, asegúrate de:

1. **Establecer el entorno correcto:**
   ```env
   CI_ENVIRONMENT = production
   ```

2. **Deshabilitar el debug de base de datos:**
   ```env
   database.default.DBDebug = false
   ```

3. **Usar contraseñas seguras:**
   - Mínimo 12 caracteres
   - Combinación de letras mayúsculas, minúsculas, números y símbolos
   - No reutilizar contraseñas entre diferentes servicios

### Desarrollo

En desarrollo puedes usar:
```env
CI_ENVIRONMENT = development
database.default.DBDebug = true
```

## Archivos a Revisar Periódicamente

### Controladores de Migración/Pruebas

Los siguientes controladores deben ser **eliminados o protegidos** antes de pasar a producción:

- `app/Controllers/Pruebas.php` - Controlador de pruebas
- `app/Controllers/Traspaso.php` - Script de migración de datos
- `app/Controllers/Traspaso2.php` - Script de migración de datos

**Recomendación:** Eliminar estos archivos o protegerlos con filtros de autenticación y restricción por IP.

## Checklist de Seguridad para Producción

- [ ] Archivo `.env` creado con credenciales de producción
- [ ] `CI_ENVIRONMENT = production` establecido
- [ ] `database.default.DBDebug = false`
- [ ] Contraseñas seguras y únicas para todas las credenciales
- [ ] Controladores de prueba eliminados o protegidos
- [ ] CSRF Protection habilitada (revisar `app/Config/Security.php`)
- [ ] Permisos de archivos correctos (writable/ solo escribible por la aplicación)
- [ ] Logs monitoreados regularmente
- [ ] Backups de base de datos configurados

## Soporte

Para más información sobre seguridad en CodeIgniter 4:
- [Documentación oficial de seguridad](https://codeigniter.com/user_guide/concepts/security.html)
- [Mejores prácticas de CodeIgniter 4](https://codeigniter.com/user_guide/intro/requirements.html)
