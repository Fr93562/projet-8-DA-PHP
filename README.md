# To do list app

## Résumé 

![todolist](https://nopanic.fr/wp-content/uploads/2017/01/todolist-1.jpg)

### Sommaire 

*
*
*
*
*

-----------------

### Information du projet

* Développeur: Francois M. 	
* Contexte: Développeur d'application - Openclassrooms	
* Date: 20/02/20
* Version: 1.0
* Etat du projet: en cours

-----------------

### Résumé du projet


Ce projet est réalisé dans le cadre de la formation de développeur d'application PHP / Symfony chez Openclassrooms. Il s'agit d'une application Symfony développée dans le cadre du projet 8.

Le but de cete application est de permettre à ses utilisateurs d'utiliser une to do list. Elle permet à ses utilisateurs de pouvoir gérer leurs listes des choses à faire. Ils ont la possibilité de clore les tâches une fois terminées.


## Détails du projet

### Langages

* Symfony 5.09 / PHP 7.x
* HTML 5 / CSS 3
* Boostrap

### Bundles utilisés

* PHPunit

-----------------

### Installation du projet


* Installer le projet dans un dossier
* Récupérer les bundles présents dans composer.json avec la commande composer
* Modifier les éléments de .env pour qu'il corresponde à la base de données utilisé
* Run l'application

## Navigation du site

Ce site est principalement constitué de 3 pages:

* La page d'accueil du site: accessible à tous

* User / inscription: accessible à tous
* Users / connexion: accessible à tous
* Users / édition: accessible aux utilisateurs authentifiés
* Users / list des users: accesible aux utilisateurs avec le rôle administrateur

* Tasks / liste des tasks: accessible à tous
* Task / liste des tasks terminées: accesible à tous
* Task / création: accessible aux utilisateurs authentifiés
* Task / édition: accessible aux utilisateurs authentifiés
* Task / suppression: accessible aux utilisateurs authentifiés


## Connexion

### Système d'authentification

Le projet To do app utilise le firewall de Symfony comme système d'authentification. Chaque page demandée passe par le firewall qui décide de filtrer en fonction de l'authentification. Un système de session est mis en place à partir de la connexion.


#### Paramètres

Les paramètres de la sécurité et du firewall sont gérés par le fichier ``` security.yaml ``` qui est situé dans le dossier ``` config/bin/ ```.

* ``` algorithm: auto ``` : donne la possibilité à Symfony de choisir l'algorithme le plus adapté

* ``` provider: ``` : objet qui sera authentifié, on y détermine l'entité et la propriété qui servira à la connexion

* ```firewall ``` : détermine les fichiers et paths nécessaires à l'authentification, la connexion et la déconnexion

#### Controller chargé de l'authentification

Le ``` SecurityController ```qui se situe dans controller est un fichier php important. Ce sont ses paths qui seront appelées pour la connexion / déconnexion avec les formulaires et les templates associés.

* ``` loginAction ``` : méthode qui va appeler la page de connexion avec le bon template. Si le formulaire est validé, le firewall intercepte la requête et fait appelle au fichier php chargé de géré l'authentification (voir plus bas).

* ``` logout ``` : méthode dont le code n'est jamais éxécuté. La requête est interceptée par le firewall qui termine la session de connexion et renvoie vers une autre page.

#### Gestion de l'authentification

Le  ``` LoginFormAuthentificator ``` qui se situe dans Security est un fichier php nécessaire au fonctionnement de l'authentification. C'est cette classe qui génère la session de connexion et la détruit.

* ``` supports ``` : renvoie un booléen en fonction des données et requêtes envoyées. Si la route appelée est ``` app_login``` en méthode ``` POST ``` la classe lance l'authentification.

* ``` getCredentials ``` : la méthode va analyser la requête, extraire les information du formulaire et lancer la session.

* ``` getUser ``` : la méthode va utiliser les données précédemments récupérées por chercher un user et vont renvoyer l'user obtenu.

* ``` checkCredentials ``` : vérifie le mot de passe en base qui sera décodé

* ``` getPassword  ``` : récupère le mot de passe extrait du formulaire

* ``` onAuthenticiationSuccess ``` : utilisé à chaque requête, vérifie que le token corresponds bien à un utilisateur connecté

* ``` getLoginUrl  ```: renvoie la path de ``` app_login ```  


#### Exemple de connexion sur le site

écrit à la fin

## References


### Mises à jour