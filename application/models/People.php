<?php

/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 05/10/2014
 * Time: 17:38
 */
class Model_People
{

    private $nom;
    private $prenom;
    private $type;
    private $company;
    private $logo;

    private $login;
    private $password;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $logo
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $logo
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }


    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }


    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    function __construct($nom, $prenom, $type, $company, $logo, $login="", $password="")
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->type = $type;
        $this->company = $company;
        $this->logo = $logo;
        $this->login = $login;
        $this->password = $password;

    }

    /**
     * Format Prenom for beeing printed
     *
     * @param $prenom
     * @return string
     */
    public function formatPrenom()
    {
        $prenom = $this->prenom;
        $prenom = ucwords(strtolower($prenom));
        if (strpos($prenom, '-')) {

            $splitted = explode('-', $prenom);

            array_walk($splitted, function (&$va) {
                $va = ucfirst(strtolower($va));


            });

            $prenom = join('-', $splitted);
        }
        $prenom = str_replace(array('Ô', 'Ï', 'Î'), array('ô','ï', 'î'), $prenom);
        return $prenom;
    }

    /**
     * Format Nom for beeing printed
     * @param $nom
     * @return string
     */
    public function formatNom()
    {
        $nom = $this->nom;
        $nom = str_replace(array("é", "è", "ç", "à", "ê", "ë", "ü", "ô"), array("e", "e", "c", "a", "e", "e", "u", "o"), $nom);
        return strtoupper($nom);
    }


    public function __toArray()
    {
        return array(
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "type" => $this->getType(),
            "login" => $this->getLogin(),
            "password" => $this->getPassword()
        );
    }


    public function getFullname()
    {
        return $this->nom . " " . $this->prenom;
    }

    public function formatCompany()
    {

        if (strlen($this->company) > 20) {
            $last_space = strrpos(substr($this->company, 0, 20), ' '); // find the last space within 35 characters

            return array(substr($this->company, 0, $last_space), substr($this->company, $last_space+1 ));
        }
        return array($this->company);


    }

    public function hasLogo()
    {
        if ($this->logo == '') {
            return false;
        }
        return true;
    }


}
