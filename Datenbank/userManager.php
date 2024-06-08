<?php
require_once ("datenbank.php");
require_once ("userClass.php");

class UserManager
{
    private $db;

    public function __construct()
    {
        $this->db = new datenbank();
        $this->db->connect();
    }

    private function validateLogin($hashedPasswdFromDB, $plainPasswdFromUser)
    {
        $errors = null; // Array für Fehlermeldungen

        if (!password_verify($plainPasswdFromUser, $hashedPasswdFromDB)) {
            $errors = "Das eingegebene Passwort ist falsch.";
        }

        return $errors;
    }


    public function checkLogin($identifier, $password)
    {
        $result = array("error"=>"", "user"=> null);

        // SQL-Abfrage vorbereiten und ausführen, um Daten aus beiden Tabellen zu holen
        $sql = "select customerlogon.Pass as Password, customerlogon.UserName as UserName, customerlogon.CustomerID as CustomerID from customers, customerlogon where ((customerlogon.UserName = :username) OR (customers.Email = :email)) AND (customerlogon.CustomerID = customers.CustomerID); ";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->bindParam(":username", $identifier);
        $stmt->bindParam(":email", $identifier);
        $stmt->execute();
        $dbres = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dbres) {
            $result['error'] = "Kein Benutzer mit diesem Benutzernamen oder dieser E-Mail-Adresse gefunden.";
        } else {
            $result['error'] = $this->validateLogin($dbres["Password"], $password);
            if (!$result['error']) {
                $result['user'] = $this->getUserByUsername($dbres["UserName"]);
            }
        }

        return $result;
    }

    public function addUser(User $user)
    {
        // SQL-Statements für beide Tabellen
        $sqlMaxID = "SELECT MAX(CustomerID) AS max_id FROM customers";
        $sqlCustomer = "INSERT INTO customers (CustomerID, firstname, lastname, address, postal, city, region, country, phone, email) 
                        VALUES (:customer_id, :firstname, :lastname, :address, :postal, :city, :region, :country, :phone, :email)";

        $sqlCustomerLogon = "INSERT INTO customerlogon (CustomerID, username, Pass, DateJoined) 
                             VALUES (:customer_id, :username, :Pass, :DateJoined)";

        try {
            // Beginne Transaktion
            $this->db->beginTransaction();

            // Hole die aktuell höchste ID
            $stmtMaxID = $this->db->prepareStatement($sqlMaxID);
            $stmtMaxID->execute();
            $result = $stmtMaxID->fetch(PDO::FETCH_ASSOC);
            $maxID = $result['max_id'];
            $newID = $maxID + 1;

            // Füge Benutzername, Passwort und DateJoined in customerlogon-Tabelle ein
            $stmtCustomerLogon = $this->db->prepareStatement($sqlCustomerLogon);
            $stmtCustomerLogon->bindValue(':customer_id', $newID);
            $stmtCustomerLogon->bindValue(':username', $user->getUsername());
            $stmtCustomerLogon->bindValue(':Pass', $user->getPasswordHash());
            $stmtCustomerLogon->bindValue(':DateJoined', date('Y-m-d H:i:s'));
            $stmtCustomerLogon->execute();

            $stmtCustomer = $this->db->prepareStatement($sqlCustomer);
            $stmtCustomer->bindValue(':customer_id', $newID);
            $stmtCustomer->bindValue(':firstname', $user->getFirstname());
            $stmtCustomer->bindValue(':lastname', $user->getLastname());
            $stmtCustomer->bindValue(':address', $user->getAddress());
            $stmtCustomer->bindValue(':postal', $user->getPostal() ?: NULL);
            $stmtCustomer->bindValue(':city', $user->getCity());
            $stmtCustomer->bindValue(':region', $user->getRegion() ?: NULL);
            $stmtCustomer->bindValue(':country', $user->getCountry());
            $stmtCustomer->bindValue(':phone', $user->getPhone() ?: NULL);
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
            return false;
        }
    }

    public function emailExists($email)
    {
        try {
            // SQL-Statement für Abfrage
            $sql = "SELECT COUNT(Email) AS 'count' FROM `customers` WHERE Email = :email";

            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function usernameExists($username)
    {
        try {
            // SQL-Statement für Abfrage
            $sql = "SELECT COUNT(UserName) AS 'count' FROM `customerlogon` WHERE UserName = :username";

            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function phoneExists($phone)
    {
        try {
            // SQL-Statement für die Abfrage
            $sql = "SELECT COUNT(Phone) AS 'count' FROM `customers` WHERE Phone = :phone";

            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':phone', $phone);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUserByUsername($username)
    {
        try {
            // SQL-Statement für Abfrage
            $sql = "SELECT * FROM customers,customerlogon WHERE customerlogon.UserName = :username AND customerlogon.CustomerID = customers.CustomerID";

            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':username', $username);
            $stmt->execute();

            // Überprüfe, ob Ergebnisse vorhanden sind
            if ($stmt->rowCount() > 0) {
                // Fetch as associative array
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                return new User(
                    $userData['FirstName'],
                    $userData['LastName'],
                    $userData['Address'],
                    $userData['Postal'],
                    $userData['City'],
                    $userData['Region'],
                    $userData['Country'],
                    $userData['Phone'],
                    $userData['Email'],
                    $userData['UserName'],
                    $userData['Pass'],
                    $userData['CustomerID']
                );
            } else {
                // Benutzer mit dem angegebenen Benutzernamen wurde nicht gefunden
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateUser(User $user)
    {
        try {
            $sqlCustomer = "UPDATE customers SET FirstName = :firstname, LastName = :lastname, Address = :address, Postal = :postal, City = :city, Region = :region, Country = :country, Phone = :phone, Email = :email WHERE CustomerID = :id";
            $sqlCustomerLogon = "UPDATE customerlogon SET DateLastModified = :DateLastModified, Pass = :password WHERE CustomerID = :id";

            $this->db->beginTransaction();

            $stmtCustomer = $this->db->prepareStatement($sqlCustomer);
            $stmtCustomer->bindValue(':id', $user->getId());
            $stmtCustomer->bindValue(':firstname', $user->getFirstname());
            $stmtCustomer->bindValue(':lastname', $user->getLastname());
            $stmtCustomer->bindValue(':address', $user->getAddress());
            $stmtCustomer->bindValue(':postal', $user->getPostal());
            $stmtCustomer->bindValue(':city', $user->getCity());
            $stmtCustomer->bindValue(':region', $user->getRegion());
            $stmtCustomer->bindValue(':country', $user->getCountry());
            $stmtCustomer->bindValue(':phone', $user->getPhone());
            $stmtCustomer->bindValue(':email', $user->getEmail());

            $stmtCustomerLogon = $this->db->prepareStatement($sqlCustomerLogon);
            $stmtCustomerLogon->bindValue(':id', $user->getId());
            $stmtCustomerLogon->bindValue(':DateLastModified', date('Y-m-d H:i:s'));
            $stmtCustomerLogon->bindValue(':password', $user->getPasswordHash());

            $stmtCustomer->execute();
            $stmtCustomerLogon->execute();

            if ($stmtCustomer->rowCount() > 0 || $stmtCustomerLogon->rowCount() > 0) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getUserPasswordById($userId)
    {
        try {
            $sql = "SELECT Pass AS Password FROM customerlogon WHERE CustomerID = :id";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->bindValue(':id', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)["Password"];
        } catch (Exception $e) {
            return null;
        }
    }
}
?>
