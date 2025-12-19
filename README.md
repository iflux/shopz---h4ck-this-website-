# 🛒 Shopz - Laboratoire E-Commerce Vulnérable

Une application de commerce électronique délibérément vulnérable pour l'apprentissage de la sécurité web et des tests d'intrusion (pentest).

## ⚠️ Avertissement

**Cette application est intentionnellement vulnérable. NE LA DÉPLOYEZ PAS sur un réseau public ou un environnement de production.**

Shopz est conçu pour :
* Apprendre la sécurité des applications web.
* Pratiquer les techniques de tests d'intrusion.
* Comprendre les vulnérabilités courantes (OWASP Top 10).
* Chasser des drapeaux (flags) en mode CTF.

---

## 🚀 Démarrage Rapide

### Prérequis
* Docker & Docker Compose
* Au moins 2 Go de RAM disponible
* Ports 80, 21, 22, 3306, 8080, 8888 disponibles

### Installation

git clone [https://github.com/votreutilisateur/shopz.git](https://github.com/votreutilisateur/shopz.git)
cd shopz
docker-compose up -d --build

Service	URL / Protocole	Description
Boutique Shopz	http://localhost	Site e-commerce vulnérable
Panneau Admin	http://localhost:8080	Tableau de bord administrateur
Suivi des Flags	http://localhost:8888	Votre progression
FTP	ftp://localhost:21	Serveur de fichiers
SSH	ssh://localhost:22	Accès shell
MySQL	localhost:3306	Base de données

🎯 Objectif
Trouvez les 40 drapeaux (flags) cachés dans l'application. Les flags suivent le format : FLAG{exemple_de_flag_ici}

Soumettez les flags trouvés sur http://localhost:8888 pour suivre votre progression.

📚 Catégories de Vulnérabilités
Catégorie Flags Difficulté Reconnaissance & Énumération
8 ⭐ Facile Attaques par Injection
5 ⭐⭐ Moyen Auth & Session5
5 ⭐⭐ Moyen Contrôle d'Accès Défaillant
6 ⭐ FacileCross-Site Scripting (XSS)
3 ⭐⭐ Moyen Vulnérabilités de Fichiers
4 ⭐⭐ Moyen Logique Métier
3 ⭐ Facile Autres Vulnérabilités
3 ⭐⭐ Moyen Élévation de Privilèges
3 ⭐⭐⭐ Difficile


🔧 Dépannage
Réinitialiser le lab : docker-compose down -v && docker-compose up -d --build

Voir les logs : docker-compose logs -f

Reset progression : Bouton "Reset" sur http://localhost:8888

📁 Structure du Projet
Plaintext
shopz/
├── docker-compose.yml
├── shopz-app/          # Application PHP
│   ├── Dockerfile
│   ├── index.php
│   └── admin/
├── tracker/            # Dashboard Python/Flask
│   ├── Dockerfile
│   └── app.py
├── db/                 # Scripts SQL
│   └── init.sql
└── README.md


📜 Licence
Ce projet est destiné à un usage éducatif uniquement.

Bon hacking ! 🎉
