# Budget App

Application web de gestion de budget et d'épargne.

## Stack technique
- Backend : Laravel 10 (PHP 8.2)
- Frontend : Vue 2 + Inertia.js
- Base de données : SQLite
- Serveur : Windows Server + IIS

## Fonctionnalités
- Authentification (Login / Register)
- Dashboard avec graphiques
- CRUD Transactions (revenus / dépenses)
- CRUD Catégories avec couleurs
- Objectifs d'épargne avec barre de progression

## Déploiement
1. git clone + composer install
2. Copier public/build/ depuis le PC de build
3. Configurer .env (APP_URL, APP_KEY)
4. php artisan migrate
5. Configurer IIS → pointer vers /public
