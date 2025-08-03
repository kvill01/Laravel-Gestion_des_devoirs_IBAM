# Gestion des Devoirs IBAM

Système de gestion des devoirs pour l'Institiut Burkinabé des Arts et des Métiers IBAM.

## Fonctionnalités

- **Gestion des utilisateurs** : Admin, Enseignants, Surveillants, Étudiants
- **Planification des devoirs** : Création et programmation des examens
- **Gestion des salles** : Attribution automatique des salles
- **Surveillance** : Affectation des surveillants
- **QR Codes** : Génération automatique pour chaque devoir
- **Notifications** : Système de notifications intégré

## Installation

1. Cloner le projet :
```bash
git clone https://github.com/kvill01/Laravel-Gestion_des_devoirs_IBAM.git
cd Laravel-Gestion_des_devoirs_IBAM
```

2. Installer les dépendances :
```bash
composer install
npm install
```

3. Configuration :
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans `.env` :
```
DB_DATABASE=gestion_devoirs
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

5. Migrer la base de données :
```bash
php artisan migrate --seed
```

6. Lancer le serveur :
```bash
php artisan serve
```

## Technologies

- **Laravel 11**
- **MySQL**
- **Tailwind CSS**
- **QR Code Generator**

## Auteur

Développé par :
    Sawadogo Abdel Kaled, 
    Coulibaly San Wilfried, 
    Dargani Charlenne & 
    Kaboré Alida 
    
pour l'IBAM.