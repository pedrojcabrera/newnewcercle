# 📋 DEPLOYMENT CHECKLIST - Producción Remote

**Fecha Preparación:** 9 de mayo de 2026
**Servidor Destino:** qvn651.cercledartfoios.com
**Base de Datos:** qvn651 (remota)
**Marco:** CodeIgniter 4

---

## FASE 1: PRE-DEPLOYMENT (Local)

### ✅ Verificaciones Previas
- [ ] Código validado sin errores (panel Problems limpio)
- [ ] Todas las URLs de email usando `base_url()` dinámico
- [ ] No hay referencias a `newnewcercle.test` en código activo
- [ ] .htaccess preserva protocolo HTTPS (`%{REQUEST_SCHEME}`)
- [ ] Base de datos en sync con última estructura (migrations ejecutadas)
- [ ] Backup local completado de BD desarrollo

### ✅ Preparación de .env Producción
Crear/actualizar `.env` en servidor remoto con estos valores:

```properties
CI_ENVIRONMENT = production

# ==================== APPLICATION ====================
app.baseURL = 'https://qvn651.cercledartfoios.com/'
app.forceGlobalSecureRequests = true
app.CSPEnabled = true

# ==================== DATABASE ====================
database.default.hostname = qvn651.cercledartfoios.com
database.default.database = qvn651
database.default.username = [TU_USUARIO_DB_REMOTA]
database.default.password = [TU_PASSWORD_DB_REMOTA]
database.default.port = 3306
database.default.DBDriver = MySQLi
database.default.DBDebug = false
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci

# ==================== EMAIL ====================
email.fromEmail = noreply@cercledartfoios.com
email.fromName = Cercle d'Art de Foios
email.protocol = SMTP
email.SMTPHost = smtp.serviciodecorreo.es
email.SMTPUser = [TU_USUARIO_SMTP]
email.SMTPPass = [TU_PASSWORD_SMTP]
email.SMTPPort = 587
email.SMTPCrypto = tls
email.mailType = html
email.charset = UTF-8
email.wordWrap = true
email.wrapChars = 76

# ==================== ENCRYPTION ====================
encryption.key = [TU_ENCRYPTION_KEY_SEGURA]
encryption.driver = OpenSSL

# ==================== SECURITY ====================
security.tokenName = csrftoken
security.headerName = X-CSRF-TOKEN
security.cookieName = __Host-XSRF-TOKEN
security.expires = 7200
security.regenerate = true
security.redirect = true
security.samesite = Strict

# ==================== RECAPTCHA ====================
recaptcha.siteKey = [TU_SITE_KEY_PRODUCCION]
recaptcha.secretKey = [TU_SECRET_KEY_PRODUCCION]

# ==================== SESSION ====================
session.driver = DatabaseHandler
session.cookieName = PHPSESSID
session.expiration = 7200
session.savePath = sessions
session.matchIP = true
session.timeToUpdate = 300
session.regenerateDestroy = true
```

---

## FASE 2: DEPLOYMENT (Servidor Remoto)

### 📦 PASO 1: Subir Código

```bash
# Opción A: Git (recomendado)
cd /home/qvn651/public_html
git clone https://github.com/pedrojcabrera/newnewcercle.git .

# Opción B: FTP/SFTP
# Sube la carpeta completa excepto:
# - .env (usar la de producción)
# - writable/ (crear vacío en servidor)
# - tests/
# - builds/
```

### 🔐 PASO 2: Permisos de Directorios

```bash
# Directorios que necesitan escritura
chmod 755 writable/
chmod 755 writable/cache/
chmod 755 writable/debugbar/
chmod 755 writable/logs/
chmod 755 writable/session/
chmod 755 writable/uploads/
chmod 755 public/fotosUsuarios/
chmod 755 public/galerias/
chmod 755 public/imgEventos/
chmod 755 public/pdfEventos/

# Archivos .htaccess
chmod 644 public/.htaccess
chmod 644 writable/.htaccess
```

### 🗄️ PASO 3: Base de Datos (phpMyAdmin)

**Opción Recomendada (Tu Preferencia):**

1. Accede a phpMyAdmin del servidor remoto
2. Selecciona la BD `qvn651`
3. **Importar** → Sube el backup SQL local de desarrollo
4. En caso necesario, ejecuta migraciones CodeIgniter:
   ```bash
   php spark migrate
   ```

**Verificar Estructura:**
- [ ] Todas las tablas presentes
- [ ] Usuarios de administración existen
- [ ] Índices creados
- [ ] Caracteres UTF-8mb4 aplicados

### 🔑 PASO 4: Variables de Entorno

**Dentro del servidor remoto (SSH o Panel de Control):**

```bash
# Editar/crear .env en la raíz del proyecto
nano .env  # o tu editor preferido
```

