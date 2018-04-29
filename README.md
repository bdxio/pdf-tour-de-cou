# BDX.IO PDF Tours de cou

Ce petit programme permet de générer le fichier PDF des tours des participants.  

* :bangbang: Les premières éxécutions peuvent être longues
* :bangbang: Codé rapidement, merci d'être indulgent ... ;)
* :bangbang: L'utilisation s'appuie sur docker.

## Principe de fonctionnement

A partir d'une sauvegarde **CSV** de la liste des inscrits.

> La liste des inscrits correspond à l'export CSV de la feuille *"Liste inscrits"* du google doc (https://docs.google.com/spreadsheets/d/1gCxI_mRmSHszuP-hYQ_2vXSIY-faPpjORbLY80G2awQ/edit#gid=1938063635)[Inscrits bdxio 2017]

Et des images de badge vierges 

> *badge-etudiant.jpg*,*badge-orga.jpg*, *badge-participant.jpg*, *badge-speaker.jpg*, *badge-vierge.jpg*.

Le script télécharge les images des logos des sociétés des inscrits (colonne H, logo société), les sauvegardes dans le répertoire `/tmp` du conteneur docker. (Note: pour ne pas les téléchargées à chaque fois, le script réalise un md5 de l'url, et si le fichier `md5(url).extension` existe alors on ne télécharge pas l'image)

Et crée les fichiers PDF (50 badges par fichier, au format A5), dans le même répertoire `/tmp` du conteneur.

## Utilisation

1. il faut *build* l'image a l'aide du `Dockerfile` à la racine du projet.
```
    docker build -t bdxio/tour-de-cou .
```
2. lancer l'image 
```
docker run -d -p 8080:80 $PWD:/app --name bdxio-tour-de-cou bdxio/tour-de-cou
```
3. Lancer le navigateur http://localhost:8080
4. Récupérer le contenu du répertoire `/tmp` (je ne l'avais pas monté dans le conteneur pour des histoires de droits d'écriture ...)

```
docker cp bdxio-tour-de-cou:/tmp ./tmp
```



