GESSEH
======

G.E.S.S.E.H. est un acronyme qui signifie : **Gestionnaire d'Évaluation et de Sélection des Stages d'Étudiant Hospitalier**.

1) Contexte
-----------

Depuis les accords du [Processus de Bologne](http://fr.wikipedia.org/wiki/Processus_de_Bologne), la part est mise sur la qualité des enseignements fournis aux étudiants. Et dans ce cadre, se généralise les **évaluations des enseignements** par les étudiants. De la même manière, les **stages hospitaliers**, formations des étudiants en médecine, doivent être évalués.

De même, les stages hospitaliers occasionnent des **sessions de choix** souvent laborieuses et parfois précédées de **simulations officieuses** de la part des étudiants. Remplir cette fonction est donc la deuxième tâche de ce logiciel.

Ce logiciel a été développé pour le cursus médical (premier, deuxième et troisième cycle). Il peut éventuellement s'appliquer à d'autres cursus. N'hésitez pas à contacter l'équipe de développement à ce sujet.

A l'heure actuelle, il s'adresse aux **associations étudiantes** (externes comme internes) et aux **administrations des universités**.

2) Sous le capot
----------------

Ce logiciel web, écrit en PHP avec [Symfony](http://symfony.com), permet de simplifier et d'uniformiser les évaluations de leurs stages hospitaliers par les étudiants ainsi que de simuler des sessions de choix de stage pour une **mise en place facile et libre** ([licence GPLv3](https://www.gnu.org/licenses/gpl-3.0.en.html)) dans les différentes facultés de médecine ou associations d'étudiants en médecine de France.

C'est un logiciel libre, par conséquent vous pouvez l'utiliser, le copier, le modifier et le diffuser comme bon vous semble. Il est également gratuit.

Pour son installation, vous aurez besoin d'un serveur web (apache, lighttpd, ...) avec PHP5 et d'une base de données (MySQL ou PostgreSQL), formule classiquement proposée par tous les hébergeurs web.

3) Téléchargement et installation
---------------------------------

###Via une archive snapshot

1. Récupérer la dernière version sur [GitHub](https://github.com/CaraGk/gesseh/releases).
2. Extraire les fichiers (BZip ou 7zip) dans un répertoire.
3. Copier le fichier *app/config/parameters.yml.dist* vers *app/config/parameters.yml* et le modifier selon les besoins (connexion MySQL, etc).
4. Envoyer les fichiers via FTP (ou autre) dans le répertoire web du serveur.
5. Pour mettre à jour la base de donnée : *http://ipduserverweb/gesseh/web/update* ; la base de donnée est automatiquement créée et un formulaire demande à créer le premier utilisateur.
6. Les accès ultérieurs se font via l’URL : *http://ipduserverweb/gesseh/web/*.

Pour les mises à jour, il faut suivre les mêmes étapes sauf que le fichier *app/config/parameters.yml* est déjà configuré et qu’il n’y aura pas de création du premier utilisateur.

###Via les dépots Git

Il est conseillé, hors développeurs, d'utiliser une archive stable mais si vous souhaitez vraiment l'installer depuis Git, lancez les commandes suivantes :

    git clone http://github.com/CaraGk/gesseh.git gesseh
    cd gesseh

Copiez le fichier *parameters.yml.dist* sur *parameters.yml* et adaptez-le à vos besoins (connexion MySQL, etc).

    cp app/config/parameters.yml.dist app/config/parameters.yml
    ./composer.phar install

Installez la base de données :

    ./app/console doctrine:migrations:migrate

4) Pour en savoir plus
----------------------

Vous pouvez vous inscrire sur la [liste de diffusion](https://groups.google.com/forum/#!forum/gesseh-devel) : gesseh-devel@googlegroups.com.
