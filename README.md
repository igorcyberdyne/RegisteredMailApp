# RegisteredMailApp

**Auteur :** [@igorcyberdyne](https://github.com/igorcyberdyne)

**RegisteredMailApp** est une mini application console se connectant à l'[API AR24](https://developers.ar24.fr/doc/#introduction) afin d'effectuer les opérations suivantes :
- Création d'un utilisateur (https://developers.ar24.fr/doc/#post-create-user)
- Récupération des informations d'un utilisateur (https://developers.ar24.fr/doc/#get-get-user-info)
- Ajout d'une pièce jointe (https://developers.ar24.fr/doc/#post-upload-an-attachment)
- Envoi d'un courrier avec une pièce jointe - Regular Registered Mail ( https://developers.ar24.fr/doc/#post-send-a-simple-or-eidas-registered-mail)
- Récupération des informations d'un courrier (https://developers.ar24.fr/doc/#get-get-registered-mail-info)
- Déchiffrement du résultat des requêtes (https://developers.ar24.fr/doc/#api-encryption)

### Comment installer ?

> Télécharger le projet en [cliquant ici](https://github.com/igorcyberdyne/RegisteredMailApp.git)

OU exécuter la commande ci-dessous dans la console

    git clone https://github.com/igorcyberdyne/RegisteredMailApp.git

Une fois le projet installé, exécuter à la racine du projet la commande suivante

    composer install


### ----------------------------------- Projet installé ! ----------------------------------

Ceci est la liste des commandes disponibles.
Les 5 commandes suivantes correspondent respectivement à chacune des API mentionnées ci-dessus

    1) app:create-user
    2) app:user-attachment
    3) app:user-info
    4) app:user-mail-info
    5) app:user-send-mail

    6) app:user-list (affiche la liste des utilisateurs créés)

La commande ci-dessous va vous permettre d'afficher la liste des commandes et une description pour chacune d'elle

    php console

Pour exécuter une commande, à la racine du projet

    php console [command]

Exemple

    php console app:user-list

La commande ci-dessous va vous permettre d'afficher l'aide sur une commande précise

    php console help [command]

Exemple

    php console help app:user-list

### Remarques
- Le fichier `console` à la racine du projet, vous permet de modifier les clés d'accès à l'API
- Le stockage des données se font dans des fichiers local créés à la volée
- Les tests ne sont pas terminés
- Les tests peuvent être utilisés pour tester les implémentations car c'est beaucoup plus simple de changer les jeux de données