# 🚀 Guide de déploiement VPS

Ce guide explique comment déployer l'API Claw Machine sur le VPS avec CI/CD automatique.

## 📋 Prérequis

- VPS avec SSH configuré (45.155.171.55)
- Nginx/Apache + PHP 8.1+ + Composer installés
- MySQL/MariaDB installé avec base `overload_db`
- Git installé sur le VPS
- Compte GitHub avec accès au repository

## 🔧 Étape 1 : Configuration initiale du VPS

### 1.1 Connexion SSH au VPS

```bash
ssh mmi23c14@45.155.171.55
```

### 1.2 Cloner le repository (première fois uniquement)

```bash
cd /home/mmi23c14/public_html/
git clone https://github.com/VOTRE_USERNAME/claw-machine-api.git overload-back
cd overload-back
git checkout production
```

### 1.3 Créer le fichier .env.local avec les vraies valeurs

```bash
cd /home/mmi23c14/public_html/overload-back
nano .env.local
```

Copier le contenu de `.env.production.example` et remplacer :
- `APP_SECRET` : générer avec `php bin/console secrets:generate-keys`
- `GAME_SECRET_KEY` : une clé aléatoire longue
- `DATABASE_URL` : remplacer `VOTRE_MOT_DE_PASSE_DB` par le vrai mot de passe MySQL
- `CORS_ALLOW_ORIGIN` : votre domaine de production

### 1.4 Installer les dépendances et configurer

```bash
composer install --no-dev --optimize-autoloader
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console cache:clear --env=prod
chmod +x deploy.sh
```

### 1.5 Configurer Nginx/Apache

**Pour Nginx**, créer `/etc/nginx/sites-available/overload-back` :

```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /home/mmi23c14/public_html/overload-back/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }
}
```

Activer le site :
```bash
sudo ln -s /etc/nginx/sites-available/overload-back /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔑 Étape 2 : Configurer GitHub Actions

### 2.1 Générer une paire de clés SSH (sur votre machine locale)

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy
```

Cela créera deux fichiers :
- `~/.ssh/github_deploy` (clé privée)
- `~/.ssh/github_deploy.pub` (clé publique)

### 2.2 Ajouter la clé publique au VPS

```bash
cat ~/.ssh/github_deploy.pub
```

Copier le contenu, puis sur le VPS :

```bash
ssh mmi23c14@45.155.171.55
nano ~/.ssh/authorized_keys
# Coller la clé publique sur une nouvelle ligne
```

### 2.3 Configurer les secrets GitHub

1. Aller sur GitHub : **Settings** → **Secrets and variables** → **Actions**
2. Cliquer sur **New repository secret**
3. Ajouter ces 3 secrets :

| Nom | Valeur |
|-----|--------|
| `VPS_HOST` | `45.155.171.55` |
| `VPS_USER` | `mmi23c14` |
| `VPS_SSH_KEY` | Contenu de `~/.ssh/github_deploy` (la clé PRIVÉE) |

Pour obtenir la clé privée :
```bash
cat ~/.ssh/github_deploy
```

Copier TOUT le contenu (y compris `-----BEGIN OPENSSH PRIVATE KEY-----` et `-----END OPENSSH PRIVATE KEY-----`)

## 🚀 Étape 3 : Premier déploiement

### 3.1 Pousser la branche production

```bash
git add .
git commit -m "Configure deployment workflow"
git push origin production
```

### 3.2 Vérifier le déploiement

1. Aller sur GitHub → **Actions**
2. Vérifier que le workflow "Deploy to VPS" s'exécute
3. Surveiller les logs

## ✅ Étape 4 : Test

Tester l'API :

```bash
curl https://votre-domaine.com/api/unity/ping
```

Devrait retourner :
```json
{"status":"ok","message":"Unity API is running"}
```

## 🔄 Utilisation quotidienne

Maintenant, **à chaque push sur la branche `production`**, le déploiement se fera automatiquement !

```bash
# Développer sur main
git checkout main
# ... faire vos modifications ...
git add .
git commit -m "Nouvelle fonctionnalité"
git push origin main

# Quand prêt à déployer en production
git checkout production
git merge main
git push origin production  # 🚀 Déploiement automatique !
```

## 🛠️ Commandes utiles

### Déploiement manuel (si besoin)

```bash
ssh mmi23c14@45.155.171.55
cd /home/mmi23c14/public_html/overload-back
bash deploy.sh
```

### Voir les logs

```bash
ssh mmi23c14@45.155.171.55
cd /home/mmi23c14/public_html/overload-back
tail -f var/log/prod.log
```

### Rollback en cas de problème

```bash
ssh mmi23c14@45.155.171.55
cd /home/mmi23c14/public_html/overload-back
git log --oneline -10  # Voir les derniers commits
git reset --hard COMMIT_HASH  # Revenir à un commit précédent
bash deploy.sh
```

## 🔒 Sécurité

- ✅ Ne JAMAIS committer `.env.local` avec les vraies valeurs
- ✅ Garder les clés SSH privées en sécurité
- ✅ Utiliser HTTPS en production
- ✅ Limiter les accès SSH par IP si possible

## 🆘 Troubleshooting

### Erreur de permissions
```bash
ssh mmi23c14@45.155.171.55
chmod -R 755 /home/mmi23c14/public_html/overload-back
chmod -R 777 /home/mmi23c14/public_html/overload-back/var
```

### Erreur composer
```bash
ssh mmi23c14@45.155.171.55
cd /home/mmi23c14/public_html/overload-back
composer install --no-dev --optimize-autoloader
```

### Cache corrompu
```bash
ssh mmi23c14@45.155.171.55
cd /home/mmi23c14/public_html/overload-back
rm -rf var/cache/*
php bin/console cache:clear --env=prod
```
