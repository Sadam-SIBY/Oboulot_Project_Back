# O'Boulot : installation du projet back

## 1. Cloner le repos
   
- Copier le lien ssh
- Exécuter la commande git clone depuis le terminal dans le chemin souhaité
- Ouvrir son IDE (VS Code)
- Se mettre sur la branche dev avec la commande suivante :

```
git checkout dev
```

 ## 2. Installation des composants Symfony

 - A la racine du projet, lancer la commande suivante :
   
```
composer install
```

## 3. Configuration du .env

- A la racine du projet se trouve le fichier .env 
- Il va falloir configurer le lien avec la base de donnée souhaité dans les lignes ci-dessous :

```
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/oboulot?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/oboulot?serverVersion=10.11.6-MariaDB&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/oboulot?serverVersion=16&charset=utf8"
```
- Dé-commenter la ligne correspondant au SGBD utiliser et modifier les éléments suivants :
  - A la place de "app" mettre son nom d'utilisateur
  - A la place de "!ChangeMe!" mettre son mot de passe
  - le nom de la base de donnée sera oboulot
  - Attention à bien vérifier la version utilisée

## 4. Création de la base de donnée

- A la racine du projet, lancer la commande suivante :

```
php bin/console doctrine:database:create
```
- On peut vérifier si la base de donnée a bien été créer sur son interface graphique (Adminer par exemple)

## 5. Récupération des données

- Dans le dossier "docs" se trouve un ficher "data.sql" comportant des données de test
- Importer ce fichier dans sa base de donnée créée

## 6. Démarrer son serveur local pour accéder aux données

- A la racine du projet, lancer par exemple la commande suivante :

```
php -S 0.0.0.0:8080 -t public
```
