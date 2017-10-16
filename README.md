# YouTube Channel Gallery - Joomla! Module  #

## Overview
YouTube Channel Gallery est un module pour Joomla! 3.7. Il permet d'afficher une galerie vidéo responsive à partir d'une chaîne YouTube existante n'importe où sur votre site Joomla!.

* Fonctionne avec l'API YouTube.
* Est complètement responsive.
* Simple à paramétrer.
* Possède un template léger.

## Fonctionnement

### Installation
   1. Téléchargez le fichier zip du dossier "/dist" et utilisez l'installateur intégré de votre site Joomla!.  
   2. Créez autant de modules que nécessaire.  
   3. Configurez les modules avec vos informations.  
   4. Et c'est tout !

### Configuration
Pour configurer un module, vous devez saisir 3 informations.
   1. Clé d'API: Demandez à Google (litteralement, c'est eux qui fournissent la clé). Si vous avez besoin d'aide pour ce processus, jetez un coup d'oeil à [ce tutoriel](https://developers.google.com/youtube/v3/getting-started).  
   2. L'ID de la chaîne : Regardez l'url de la chaîne ciblée.  
   ```
      Exemple : https://www.youtube.com/channel/[the id]  
   ```  
   3. *Optionnel -* Nombre de vidéos affichées : Par défaut à 50.


## Crédits
J'ai utilisé le plugin de Nicola Franchini, [VenoBox](https://github.com/nicolafranchini/VenoBox), pour afficher la lecture des vidéos dans des lightbox.