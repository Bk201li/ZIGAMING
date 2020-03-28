<?php
session_start();

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=zigaming;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if(isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
    {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $pseudolength = strlen($newpseudo);
        if($pseudolength <= 255)
        {
            $reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
            $reqpseudo->execute(array($newpseudo));
            $pseudoExist = $reqpseudo->rowCount();
            if($pseudoExist == 0)
            {
                $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
                $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
                header('Location: ../profil/index.php?id='.$_SESSION['id']);
            }
            else
            {
                $msg = "Pseudo déjà utilisée !";
            }
        }
    }

    if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
    {
        $newmail = htmlspecialchars($_POST['newmail']);
        if(filter_var($newmail, FILTER_VALIDATE_EMAIL))
        {
            $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
            $reqmail->execute(array($newmail));
            $mailExist = $reqmail->rowCount();
            if($mailExist == 0)
            {
                $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
                $insertmail->execute(array($newmail, $_SESSION['id']));
                header('Location: ../profil/index.php?id='.$_SESSION['id']);
            }
            else
            {
                $msg = "Adresse mail déjà utilisée !";
            }
        }
        else
        {
            $msg = "Votre addresse mail n'est pas valide";
        }
    }

    if(isset($_POST['descriptionProfil']) AND !empty($_POST['descriptionProfil']))
    {
        // $descriptionJeu = htmlspecialchars(addslashes($_POST["description"])); 
        $descriptionProfil = htmlspecialchars(addslashes($_POST['descriptionProfil']));
        $descriptionProfilLength = strlen($descriptionProfil);
        if($descriptionProfilLength <= 255)
        {
            $insertdescriptionProfil = $bdd->prepare("UPDATE membres SET descriptionProfil = ? WHERE id = ?");
            $insertdescriptionProfil->execute(array($descriptionProfil, $_SESSION['id']));
            header('Location: ../profil/index.php?id='.$_SESSION['id']);
        }
    }

    if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
    {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);

        if($mdp1 == $mdp2)
        {
            $insertmdp = $bdd->prepare("UPDATE membres SET mot_de_passe = ? WHERE id = ?");
            $insertmdp->execute(array($mdp1, $_SESSION['id']));
            header('Location: ../profil/index.php?id='.$_SESSION['id']);
        }
        else
        {
            $msg = "Vos mots de passe ne correspondent pas !";
        }
    }

    if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
    {
        $tailleMax = 2097152;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if($_FILES['avatar']['size'] <= $tailleMax)
        {
            $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
            if(in_array($extensionUpload, $extensionsValides))
            {
                $chemin = "../../assets/membres/avatars/".$_SESSION['id'].".".$extensionUpload;
                $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                if($resultat)
                {
                    $updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
                    $updateavatar->execute(array(
                        'avatar' => $_SESSION['id'].".".$extensionUpload,
                        'id' => $_SESSION['id']
                        ));
                    header('Location: ../profil/index.php?id='.$_SESSION['id']);
                }
                else
                {
                    $msg = "Erreur durant l'importation de votre avatar";
                }
            }
            else
            {
                $msg = "Votre avatar doit être au format jpg, jpeg, gif ou png";
            }
        }
        else
        {
            $msg = "Votre avatar ne doit pas dépasser 2Mo";
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    include "../../partials/php/head.php";
    ?>
</head>

<body>
    <!-- WRAPPER -->
    <div id="wrapper">
        <!-- PROFIL -->
        <div id="profil">
            <h2>Edition de mon profil</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <label>Pseudo :</label>
                <input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>"/><br><br>
                <label>Mail :</label>
                <input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>"/><br><br>
                <label>Description profil :</label>
                <textarea name="descriptionProfil" cols="" rows="" placeholder="Description profil"></textarea><br><br>
                <label>Mot de passe :</label>
                <input type="password" name="newmdp1" placeholder="Mot de passe"/><br><br>
                <label>Confirmation mot de passe :</label>
                <input type="password" name="newmdp2" placeholder="Confirmation mot de passe"/><br><br>
                <label>Avatar :</label>
                <input type="file" name="avatar"><br><br>
                <input type="submit" value="Mettre à jour">
            </form>
            <?php if(isset($msg)) { echo $msg; } ?>
        </div>
    </div>
    <!-- FONT AWESOME KIT -->
    <script src="https://kit.fontawesome.com/e6c2645393.js" crossorigin="anonymous"></script>
</body>

</html>
<?php
}
else
{
    header("Location: ../login/");
}
?>