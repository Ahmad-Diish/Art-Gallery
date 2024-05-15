<?php
require_once("userClass.php");
class Artist
{

    private $customerID;
    private $firstName;
    private $lastName;
    private $address;
    private $city;
    private $region;
    private $country;
    private $postal;
    private $phone;
    private $email;
    private $datenbank;

    public function __construct($customerID, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email)
    {
        // Initialisierung der Eigenschaften...
        $this->artistID = $customerID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->nationality = $address;
        $this->yearOfBirth = $city;
        $this->yearOfDeath = $region;
        $this->details = $country;
        $this->artistLink = $postal;
        $this->artistLink = $phone;
        $this->artistLink = $email;

    }
}