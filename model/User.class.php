<?php

class User
{
    public $id;
    public $anrede;
    public $fname;
    public $name;
    public $adress;
    public $plz;
    public $ort;
    public $username;
    public $password;
    public $email;

    public function __construct($id, $anrede, $fname, $name, $adress, $plz, $ort, $username, $password, $email)
    {
        $this->id = $id;
        $this->anrede = $anrede;
        $this->fname = $fname;
        $this->name = $name;
        $this->adress = $adress;
        $this->plz = $plz;
        $this->ort = $ort;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of anrede
     */ 
    public function getAnrede()
    {
        return $this->anrede;
    }

    /**
     * Set the value of anrede
     *
     * @return  self
     */ 
    public function setAnrede($anrede)
    {
        $this->anrede = $anrede;

        return $this;
    }

    /**
     * Get the value of fname
     */ 
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set the value of fname
     *
     * @return  self
     */ 
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of adress
     */ 
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set the value of adress
     *
     * @return  self
     */ 
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get the value of plz
     */ 
    public function getPlz()
    {
        return $this->plz;
    }

    /**
     * Set the value of plz
     *
     * @return  self
     */ 
    public function setPlz($plz)
    {
        $this->plz = $plz;

        return $this;
    }

    /**
     * Get the value of ort
     */ 
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * Set the value of ort
     *
     * @return  self
     */ 
    public function setOrt($ort)
    {
        $this->ort = $ort;

        return $this;
    }

        /**
         * Get the value of username
         */ 
        public function getUsername()
        {
                return $this->username;
        }

        /**
         * Set the value of username
         *
         * @return  self
         */ 
        public function setUsername($username)
        {
                $this->username = $username;

                return $this;
        }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

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
}
