<?php
namespace App\Entities;

class Lecteur
{
    private $id_lecteur;
    private $email;
    private $pseudo;
    private $mdp;
    private $nom;
    private $prenom;
    private $livres_empruntes;
    private $admin;

    /**
     * Get the value of id_lecteur
     */ 
    public function getId_lecteur()
    {
        return $this->id_lecteur;
    }

    /**
     * Set the value of id_lecteur
     *
     * @return  self
     */ 
    public function setId_lecteur($id_lecteur)
    {
        $this->id_lecteur = $id_lecteur;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of pseudo
     */ 
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @return  self
     */ 
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of mdp
     */ 
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set the value of mdp
     *
     * @return  self
     */ 
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of livres_empruntes
     */ 
    public function getLivres_empruntes()
    {
        return $this->livres_empruntes;
    }

    /**
     * Set the value of livres_empruntes
     *
     * @return  self
     */ 
    public function setLivres_empruntes($livres_empruntes)
    {
        $this->livres_empruntes = $livres_empruntes;

        return $this;
    }

    /**
     * Get the value of admin
     */ 
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the value of admin
     *
     * @return  self
     */ 
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }
}
?>