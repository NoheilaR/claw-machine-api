#!/bin/bash

# Script de déploiement Symfony pour VPS
# Chemin : /home/mmi23c14/public_html/overload-back

set -e  # Arrêt si erreur

echo "🚀 Début du déploiement..."

# Variables
APP_DIR="/home/mmi23c14/public_html/overload-back"
BRANCH="production"

# Se déplacer dans le répertoire de l'application
cd $APP_DIR

echo "📥 Récupération des dernières modifications..."
git fetch origin $BRANCH
git reset --hard origin/$BRANCH

echo "📦 Installation des dépendances..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔧 Configuration de l'environnement..."
# Les variables .env.local doivent déjà exister sur le serveur
# On ne les écrase pas pour garder les secrets

echo "🗄️ Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "🧹 Nettoyage du cache..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "✅ Déploiement terminé avec succès !"
echo "🕐 $(date '+%Y-%m-%d %H:%M:%S')"