**Copiar bloque de arriba (FASE 1) con valores reales:**
- `[TU_USUARIO_DB_REMOTA]` → Usuario de BD remota
- `[TU_PASSWORD_DB_REMOTA]` → Contraseña BD remota
- `[TU_USUARIO_SMTP]` → Usuario de correo serviciodecorreo.es
- `[TU_PASSWORD_SMTP]` → Contraseña SMTP
- `[TU_ENCRYPTION_KEY_SEGURA]` → Generar con `php spark generate:key`
- `[TU_SITE_KEY_PRODUCCION]` → De Google reCAPTCHA v3 (producción)
- `[TU_SECRET_KEY_PRODUCCION]` → De Google reCAPTCHA v3 (producción)

**Guardar y cerrar:**
```
Ctrl+O (Enter) → Ctrl+X
```

### 📦 PASO 5: Instalar Dependencias

```bash
cd /home/qvn651/public_html
composer install --no-dev --optimize-autoloader
```

### 🔄 PASO 6: Cache y Optimización

```bash
# Limpiar cachés
php spark cache:clear
php spark view:cache
php spark config:cache

# Opcional: Pre-compilar rutas (si lo soporta)
php spark route:cache
```

---

## FASE 3: VALIDACIÓN POST-DEPLOYMENT

### 🔍 Verificaciones Críticas

- [ ] **URL Base Correcta:**
  ```
  Accede a https://qvn651.cercledartfoios.com/
  Debería cargar sin errores
  ```

- [ ] **SSL Certificate:**
  - [ ] Se conecta por HTTPS
  - [ ] Certificado válido (sin avisos de navegador)
  - [ ] Redirige HTTP → HTTPS automáticamente

- [ ] **Formularios Funcionan:**
  - [ ] Formulario "Contactar" → envía emails
  - [ ] reCAPTCHA v3 no muestra errores
  - [ ] CSRF token validado correctamente

- [ ] **Emails Enviándose:**
  - [ ] Prueba: Envía email de contacto
  - [ ] Verifica que llega a bandeja (no spam)
  - [ ] Imágenes de email cargan (logo Cercle, anagrama)

- [ ] **Base de Datos Accesible:**
  - [ ] Página home carga contenido (eventos, enlaces, noticia)
  - [ ] Galería de fotos carga imágenes
  - [ ] Búsquedas/filtros funcionan

- [ ] **Admin Panel (si aplica):**
  - [ ] Login funciona
  - [ ] Puede enviar campaigns de emails
  - [ ] Puede crear eventos

- [ ] **Panel de Errores (Debug):**
  - [ ] `CI_ENVIRONMENT = production` → No muestra errores públicamente
  - [ ] Logs escriben en `writable/logs/`

---

## FASE 4: MONITOREO Y MANTENIMIENTO

### 📊 Primeros Días Post-Deploy

1. **Revisa logs diariamente:**
   ```bash
   tail -f writable/logs/log-*.log
   ```

2. **Monitorea BD:**
   - Verifica que no hay errores de conexión
   - Backup automático configurado

3. **Testing de Usuarios:**
   - Invita a usuarios beta a probar
   - Recopila feedback

4. **SSL Renewal (si corresponde):**
   - Configura auto-renewal de certificado (Let's Encrypt o similar)

5. **Backups Automáticos:**
   - Configura backup diario de BD
   - Backup de archivos cargados (`public/fotosUsuarios/`, `public/galerias/`)

---

## FASE 5: ROLLBACK (Si Algo Falla)

Si necesitas revertir rápidamente:

```bash
# 1. Restaurar código anterior (si usaste Git)
cd /home/qvn651/public_html
git log --oneline
git revert [HASH_DEL_DEPLOY]
git push

# 2. Restaurar BD desde backup
# Accede a phpMyAdmin → Importar backup anterior

# 3. Limpiar cache
php spark cache:clear
```

---

## 🎯 RESUMEN RÁPIDO (Checklist Final)

- [ ] Código sin errores en panel Problems
- [ ] .env producción con todos los valores reales
- [ ] Base de datos importada en servidor remoto
- [ ] Permisos de directorios (755/644) configurados
- [ ] `composer install` ejecutado
- [ ] Cachés limpiados con `php spark cache:clear`
- [ ] Acceso HTTPS funciona y sin advertencias
- [ ] Formulario de contacto envía emails
- [ ] Página home muestra contenido de BD
- [ ] Logs escriben correctamente en `writable/logs/`

---

## 📞 Troubleshooting Rápido

| Error | Causa | Solución |
|-------|-------|----------|
| **502 Bad Gateway** | Permisos/PHP | Verifica permisos `chmod 755 writable/` |
| **Emails no envían** | SMTP credenciales | Revisa `.env` email config vs serviciodecorreo.es |
| **"Base URL incorrect"** | .env no cargado | Verifica `CI_ENVIRONMENT = production` |
| **Imágenes emails no cargan** | base_url() mal | Verifica `app.baseURL` tiene trailing slash `/` |
| **DB connection failed** | Credenciales remotas | Revisa `.env` database.default.* |
| **CSS/JS no cargan** | Routes | Verifica `.htaccess` /recursos y /public en config |

---

**Última Actualización:** 9 de mayo de 2026
**Estado:** ✅ Listo para Deploy
