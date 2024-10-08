# eventsphere
### Déployer une application en local 

1 . ** Préparer l'environnement qui va vous permettre de cloner ce projet. 
       C'est-dire que dans un repertoire choisi, créer un nouveau dossier qui va recevoir le projet.
       Et positionnez-vous dans ce dossier.  

2 . Cloner ce repository avec la commande : 
    git clone https://github.com/stshauke/eventsphere.git ;
    composer install;
    npm install;
    npm run dev;

3. Télécharger et installer WampServer

4 . Démarrer WampServer

5 . Accéder à phpMyAdmin en tapant (http://localhost/phpmyadmin/)
   Vous devriez voir une page de connexion. Par défaut, le nom d'utilisateur est "root" et il n'y a pas de mot de passe. Laissez le champ du mot de passe vide et appuyez sur Entrée pour vous connecter.

6 . Importer  le script de la base de données sur phpMyAdmin

7 . Démarrer le serveur de Symfony en tapant la commanda suivante : ** Symfony server:start  -d **

8 . Puis copier l'URL (https://127.0.0.1:8000) générer et coller le sur votre navigateur 

9 . Vous pouvez stopper le serveur de Symfony en tapant la commanda suivante : ** Symfony server :stop **
