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

###Via les dépots Git

    git clone http://github.com/CaraGk/gesseh.git gesseh
    cd gesseh

Copiez le fichier *parameters.yml.dist* sur *parameters.yml* et adaptez-le à vos besoins (connexion MySQL, etc).

    cp app/config/parameters.yml.dist app/config/parameters.yml
    export SYMFONY_ENV=prod
    ./composer.phar install --no-dev --optimize-autoloader
    ./app/console doctrine:migrations:migrate
    ./app/console assetic:dump

Rendez-vous sur  `http://adresseserveurweb/web/firstuser` pour créer le compte administrateur et finaliser votre installation.

4) Pour en savoir plus
----------------------

Vous pouvez vous inscrire sur la [liste de diffusion](https://groups.google.com/forum/#!forum/gesseh-devel) : gesseh-devel@googlegroups.com.
