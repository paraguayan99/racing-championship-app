<?php
namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\Creation;
use App\Entities\Lecteur;
use \PDO;

class CreationModel extends DbConnect
{
    public function findAll()
    {
        $this->request = "SELECT * FROM livre";
        $result = $this->connection->query($this->request);
        $list = $result->fetchAll();
        return $list;
    }


    public function find(int $id)
    {
        $this->request = $this->connection->prepare("SELECT * FROM livre WHERE id_livre = :id_livre");
        $this->request->bindParam(":id_livre", $id);
        $this->request->execute();
        $creation = $this->request->fetch();
        return $creation;
    }


    public function create(Creation $creation)
    {
        $this->request = $this->connection->prepare("INSERT INTO livre VALUES (NULL, :titre, :auteur, NULL, NULL, NULL)");
        // Attaques XSS - récupère titre et auteur, protège des injections de scripts
        $this->request->bindValue(":titre", htmlspecialchars($creation->getTitre(), ENT_QUOTES));
        $this->request->bindValue(":auteur", htmlspecialchars($creation->getAuteur(), ENT_QUOTES));
        $this->executeTryCatch();
    }


    public function update(int $id, Creation $creation)
    {
        $this->request = $this->connection->prepare("UPDATE livre
                                                    SET titre = :titre, auteur = :auteur, date_emprunt = :date_emprunt, date_retour = :date_retour, id_lecteur = :id_lecteur
                                                    WHERE id_livre = :id_livre");
        $this->request->bindValue(":id_livre", $id);
        // Attaques XSS - récupère titre et auteur, protège des injections de scripts
        $this->request->bindValue(":titre", htmlspecialchars($creation->getTitre(), ENT_QUOTES));
        $this->request->bindValue(":auteur", htmlspecialchars($creation->getAuteur(), ENT_QUOTES));

        // Gestion date_emprunt : si 0 ou vide, on envoie NULL
        $dateEmprunt = $creation->getDate_emprunt();
        if ($dateEmprunt === '0' || empty($dateEmprunt)) {
            $dateEmprunt = null;
        } else {
            $dateEmprunt = htmlspecialchars($dateEmprunt, ENT_QUOTES);
        }
        $this->request->bindValue(":date_emprunt", $dateEmprunt, $dateEmprunt === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        // Gestion date_retour : si 0 ou vide, on envoie NULL
        $dateRetour = $creation->getDate_retour();
        if ($dateRetour === '0' || empty($dateRetour)) {
            $dateRetour = null;
        } else {
            $dateRetour = htmlspecialchars($dateRetour, ENT_QUOTES);
        }
        $this->request->bindValue(":date_retour", $dateRetour, $dateRetour === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        // Gestion id_lecteur : si 0 ou vide, on envoie NULL
        $idLecteur = $creation->getId_lecteur();
        if ($idLecteur === '0' || empty($idLecteur)) {
            $idLecteur = null;
        }
        
        $this->request->bindValue(":id_lecteur", $idLecteur, $idLecteur === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $this->executeTryCatch();
    }


    public function updateEmprunt(int $id, Creation $creation)
    {
        $this->request = $this->connection->prepare("UPDATE livre
                                                    SET date_emprunt = :date_emprunt, date_retour = :date_retour, id_lecteur = :id_lecteur
                                                    WHERE id_livre = :id_livre");
        $this->request->bindValue(":id_livre", $id);
        // Attaques XSS - récupère les dates, protège des injections de scripts
        $this->request->bindValue(":date_emprunt", htmlspecialchars($creation->getDate_emprunt(), ENT_QUOTES));
        $this->request->bindValue(":date_retour", htmlspecialchars($creation->getDate_retour(), ENT_QUOTES));
        $this->request->bindValue(":id_lecteur", $creation->getId_lecteur());
        $this->executeTryCatch();
    }


    public function verifierDateEmpruntExisteDeja($idLivre) 
    {
        // Empêche toute tentative de forçage via la methode GET
        // Fonction qui teste si une date d'emprunt est déjà renseignée
        // Si c'est le cas, une erreur se produit dans CreationController -> updateEmprunt($id) et n'actualise donc pas l'emprunt
        $sql = "SELECT date_emprunt FROM livre WHERE id_livre = :id_livre";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id_livre' => $idLivre]);
        return $stmt->fetchColumn();   
    }


    public function verifierLimiteEmprunts(int $idLecteur, int $limite = 3): bool
    {
        // Au clic sur chaque demande d'emprunt
        // Fonction qui teste le nombre d'emprunts de cet id_lecteur
        // Si il a emprunté 3 livres, une erreur se produit dans CreationController -> updateEmprunt($id) et affiche le message de limite d'emprunt
        $sql = "SELECT COUNT(*) AS nb_emprunts FROM livre WHERE id_lecteur = :id_lecteur";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id_lecteur', $idLecteur, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nbEmprunts = (int)$result['nb_emprunts'];

        return $nbEmprunts >= $limite;
    }


    public function delete(int $id)
    {
        $this->request = $this->connection->prepare("DELETE FROM livre WHERE id_livre = :id_livre");
        $this->request->bindParam(":id_livre", $id);
        $this->executeTryCatch();
    }


    private function executeTryCatch()
    {
        try {
            $this->request->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        // Ferme le curseur, permettant à la requête d’être de nouveau exécutée
        $this->request->closeCursor();
    }


    public function createLecteur(Lecteur $creation)
    {
        $this->request = $this->connection->prepare("INSERT INTO lecteur VALUES (NULL, :email, :pseudo, :mdp, :nom, :prenom, NULL)");
        // Attaques XSS - récupère le formulaire et protège des injections de scripts
        $this->request->bindValue(":email", htmlspecialchars($creation->getEmail(), ENT_QUOTES));
        $this->request->bindValue(":pseudo", htmlspecialchars($creation->getPseudo(), ENT_QUOTES));
        $this->request->bindValue(":mdp", htmlspecialchars($creation->getMdp(), ENT_QUOTES));
        $this->request->bindValue(":nom", htmlspecialchars($creation->getNom(), ENT_QUOTES));
        $this->request->bindValue(":prenom", htmlspecialchars($creation->getPrenom(), ENT_QUOTES));
        $this->executeTryCatch();
    }

}
?>