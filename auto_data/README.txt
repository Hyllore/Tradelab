AUTO_DATA :

!!! Il faut imperativement que le fichier .js visé soit a la racine du dépot visé sinon la copie sera mauvaise !!!

Ce fichier sert à générer automatiquement à partir d'un flux de données csv, la base de données en dur pour les tests.

Pour utiliser l'auto_data, assurrez vous d'avoir une version de php 5 ou dans le cas de la version 7 pensez à lancer cette commande dans le shell :

sudo apt-get install php7.0-xml

Ceci afin d'avoir acces à la fonction utf8_encode();.

UTILISATION :

php auto_data.php [lien du flux csv] [chemin vers le fichier .js contenant la balise %DATA%] [chiffre minimum pour la generation aléatoire] [chiffre maximum pour la generation aléatoire] [séparateur des differentes données du flux]

Le programme va déterminer un chiffre aléatoire entre le minimum et le maximum. Afin d'obtenir un chiffre voulu, mettre le minimum et le maximun à la valeur de ce dernier.

RESULTAT :

Si tout fonctionne bien le programme devrait avoir generé le dossier prod/, resultant de la copie du dépôt envoyé en deuxième paramètre en ayant remplacé dans le fichier .js la variable %DATA% par des elements aleatoire provenant du fichier .csv .

à chaque réutilisation le dossier prod/ sera supprimé pour être recrée.

@droly
