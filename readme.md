# Fonctionnalités
Ce plugin a pour objectif de fournir des redirection plus 'intelligentes' que ce que peuvent faire les URL de base de GLPI. L'objectif n'est pas d'ajouter des fonctionnalités à GLPI à proprement parler, mais d'en rendre l'utilisation plus naturelle et d'améliorer son confort d'usage, surtout pour des utilisateur occasionnels qui n'en maitrisent pas les subitilités et sont parfois déroutés par son comportement par défaut.
Pour l'instant, on a trois grandes fonctionnalités qui tournent principalement autour de la gestion de tickets, mais les principes sont assez facilement extrapolables à d'autres objets :

## Possibilité de régler à la volée le 'contexte' dans lequel on regarde un ticket.
Par contexte, j'entend le profil et l'entités. L'objectif est de corriger un comportement aggaçant de GLPI pour les utilisateurs ayant plusieurs profils n'ayant pas tous les mêmes droits : quand je clique sur un lien vers un ticket, c'est mon profil par défaut qui est sélectionné, ce qui fait que si mon profil par défaut n'a pas les droits sur ce ticket, alors que j'ai un autre profil qui les a, GLPI m'envoie promener alors que dans l'absolu, j'ai les droits. (idem pour les entités).
Le principe est le suivant : je peux définir des règles qui permette que le profil soit choisi en fonction du type de lien, de l'entité, de mon rôle sur le ticket et/ou du statut de celui-ci. Par exemple, si j'ai un profil 'technicien', qui m'autorise à traiter les tickets qui me sont attribués, et un profil 'observateur' qui me permet de voir tous les tickets, mais pas de les modifier, je utiliser les deux règles suivantes : 'si mon rôle est 'attribué à', choisir le profil technicien', et 'dans tous les autres cas, je vais choisir le rôle observateur'. Le système de règles fait qu'on peut, si on le souhaite, définir des comportements assez fins, pour peu qu'on se donne le temps de paramétrer tous les cas possibles.
De plus, si jamais le ticket n'est pas visible avec l'entité par défaut sélecionnée après le changement de profil, il élargit autant que possible la vision, en sélectionnant pour les entités 'voir tous'. Notez que si le paramétrage est maladroit, ça peut ne pas être suffisant : le plugin n'est pas intelligent au point de tester si tel ou tel profil offre la vision sur le processus du ticket. Par exemple une règle dit que pour l'entité XXX on sélectionne le profil YYY, mais que le profil YYY ne nous donne aucun droit sur cette entité, on aura quand même l'erreur disant qu'on n'a pas les droits. 

## Possibilité de 'protéger les liens contre l'authentification'
Si je veux informer un collègue d'un ticket, le moyen le plus naturel et de copier-coller l'URL de mon navigateur quand je vois le ticket et de lui envoyer par mail/messagerie instantanée. Or s'il n'est pas déjà authentifié dans GLPI, quand il clique dessus, ça ne marche pas; il sera redirigé vers l'authentification, et se retrouvera sur la page d'accueil. Pas très pratique.
Pour contourner ça, ce plugin implémente une fonction qui permet de changer l'URL avant d'être redirigé vers la page d'authentification de façon à la transformer en une url avec un 'redirect=blablabla', qui a le bon goût de survivre à l'authentification. Défaut, ça nécessite de modifier un peu le code du coeur de GLPI pour remplacer les redirections 'simples' par cette fonction.
De plus, plutôt que d'utiliser les 'redirect=nomobjet_id_forcetab' classiques (ceux qu'on trouve dans les mails de notification), la redirection est faite vers le plugin lui-même, de façon à ce que les redirections intelligentes telle celle décrite ci-dessus s'appliquent (pour l'instant, seule celle sur le ticket est définie, mais si on en fait d'autres pour d'autres objets, elle pourront s'appliquer de la même façon).

