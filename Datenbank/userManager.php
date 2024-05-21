<?php
require_once("datenbank.php");
require_once("userClass.php");

class UserManager {
    private $db;

    public function __construct() {
        $this->db = new datenbank();
        $this->db->connect();
    }

    public function addUser(User $user) {
        // SQL-Statements für beide Tabellen
        $sqlMaxID = "SELECT MAX(CustomerID) AS max_id FROM customers";
        $sqlCustomer = "INSERT INTO customers (CustomerID, firstname, lastname, address, postal, city, region, country, phone, email) 
                        VALUES (:customer_id, :firstname, :lastname, :address, :postal, :city, :region, :country, :phone, :email)";
        
        $sqlCustomerLogon = "INSERT INTO customerlogon (CustomerID, username, Pass) 
                             VALUES (:customer_id, :username, :Pass)";
    
        try {
            // Beginne Transaktion
            $this->db->beginTransaction();
    
            // Hole die aktuell höchste ID
            $stmtMaxID = $this->db->prepareStatement($sqlMaxID);
            $stmtMaxID->execute();
            $result = $stmtMaxID->fetch(PDO::FETCH_ASSOC);
            $maxID = $result['max_id'];
            $newID = $maxID + 1;

            // Füge Benutzername und Passwort in customerlogon-Tabelle ein
            $stmtCustomerLogon = $this->db->prepareStatement($sqlCustomerLogon);
            $stmtCustomerLogon->bindValue(':customer_id', $newID);
            $stmtCustomerLogon->bindValue(':username', $user->getUsername());
            $stmtCustomerLogon->bindValue(':Pass', $user->getPasswordHash());
            $stmtCustomerLogon->execute();

            // Füge Benutzerdaten in customers-Tabelle ein
            $stmtCustomer = $this->db->prepareStatement($sqlCustomer);
            $stmtCustomer->bindValue(':customer_id', $newID);
            $stmtCustomer->bindValue(':firstname', $user->getFirstname());
            $stmtCustomer->bindValue(':lastname', $user->getLastname());
            $stmtCustomer->bindValue(':address', $user->getAddress());
            $stmtCustomer->bindValue(':postal', $user->getPostal());
            $stmtCustomer->bindValue(':city', $user->getCity());
            $stmtCustomer->bindValue(':region', $user->getRegion());
            $stmtCustomer->bindValue(':country', $user->getCountry());
            $stmtCustomer->bindValue(':phone', $user->getPhone());
            $stmtCustomer->bindValue(':email', $user->getEmail());
            $stmtCustomer->execute();
    
            // Überprüfe, ob beide Einträge erfolgreich waren
            if ($stmtCustomer->rowCount() > 0 && $stmtCustomerLogon->rowCount() > 0) {
                // Commit Transaktion
                $this->db->commit();
                return true;
            } else {
                // Rollback Transaktion bei Misserfolg
                $this->db->rollBack();
                return false;
            }
        } catch (Exception $e) {
            // Bei Fehler Rollback durchführen und Fehlermeldung ausgeben
            $this->db->rollBack();
            error_log("Fehler beim Hinzufügen des Benutzers: " . $e->getMessage());
            return false;
        }
    } 

    public function emailExists($email) {
        try {
            // SQL-Statement für Abfrage
            $sql = "SELECT COUNT(Email) AS 'count' FROM `customers` WHERE Email = :email";
            
            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (Exception $e) {
            error_log("Fehler beim Überprüfen der E-Mail-Existenz: " . $e->getMessage());
            return false;
        }
    }

    public function usernameExists($username) {
        try {
            // SQL-Statement für Abfrage
            $sql = "SELECT COUNT(UserName) AS 'count' FROM `customerlogon` WHERE UserName = :username";
            
            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] > 0;
        } catch (Exception $e) {
            error_log("Fehler beim Überprüfen des Benutzernamens: " . $e->getMessage());
            return false;
        }
    }
}
?>




