<?php
namespace App\Entities;

class Creation
{
    private $id_livre;
    private $titre;
    private $auteur;
    private $date_emprunt;
    private $date_retour;
    private $id_lecteur;

    /**
     * Get the value of id_livre
     */ 
    public function getId_livre()
    {
        return $this->id_livre;
    }

    /**
     * Set the value of id_livre
     *
     * @return  self
     */ 
    public function setId_livre($id_livre)
    {
        $this->id_livre = $id_livre;

        return $this;
    }

    /**
     * Get the value of titre
     */ 
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @return  self
     */ 
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of auteur
     */ 
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set the value of auteur
     *
     * @return  self
     */ 
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get the value of date_emprunt
     */ 
    public function getDate_emprunt()
    {
        return $this->date_emprunt;
    }

    /**
     * Set the value of date_emprunt
     *
     * @return  self
     */ 
    public function setDate_emprunt($date_emprunt)
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    /**
     * Get the value of date_retour
     */ 
    public function getDate_retour()
    {
        return $this->date_retour;
    }

    /**
     * Set the value of date_retour
     *
     * @return  self
     */ 
    public function setDate_retour($date_retour)
    {
        $this->date_retour = $date_retour;

        return $this;
    }

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
}
?>