## Possibilité de rediriger vers la page de création des tickets avec des champs préréglés
L'idée est que dans certaines situations, on voudrait bien faire un lien qui emmène directement dans GLPI, sur la page de création des tickets, avec un type de ticket et une catégorie déjà sélectionnée. Par exemple, on pourrait vouloir mettre dans un catalogue de service un lien qui emmène directement là où il faut faire les demandes pour tel ou tel service, ou bien si on fait régulièrement des demandes qui se ressemble, on mettrait bien un lien dans les favoris de son navigateur.
Ce plugin permet de définir des liens qui font exactement ça. En suivant le format décrit dans la doc, on peut faire en sorte de présélectionner le profil et/ou l'entité et/ou le type de ticket et/ou la catégorie du ticket, avant d'être redirigé vers la page de création du ticket.
Deux inconvéniants :
* le format, sans être compliqué, n'est pas totalement trivial. Il faut être capable d'identifier les id des objets visés, ce qui est assez facile pour quelqu'un qui connait bien l'outil, mais moins pour un utilisateur occasionnel.
* ça encourage à faire des liens depuis l'extérieur de GLPI qui référencent des id d'objets internes à GLPI, ce qui fait que si on supprime des catégories (ou pire, si on les recycle pour autre chose), il faudra bien penser à changer tous les liens externes, ce qui n'est pas nécessairement évident.

