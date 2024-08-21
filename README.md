# Blog

![Symfony](https://img.shields.io/badge/Symfony-black?logo=symfony)

Bienvenue dans mon blog !

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [PHP 8.0+](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)
- [Git](https://git-scm.com/)

## Installation

Le blog est accessible à l'url suivante : https://blog-symfony.mcastel.fr/

Suivez ces étapes pour installer et configurer le projet sur votre machine locale :

1. **Clonez le repository :**

   ```bash
   git clone https://github.com/Maxcastel/blog-symfony.git
   cd blog-symfony
   ```
   
2. **Installez les dépendances PHP :**

    ```bash
    composer install
    ```

3. **Créez la base de données :**

   - Modifiez le fichier `.env` pour configurer les informations de connexion à votre base de données :
     
     Pour mysql : 
  
     ```bash
     DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4"
     ```
     
     Assurez-vous de remplacer les valeurs par celles correspondant à votre configuration :

     `app` par l'utilisateur de votre base de données  
     `!ChangeMe!` par le mot de passe de votre base de données  
     `app` par le nom de la base de données (par exemple 'blog')
     
   - Créez la base de données :  

       ```bash
       php bin/console doctrine:database:create
       ```
       
4. **Creez les tables de la base de données**

   - Creez le fichier de migration :
     
     ```bash
     php bin/console make:migration
     ```
     
   - Executez la migration pour créer les tables :
     
     ```bash
     php bin/console doctrine:migration:migrate
     ```

5. **Remplir la base de données :**

    Pour remplir la base de données, il faut charger les fixtures.

   Chargez toutes les fixtures :

    ```bash
    php bin/console doctrine:fixtures:load --append
    ```

    Les fixtures contiennent :

   - un fichier **UserFixtures** avec 4 utilisateurs : 2 admin et 2 user. L'identifiant de connexion est l'email dans setEmail et le mot est le deuxième argument de hashPassword dans setPassword. Pour les admin, le mot de passe est "admin", et pour les user, le mot de passe est "user".
       
      ⚠️ **Attention:** Veillez à bien changer le mot de passe des admin et user.
     
   - un fichier **ArticleFixtures** contenant les articles
   - un fichier **CommentFixtures** contenant les commentaires des articles

6. **Démarrez le serveur Symfony :**

    ```bash
    symfony server:start
    ```

    Accédez à l'application via http://localhost:8000
