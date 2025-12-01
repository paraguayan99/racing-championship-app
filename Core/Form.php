<?php
namespace App\Core;

class Form
{
    // Attribut contenant le code du formulaire
    private $formElements;

    // Le getter pour lire le contenu de l'attribut $formElements
    public function getFormElements()
    {
        return $this->formElements;
    }

    // Méthode permettant d'ajouter un ou des attributs
    private function addAttributes(array $attributes): string
    {
        $att = "";
        // Chaque attribut est parcouru
        foreach ($attributes as $attribute => $value) {
            $att .= " $attribute=\"$value\"";
        }
        return $att;
    }

    // Méthode permettant de générer la balise ouvrante HTML du formulaire
    public function startForm(string $action = '#', string $method = "POST", array $attributes = []): self
    {
        // On commence la creation du formulaire avec l'ouverture de la balise form et ses attribututs action method
        $this->formElements = "<form action='$action' method='$method'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        return $this;
    }

    // Méthode permettant d'ajouter un label
    public function addLabel(string $for, string $text, array $attributes = []): self
    {
        // On ajoute la balise label et l'attribut for
        $this->formElements .= "<label for='$for'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        $this->formElements .= "$text</label>";
        return $this;
    }

    // Méthode permettant d'ajouter un champ
    public function addInput(string $type, string $name, array $attributes = []): self
    {
        // On ajoute la balise input et les attributs type name
        $this->formElements .= "<input type='$type' name='$name'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        return $this;
    }

    // Méthode permettant d'ajouter un champ textarea
    public function addTextarea(string $name, string $text = '', array $attributes = []): self
    {
        // On ajoute la balise textarea et l'attribut name
        $this->formElements .= "<textarea name='$name'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        $this->formElements .= "$text</textarea>";
        return $this;
    }

    // Méthode permettant d'ajouter un champ select
    // public function addSelect(string $name, array $options, array $attributes = []): self
    // {
    //     // On ajoute la balise select et l'attribut name
    //     $this->formElements .= "<select name='$name'";
    //     $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
    //     foreach ($options as $key => $value) {
    //         $this->formElements .= "<option value='$key'>$value</option>";
    //     }
    //     $this->formElements .= "</select>";
    //     return $this;
    // }

    public function addSelect(string $name, array $options, array $attributes = []): self
    {
        // Extraire la valeur sélectionnée si elle existe
        $selectedValue = $attributes['value'] ?? null;
        unset($attributes['value']); // on l'enlève pour ne pas mettre value dans <select>

        // On ajoute la balise select et l'attribut name
        $this->formElements .= "<select name='$name'";
        $this->formElements .= !empty($attributes) ? $this->addAttributes($attributes) . ">" : ">";

        foreach ($options as $key => $value) {
            $selected = ($key == $selectedValue) ? " selected" : "";
            $this->formElements .= "<option value='$key'$selected>$value</option>";
        }

        $this->formElements .= "</select>";
        return $this;
    }


    // Méthode permettant de fermer le formulaire
    public function endForm(): self
    {
        $this->formElements .= "</form>";
        return $this;
    }

    // Méthode permettant de tester les champs. Les paramètres représentant les valeurs en POST et le nom des champs
    public static function validatePost(array $post, array $fields): bool
    {
        // Chaque champ est parcouru
        foreach ($fields as $field) {
            // On teste si les champs sont vides ou non présents
            if (empty($post[$field]) || !isset($post[$field])) {
                return false;
            }
        }
        return true;
    }

    // Méthode permettant de tester les champs. Les paramètres représenant les valeurs en FILES et le nom des champs
    public static function validateFiles(array $files, array $fields): bool
    {
        // Chaque champ est parcouru
        foreach ($fields as $field) {
            //  On teste si les champs sont déclarés et sans erreur
            if (isset($files[$field]) && $files[$field]['error'] == 0) {
                return true;
            }
        }
        return false;
    }

    // Méthode qui ajoute un CSRF TOKEN dans les formulaires
    public function addCSRF(): self
{
    $token = \App\Core\Auth::csrfToken();
    $this->formElements .= "<input type='hidden' name='csrf_token' value='$token'>";
    return $this;
}

    // puis dans chaque formulaire : 
    // $form->startForm(...)
    //  ->addCSRF()
    //  ->addLabel(...)
}
?>