# BDX.IO PDF Tours de cou

Ce petit programme permet de générer le fichier PDF des tours des participants.  

> **NOTE** : Codé rapidement, merci d'être indulgent ... ;)

## Installation



### Dépendances

Le produit est basé sur les librairies suivantes :

 * **Zend Framework 1.12.9** (A télécharger et placer dans le répertoire *library*)
 * **asimlqt/php-google-spreadsheet-client : 2.2.x ** (dépendance [Composer](https://getcomposer.org/))
 * **google/apiclient : v1.0.6-beta ** (dépendance [Composer](https://getcomposer.org/))

### Vhost Apache

 Voici un exemple de vhost pour _Apache 2.2_

```
<VirtualHost *:80>
    DocumentRoot "PATH_TO_PUBLIC_FOLDER"
    # Penser a ajouter tour-de-cou.local.fr à votre fichier hosts
    ServerName tour-de-cou.local.fr

    # This should be omitted in the production environment
    SetEnv APPLICATION_ENV development

    <Directory "PATH_TO_PUBLIC_FOLDER">
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>

</VirtualHost>
```

### Droits

 Le script écrit des données sur le disque, il est important que le repertoire *PATH/public/* soit accéssible en écriture par apache.

## Configuration

### Données de travail

 Le script a besoin de données de travail :

 * Fond de page (différents en fonction du type de participant)
 * Font (Attention aux accents)
 * Clé privé pour accéder aux services Google

Toutes ces données sont déposées dans le répertoire *application/datas*

> **ATTENTION** la clé privée ne sera pas dans les sources ...

### Données Service Google (Spreadsheet)

 Dans le fichier ***application/models/Sheet.php*** il faut modifier les valeurs des constantes selon le besoin :

 * **GOOGLE_CLIENTID** : Le ClientId Google (récupéré à partir de la console Google)
 * **GOOGLE_ACCOUNT_EMAIL** : L'utilisateur Google (il faut lui partager les fichiers qu'il peut lire ...)
 * **GOOGLE_PRIVATEKEY**  : Le chemin d'accés à la clé privé (relative à **APPLICATION_PATH**)
 * **WORKBOOK_TITLE** : Le nom du fichier (Attention il faut que le classeur soit accéssible par l'utilisateur)
 * **SHEET_NAME**  : Le nom de la feuille de travail (onglet de la spreadsheet)

### Carton

Dans le fichier ***application/models/Carton.php***, il faut modifier les constantes suivantes :

 * **NAME_X** : Coordonnée X du Nom (en points)
 * **NAME_Y** : Coordonnée Y du Nom (en points)
 * **FONT_SIZE** : Taille de la police
 * **LINE_SPACING** : Taille de l'espacement entre les lignes
 * **ROTATE_DEG** : Nombre de degrés pour la rotation du texte
 * **TEXT_COLOR** : Couleur du texte (Hexadecimal : #111333)
 * **PAGE_SIZE_A6** : Dimensions de la pages A6 en points
 * **PAGE_SIZE_A4** : Dimensions de la pages A4 en points
 * **FONT_FACE** : Police du texte, fichier relatif à **APPLICATION_PATH**


## Todos :

 Bon il reste pas mal de choses à revoir ou améliorer, mais pour le moment, on laisse comme ça !

 * Externaliser toute la configuration
 * Variabiliser les noms des images / types de participants
 * Faire un truc plus joli
 * Ajouter la creation des etiquettes "bataille de speaker"

 
