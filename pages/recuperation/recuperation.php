<?php
session_start();

include_once('../../partials/php/bdd.php');

if (isset($_GET['section'])) {
    $section = htmlspecialchars($_GET['section']);
} else {
    $section = "";
}

if (isset($_POST['recup_submit'], $_POST['recup_mail'])) {
    if (!empty($_POST['recup_mail'])) {
        $recup_mail = htmlspecialchars($_POST['recup_mail']);
        if (filter_var($recup_mail, FILTER_VALIDATE_EMAIL)) {
            $mailexist = $bdd->prepare('SELECT id,pseudo FROM membres WHERE mail = ?');
            $mailexist->execute(array($recup_mail));
            $mailexist_count = $mailexist->rowCount();
            if ($mailexist_count == 1) {
                $pseudo = $mailexist->fetch();
                $pseudo = $pseudo['pseudo'];

                $_SESSION['recup_mail'] = $recup_mail;
                $recup_code = "";
                for ($i = 0; $i < 8; $i++) {
                    $recup_code .= mt_rand(0, 9);
                }
                $mail_recup_exist = $bdd->prepare('SELECT id FROM recuperation WHERE mail = ?');
                $mail_recup_exist->execute(array($recup_mail));
                $mail_recup_exist = $mail_recup_exist->rowCount();
                if ($mail_recup_exist == 1) {
                    $recup_insert = $bdd->prepare('UPDATE recuperation SET code = ? WHERE mail = ?');
                    $recup_insert->execute(array($recup_code, $recup_mail));
                } else {
                    $recup_insert = $bdd->prepare('INSERT INTO recuperation(mail,code) VALUES (?, ?)');
                    $recup_insert->execute(array($recup_mail, $recup_code));
                }

                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"ZIgaming.com"<llorens.31600@gmail.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                $message = '
                <html>
                <head>
                  <title>Récupération de mot de passe - ZIgaming.com</title>
                  <meta charset="utf-8" />
                </head>
                <body>
                  <font color="#303030";>
                    <div align="center">
                      <table width="600px">
                        <tr>
                          <td>
                            
                            <div align="center">Bonjour <b>' . $pseudo . '</b>,</div>
                            Voici votre code de récupération: <b>' . $recup_code . '</b>
                            A bientôt sur <a href="http://www.zigaming.fr/">ZIgaming.com</a> !
                            
                          </td>
                        </tr>
                        <tr>
                          <td align="center">
                            <font size="2">
                              Ceci est un email automatique, merci de ne pas y répondre
                            </font>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </font>
                </body>
                </html>
                ';
                mail($recup_mail, "Récupération de mot de passe - ZIgaming.com", $message, $header);
                header("Location:http://www.zigaming.fr/pages/recuperation/index.php?section=code");
            } else {
                $error = "Cette adresse mail n'est pas enregistrée";
            }
        } else {
            $error = "Adresse mail invalide";
        }
    } else {
        $error = "Veuillez entrer votre adresse mail";
    }
}

if (isset($_POST['verif_submit'], $_POST['verif_code'])) {
    if (!empty($_POST['verif_code'])) {
        $verif_code = htmlspecialchars($_POST['verif_code']);
        $verif_req = $bdd->prepare('SELECT id FROM recuperation WHERE mail = ? AND code = ?');
        $verif_req->execute(array($_SESSION['recup_mail'], $verif_code));
        $verif_req = $verif_req->rowCount();
        if ($verif_req == 1) {
            $up_req = $bdd->prepare('UPDATE recuperation SET confirme = 1 WHERE mail = ?');
            $up_req->execute(array($_SESSION['recup_mail']));
            header('Location:http://www.zigaming.fr/pages/recuperation/index.php?section=changemdp');
        } else {
            $error = "Code invalide";
        }
    } else {
        $error = "Veuillez entrer votre code de confirmation";
    }
}

if (isset($_POST['change_submit'])) {
    if (isset($_POST['change_mdp'], $_POST['change_mdpc'])) {
        $verif_confirme = $bdd->prepare('SELECT confirme FROM recuperation WHERE mail = ?');
        $verif_confirme->execute(array($_SESSION['recup_mail']));
        $verif_confirme = $verif_confirme->fetch();
        $verif_confirme = $verif_confirme['confirme'];
        if ($verif_confirme == 1) {
            $mdp = htmlspecialchars($_POST['change_mdp']);
            $mdpc = htmlspecialchars($_POST['change_mdpc']);
            if (!empty($mdp) and !empty($mdpc)) {
                if ($mdp == $mdpc) {
                    $hashedpass = password_hash($mdp, PASSWORD_DEFAULT);
                    $ins_mdp = $bdd->prepare('UPDATE membres SET mot_de_passe = ? WHERE mail = ?');
                    $ins_mdp->execute(array($hashedpass, $_SESSION['recup_mail']));
                    $del_req = $bdd->prepare('DELETE FROM recuperation WHERE mail = ?');
                    $del_req->execute(array($_SESSION['recup_mail']));
                    header('Location:https://www.zigaming.fr/pages/login/');
                } else {
                    $error = "Vos mots de passes ne correspondent pas";
                }
            } else {
                $error = "Veuillez remplir tous les champs";
            }
        } else {
            $error = "Veuillez valider votre mail grâce au code de vérification qui vous a été envoyé par mail";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}
