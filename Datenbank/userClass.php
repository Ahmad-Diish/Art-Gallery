<?php
class User {
    private $id;
    private $firstname;
    private $lastname;
    private $address;
    private $postal;
    private $city;
    private $region;
    private $country;
    private $phone;
    private $email;
    private $username;
    private $password;
    private $type;
    private $state;

    public function __construct($firstname, $lastname, $address, $postal, $city, $region, $country, $phone, $email, $username, $password, $type, $state, $id = null) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->postal = $postal;
        $this->city = $city;
        $this->region = $region;
        $this->country = $country;
        $this->phone = $phone;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->type = $type;
        $this->state = $state;
    }

    public function getId() { return $this->id; }
    public function getFirstname() { return $this->firstname; }
    public function getLastname() { return $this->lastname; }
    public function getAddress() { return $this->address; }
    public function getPostal() { return $this->postal; }
    public function getCity() { return $this->city; }
    public function getRegion() { return $this->region; }
    public function getCountry() { return $this->country; }
    public function getPhone() { return $this->phone; }
    public function getEmail() { return $this->email; }
    public function getUsername() { return $this->username; }
    public function getPasswordHash() { return $this->password; } // Methode um gehashtes Passwort zu bekommen
    public function getType() { return $this->type; }
    public function getState(){return $this->state; }

    public function setState($state) {
        $this->state = $state;
    }
}
?>
