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


    function __construct($nom, $prenom, $type)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->type = $type;
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
        $nom = str_replace(array("é", "è", "ç", "à", "ê", "ë", "ü"), array("e", "e", "c", "a", "e", "e", "u"), $nom);
        return strtoupper($nom);
    }


    public function __toArray()
    {
        return array(
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "type" => $this->getType()
        );
    }


    public function getFullname(){
        return $this->nom." ".$this->prenom;
    }

} 