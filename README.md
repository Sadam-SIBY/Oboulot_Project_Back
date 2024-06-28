## Bienvenue sur le projet backend de Oboulot

Vous avez ici un guide d'installation du projet coté back (Développé avec le framework Symfony)
1. Cloner le repos : 
    - Copier le lien ssh
    - Exécuter la commande `git clone` depuis le terminal dans le chemin souhaité
    - Ouvrir son IDE (VS Code)

2. Installation des composants Symfony
  A la racine du projet, lancer la commande suivante :
`composer install`

1. Configuration du .env.local
    - A la racine du projet se trouve le fichier .env
Il va falloir configurer le lien avec la base de donnée souhaité dans les lignes ci-dessous :

    #DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

    #DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/oboulot?serverVersion=10.11.6-MariaDB&charset=utf8mb4"
    
    #DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/oboulot?serverVersion=16&charset=utf8"

    - Dé-commenter la ligne correspondant au SGBD utiliser et modifier les éléments suivants :
    - A la place de "app" mettre son nom d'utilisateur
    - A la place de "!ChangeMe!" mettre son mot de passe
    - le nom de la base de donnée sera oboulot
    - Attention à bien vérifier la version utilisée
     - ex : DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/oboulot?serverVersion=10.11.6-MariaDB&charset=utf8mb4
1. Création de la base de donnée
A la racine du projet, lancer la commande suivante :
`php bin/console doctrine:database:create`
On peut vérifier si la base de donnée a bien été créer sur son interface graphique (Adminer par exemple)
1. Récupération des données via les fixtures 
Lancer la commande :  `php bin/console doctrine:fixtures:load`

1. Démarrer son serveur local pour accéder aux données
A la racine du projet, lancer par exemple la commande suivante :
`php -S 0.0.0.0:8080 -t public`

Bonne navigation ! 