# Syntaxe et documentation
## Redirection vers un objet arbitraire en passant si nécessaire par la redirection intelligente
syntaxe de l'URL : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_id_type$$$forcetab où
* id = id du ticket
* type = type de l'objet (ticket, computer...)
* forcetab = valeur du forcetab souhaité (sélection par défaut d'un onglet particulier), où un éventuel _ est remplacé par $$, par exemple Ticket$2 pour la solution, Document$$Item$1 pour les documents joints...

Par exemple pour un ticket :
* http://glpi0848.localhost/front/ticket.form.php?id=2016021801&forcetab=Document_Item$1 est l'URL standard (celle qu'on a dans la barre d'adresse)
* http://glpi0848.localhost/index.php?redirect=ticket_2016021801_Document_Item$1 est l'URL de redirection de GLPI natif (celle qu'on va trouver par exemple dans les mails de notifiation)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021801_ticket$$$Document$$Item$1 est l'URL à utiliser pour le plugin SmartRedirect

Le fait de passer par la redirection intelligent qui change le profil à la volée ne nécessite pas de changer le lien. ça sera automatique si l'utilisateur a activé le mécanisme.

## Redirection vers la création d'un ticket avec champs présélectionnés
syntaxe : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p(a)e(b)t(c)c(d), où :
* (a) = id du profil (profil par défaut si ignoré)
* (b) = id de l'entité (entité par défaut si ignoré)
* (c) = 1 pour incident, 2 pour demande (valeur par défaut du gabarit si ignoré)
* (d) = id de la catégorie (valeur par défaut du gabarit si ignoré)
Sachant que tous ces champs sont facultatifs, sous réserve de retirer la lettre associée. Par contre, régler une catégorie impose de régler le type.
Vous vous demandez à quoi sert le 1? il est indispensable, car sinon le mécanisme de redirection de GLPI refuse de passer la main à mon plugin.

Par exemple : 
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8e5t2c9 pour un ticket de demande avec le profil n° 8, dans l'entité 5, catégorie 9
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1e5t2c9 pour un ticket au même endroit, mais avec le profil par défaut
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1t1c9 pour un ticket toujours dans la même catégorie, mais sans définir le type (en l'absence de gabarit qui le force, ça sera un incident)
* etc...

# Installation et configuration
## Installation
Il s'agit pour l'essentiel d'un plugin classique : on le télécharge, on l'ajoute dans le dossier plugin, et on va l'installer dans l'interface de GLPI.
Par contre, pour profiter de la fonctionnalité qui permet de ne pas perdre l'objet visé par un lien quand on doit passer par l'authentification, il faut légèrement modifier le coeur de GLPI : remplacez tous les `Html::redirect($CFG_GLPI["root_doc"] . "/index.php");` dans les fonctions en checkQuelquechose de Session.class.php par `PluginSmartredirectGobject::redirectSmartly();`
En 0.84.8, ça fait environ 5 lignes à changer.

## Configuration
La configuration se base sur le plugin ConfigManager, donc gère les héritages de config et personnalisations. On a deux niveaux de configuration : globale, et personnalisation par utilisateurs.
Niveau configurations, on a une option, et un jeu de règles :
* option : activer ou non le changement de profil à la volée quand on accède à un ticket (par défaut non, réglable de façon globale, surchargeable par l'utilisateur)
* règles : règle de choix du profil en fonction des caractéristiques du ticket visé (par défaut aucune règle, réglable de façon globale, avec possibilitié d'ajouter des règles personnelles). Seule la première règle applicable s'applique (les suivantes seront ignorées), et si aucune règle n'est applicable lorsqu'on clique sur le lien, le profil sélectionné reste le profil par défaut.
La configuration des règles nécessite une bonne connaissance de GLPI, et n'est donc pas très accessible à un utilisateur occasionnel. Telles qu'elles ont été conçues, il est prévu que la configuration générale faite par l'administrateur suffise à la majorité des utilisateurs, et que seuls quelques utilisateurs ayant des rôles particulièrs aient besoin de la surcharger.

# Liens de test
Les exemples sont données avec mon environnement de test. A adapter en fonction de l'état de votre base bien sûr.
## Possibilité de régler à la volée le 'contexte' dans lequel on regarde un ticket.
Test sur les rôles
* Demandeur => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2015043002_ticket$$$
* Observateur => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021701_ticket$$$
* Attribué à => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2015043007_ticket$$$
* Valideur => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$
* No role => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2015043008_ticket$$$

Test sur les rôles doublés:
* D&R => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2015043004_ticket$$$
* D&O => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2015043003_ticket$$$
* O&R => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2015043006_ticket$$$

Test sur le statut :
* Nouveau => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021801_ticket$$$
* En cours attribué => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021802_ticket$$$
* En cours planifié => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021803_ticket$$$
* En attente => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021804_ticket$$$
* Résolu => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021805_ticket$$$
* Clos => http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021806_ticket$$$

Test sur entités :
* Sur Root : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$
* Sur entité 3 : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021801_ticket$$$

Test sur le type de lien :
* Vers suivis : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$TicketFollowup$1
* Vers Tâches : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$TicketTask$1
* Vers solution : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$Ticket$2
* Vers validation : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$TicketValidation$1
* Vers document : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$Document$$Item$1
* Vers stats : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$Ticket$4
* Vers satisfaction : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_3_ticket$$$Ticket$3

## Réécriture des liens simples en lien smart-redirect
Sans forcetab :
* Ticket nouveau norole : http://glpi0848.localhost/front/ticket.form.php?id=2016021801
* Le même en smart : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021801_ticket$$$
* Computer : http://glpi0848.localhost/front/computer.form.php?id=1

Avec un forcetab sans _ sélectionné : 
* Ticket nouveau norole : http://glpi0848.localhost/front/ticket.form.php?id=2016021801&forcetab=Ticket$2
* Le même en smart : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021801_ticket$$$Ticket$2
* Computer : http://glpi0848.localhost/front/computer.form.php?id=1&forcetab=Ticket$1

Avec Document_Item$1 sélectionné : 
* Ticket nouveau norole : http://glpi0848.localhost/front/ticket.form.php?id=2016021801&forcetab=Document_Item$1
* Le même en smart : http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_gobject_2016021801_ticket$$$Document$$Item$1
* comupter : http://glpi0848.localhost/front/computer.form.php?id=1&forcetab=Document_Item$1

## Redirection vers la création de tickets
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8e5t2c9 pour un ticket de demande avec le profil n° 8, dans l'entité 5, catégorie 9 (profil helpdesk chez moi)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p4e5t1c9 pour un ticket au même endroit, mais de type incident et avec le profil 4 (profil tech)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p2e5t1c9 pour un profil n'ayant pas les droits de créer un ticket (on tombe sur un message indiquant qu'on n'a pas les droits
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8tc3 pour un lien mal foutu
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p148t1c3 pour un profil qui n'existe pas
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8e1t2c3 pour une catégorie incompatible avec l'entité (hd)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p4e1t2c3 pour une catégorie incompatible avec l'entité (admin)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8e2t2c4 pour une catégorie non visible en helpdesk
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8e2t1c3 pour une catégorie incompatible avec le type (hd)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p4e2t1c3 pour une catégorie incompatible avec le type (admin)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p17e2t2c3 pour un profil n'ayant pas les droits sur l'entité (hd)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p16e2t2c3 pour un profil n'ayant pas les droits sur l'entité (admin)
* http://glpi0848.localhost/index.php?redirect=plugin_smartredirect_create_1p8e5t0c9 avec un type bidon


















































