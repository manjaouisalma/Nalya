# 💎 Nalya — Boutique de bijoux en ligne

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-PDO-4479A1?logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)

**Nalya** est un site e-commerce de bijoux fantaisie, développé from scratch en **PHP natif**, **MySQL (PDO)** et **JavaScript Vanilla**, sans framework. Le projet propose un parcours d'achat complet : catalogue, panier, favoris, commande avec paiement à la livraison, espace client et administration.

> 🎓 Projet réalisé dans une démarche d'apprentissage, conçu pour tourner en local.

---

## ✨ Fonctionnalités

### 🛍️ Côté client
- Page d'accueil avec catégories et sélection de produits récents
- Catalogue avec **recherche**, **filtre par catégorie** et **tri** (nouveautés, prix croissant / décroissant)
- Fiche produit détaillée avec gestion du stock et choix de la quantité
- **Panier** et **favoris** sauvegardés dans le navigateur (`localStorage`)
- Inscription, connexion et déconnexion avec mots de passe hachés
- **Commande** avec paiement à la livraison et page de confirmation
- Espace client : profil, historique des commandes et détail de chaque commande
- Formulaire de contact

### 🔧 Côté administration
- Tableau de bord
- Gestion des produits (ajout, modification, upload d'images JPG / PNG / WEBP jusqu'à 2 Mo)
- Gestion des catégories
- Suivi et changement de statut des commandes
- Liste des clients
- Consultation des messages de contact

### 🔒 Sécurité
- Requêtes préparées **PDO** (protection contre les injections SQL)
- Échappement HTML des sorties (protection XSS)
- **Jetons CSRF** sur tous les formulaires POST
- Mots de passe hachés avec `password_hash()`
- Régénération de l'ID de session à la connexion
- Vérification du stock et des prix **côté serveur**, dans une **transaction** SQL avec verrouillage (`FOR UPDATE`)

---

## 🧰 Technologies

| Domaine | Outils |
|---|---|
| Front-end | HTML5, CSS3, JavaScript Vanilla |
| Back-end | PHP 8.1+ (natif) |
| Base de données | MySQL via PDO |
| Environnement local | XAMPP (Apache + MySQL) |

---

## 📁 Structure du projet

```
Nalya/
├── admin/            # Espace d'administration
├── api/              # Endpoints JSON (produits)
├── assets/           # CSS, JavaScript, images
├── config/           # Connexion à la base de données
├── database/         # Script SQL (nalya.sql)
├── includes/         # Header, footer, fonctions communes
├── index.php         # Accueil
├── products.php      # Catalogue
├── product-details.php
├── cart.php
├── checkout.php
├── order-confirmation.php
├── orders.php
├── order-details.php
├── favorites.php
├── account.php
├── login.php / register.php / logout.php
└── contact.php
```

---

## 🚀 Installation (Windows / XAMPP)

1. **Cloner ou télécharger** le projet dans `C:\xampp\htdocs\Nalya`
```bash
   git clone https://github.com/votre-username/Nalya.git
```
2. Ouvrir le panneau XAMPP et démarrer **Apache** et **MySQL**.
3. Ouvrir `http://localhost/phpmyadmin`.
4. **Importer** le fichier `database/nalya.sql` (onglet *Importer*).
5. Vérifier les paramètres dans `config/database.php` (par défaut : utilisateur `root`, sans mot de passe).
6. Ouvrir **http://localhost/Nalya/** 🎉

---

## 🔑 Compte administrateur (démo)

| Champ | Valeur |
|---|---|
| E-mail | `admin@bijoux.com` |
| Mot de passe | `admin123` |

Après connexion, l'administrateur est redirigé vers `admin/index.php`.

> ⚠️ Ces identifiants sont réservés à la **démonstration en local**. Ne les utilisez jamais tels quels sur un site public.

Pour promouvoir un autre compte en administrateur, exécutez dans phpMyAdmin :

```sql
UPDATE users SET role = 'admin' WHERE email = 'votre-email@example.com';
```

---

## ⚠️ Limites connues

- Le panier et les favoris utilisent `localStorage` : ils ne sont pas synchronisés avec MySQL ni fusionnés côté serveur après la connexion.
- Une annulation de commande depuis l'administration ne remet pas automatiquement le stock.
- Le projet nécessite une revue de sécurité et des tests avant toute mise en ligne.

---

## 🔮 Améliorations prévues

- Synchronisation des favoris et du panier avec la base de données
- Remise en stock automatique lors d'une annulation
- Paiement en ligne
- Notifications par e-mail

---

## 👩‍💻 Auteure

**Salma** — Développeuse Web Full Stack Junior  
🔗 [GitHub](https://github.com/manjaouisalma) · [LinkedIn](https://linkedin.com/in/salma-manjaoui)