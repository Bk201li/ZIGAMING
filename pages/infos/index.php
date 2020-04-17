<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    include "../../partials/php/head.php";
    ?>
</head>

<body>
    <!-- HEADER -->
    <?php
    if (isset($_SESSION['id']))
    {
        include "../../partials/php/headerCo.php";
    }
    else
    {
        include "../../partials/php/header.php";
    }
    ?>

    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="contener">
            <p>
                Projet Web - B1 Ynov Info 2020
                
                
                Nom du groupe : Bkash
                
                Composition du groupe :
                Llorens Clément / Lafond Nicolas
                
                Choix du projet : (ZIGAMING)
                
                Description : Site de vente de jeux vidéos (matériel ou dématérialisé) entre particulier.
                
                Choix des technologies et outils utilisées
                HTML, CSS, BootStrap, Axentix, PHP,  Javascript, MySql, Git, Trello, Discord, Visual Studio Code, Laragon
                
                
                    Fonctionnalités : 
                
                
                Les utilisateurs peuvent se créer un compte et se connecter. Ils pourront compléter et modifier leur profil ou se déconnecter.
                Chaque profil possède :
                ▪ un pseudo ▪ un email ▪ une adresse ▪ une photo de profil ▪ une description (3 difficultés)(MVP)
                
                Chaque membre peut poster. Chaque annonce possède :
                ▪ un titre ▪ une description ▪ une photo ▪ un prix ▪ l'état ▪ la console
                (3 difficultés)(MVP)
                
                Les utilisateurs pourront modifier et supprimer leurs annonces.
                (3 difficultés)(MVP)
                
                Tous les visiteurs peuvent consulter les annonces sans être connectés
                (1 difficulté)(MVP)
                
                Système de recherche : tous les visiteurs peuvent filtrer les résultats par :
                ▪ le type de jeu ▪ Nom
                (6 difficultés)(MVP)
                
                Formulaire de contact : Envoie de mail au support
                (2 points)(MVP)
                
                Système de messagerie : un utilisateur connecté peut contacter un annonceur directement sur le site grâce à une messagerie interne.                              
                (6 difficultés)(MVP)
                
                Confirmation par mail : Chaque création de compte enverras un mail de confirmation par mail à l’utilisateur avec un code aléatoire à 
                rentrer dans le site pour confirmer son compte.                       
                (~ difficultés)
                
                Se souvenir de moi : Possibilité de cocher une case se souvenir de moi dans le formulaire de connexion pour ne pas avoir à entrer de 
                nouveau ses identifiants à chaque visite du site                         
                (~ difficultés)
                
                Mot de passe oublié : Système de réinitialisation de mot de passe en cas d’oubli, envois d’un mail avec un code de réinitialisation à 
                entrer sur le site pour changer de mot de passe.                              
                (~ difficultés)
                
                Header adaptatif : Contenu du header qui s'adapte selon si on est connecté ou pas.
                (~ difficultés)
                
                Page de vente : Lorsque qu'on clique sur une annonce, une page de vente s'affiche avec l'article et son contenu ainsi que le vendeur 
                de l'article et ses informations, il est possible d'afficher le profil du vendeur ou de le contacter.
                (~ difficultés)
                
                Blog informations : Une page blog information sur le site avec du contenu texte optimisé SEO.
                (~ difficultés)
                
                Slideshow : Un slideshow automatique dans l’accueil qui défile plusieurs images d’annonces de jeu toutes les x secondes.
                (~ difficultés)
                
                Sécurité : Site protégé contre les failles XSS, et contre les injections SQL.
                Toutes les champs où l’utilisateur peut entrer du texte sont protégé contre ces deux attaques.                              
                (~ difficultés)

                Gestion d’erreurs : Un système de gestion d’erreurs permettant d’afficher une erreur différente pour chaque action erronée de 
                l’utilisateur ( Création d’un compte avec une adresse mail déjà existante, mot de passe non correspondant, mail inexistant, image de 
                mauvais format, image trop lourde, chaîne de caractères trop longues etc...) Couvre la casi-totalité des erreurs possibles.                  
                (~ difficultés)
            </p>
        </div>
    </div>

    <!-- FOOTER -->
    <?php
    include "../../partials/php/footer.php";
    ?>

    <!-- FONT AWESOME KIT -->
    <script src="https://kit.fontawesome.com/e6c2645393.js" crossorigin="anonymous"></script>
</body>

</html>