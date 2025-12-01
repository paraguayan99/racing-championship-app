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
    // private function addAttributes(array $attributes): string
    // {
    //     $att = "";
    //     // Chaque attribut est parcouru
    //     foreach ($attributes as $attribute => $value) {
    //         $att .= " $attribute=\"$value\"";
    //     }
    //     return $att;
    // }

    // Méthode permettant d'ajouter un ou des attributs
    private function addAttributes(array $attributes): string
    {
        $att = "";
        foreach ($attributes as $attribute => $value) {
            // Échapper nom/valeur d'attribut pour éviter injection
            $attrName = htmlspecialchars($attribute, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $attrValue = htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $att .= " {$attrName}=\"{$attrValue}\"";
        }
        return $att;
    }


    // Méthode permettant de générer la balise ouvrante HTML du formulaire
    public function startForm(string $action = '#', string $method = "POST", array $attributes = []): self
    {
        // On commence la creation du formulaire avec l'ouverture de la balise form et ses attribututs action method
        // AJOUT DE LA CLASSE CSS
        $this->formElements = "<form action='$action' method='$method' class='login-form'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        return $this;
    }

    // Méthode permettant d'ajouter un label
    public function addLabel(string $for, string $text, array $attributes = []): self
    {
        // On ajoute la balise label et l'attribut for
        // DEBUT DE LA DIV DE LA CLASSE CSS
        $this->formElements .= "<div class='form-group'><label for='$for'";
        $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
        $this->formElements .= "$text</label>";
        return $this;
    }

    // Méthode permettant d'ajouter un champ
    // public function addInput(string $type, string $name, array $attributes = []): self
    // {
    //     // On ajoute la balise input et les attributs type name
    //     $this->formElements .= "<input type='$type' name='$name'";
    //     $this->formElements .= isset($attributes) ? $this->addAttributes($attributes) . ">" : ">";
    //     return $this;
    // }

    // Méthode permettant d'ajouter un champ
    public function addInput(string $type, string $name, array $attributes = []): self
    {
        $typeEsc = htmlspecialchars($type, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $nameEsc = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // handle value attribute if provided
        $value = '';
        if (isset($attributes['value'])) {
            $value = ' value="' . htmlspecialchars((string)$attributes['value'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"';
            unset($attributes['value']);
        }

        $this->formElements .= "<input type='{$typeEsc}' name='{$nameEsc}'{$value}";
        $this->formElements .= !empty($attributes) ? $this->addAttributes($attributes) . "></div>" : "></div>";
        // FIN DE LA DIV DE LA CLASSE CSS </div>
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

    // Méthode permettant d'ajouter un champ select
    public function addSelect(string $name, array $options, array $attributes = []): self
    {
        // Extraire la valeur sélectionnée si elle existe
        $selectedValue = $attributes['value'] ?? null;
        unset($attributes['value']); // on l'enlève pour ne pas mettre value dans <select>

        // On ajoute la balise select et l'attribut name
        $this->formElements .= "<select name='$name'";
        $this->formElements .= !empty($attributes) ? $this->addAttributes($attributes) . ">" : ">";

        foreach ($options as $key => $value) {
            $keyEsc = htmlspecialchars((string)$key, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $valueEsc = htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $selected = ($key == $selectedValue) ? " selected" : "";
            $this->formElements .= "<option value='{$keyEsc}'{$selected}>{$valueEsc}</option>";
        }

        // foreach ($options as $key => $value) {
        //     $selected = ($key == $selectedValue) ? " selected" : "";
        //     $this->formElements .= "<option value='$key'$selected>$value</option>";
        // }

        $this->formElements .= "</select></div>";
        // FIN DE LA DIV DE LA CLASSE CSS </div>
        return $this;
    }

    // Méthode permettant d'ajouter le bouton soummission avec le CSS associé
    public function addSubmit($value = "Envoyer", $class = "btn")
    {
        $this->formElements .= '<div class="form-group">';
        $this->formElements .= '<button type="submit" class="'.$class.'">'.$value.'</button>';
        $this->formElements .= '</div>';

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