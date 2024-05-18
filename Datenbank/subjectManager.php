<?php

require_once("../Datenbank/subjectClass.php");

class  SubjectManager
{
    private $collectionAllSubjects = array(); // Sammlung aller Subjects
    private $datenbank; // Datenbankverbindung
    private $subjecti; // Aktuelles Subject

    private $artworki;


    // Konstruktor für die SubjectManager-Klasse
    public function __construct($datenbank)
    {
        $this->datenbank = $datenbank;
        $this->getAllSubject();
        $this->subjecti = Subject::getDefaultSubject();
    }


    // Alle Subjects aus der Datenbank abrufen
    private function getAllSubject() 
    {
        $this->datenbank->connect();
        try {
            $anfrage =
                "SELECT * FROM `subjects` ORDER BY SubjectName";

            $stmt = $this->datenbank->prepareStatement($anfrage);

            $stmt->execute();

            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }

        $this->collectionAllSubjects = $subjects;
    }

      // Alle Subjects auf der Website anzeigen "Subjects.php"
    public function displayAllSubjects()
    {
        $subjects = $this->collectionAllSubjects;

        foreach ($subjects as $subject) {

            $this->subjecti = Subject::fromState($subject);

            $this->subjecti->outputSubjects();
        }
    }


    // Ein einzelnes Subject nach ID anzeigen
    public function displaySubjectById($subjectId)
    {
        $subjects = $this->collectionAllSubjects;

        // Durchlaufen der Sammlung aller Subjekte
        foreach ($subjects as $subject) {
            // Überprüfen, ob die ID des aktuellen Subjekts mit der gesuchten ID übereinstimmt
            if ($subject['SubjectId'] == $subjectId) {
                // Subjekt aus dem Zustand erstellen und ausgeben
                $subjectObj = Subject::fromState($subject);
                $subjectObj->outputSingleSubject();
                return; // Wenn das Subjekt gefunden wurde, die Schleife beenden
            }
        }

        // Fehlermeldung, falls das Subjekt mit der angegebenen ID nicht gefunden wurde
        echo "Subjekt mit der ID $subjectId wurde nicht gefunden.";
    }


    // Kunstwerke nach Subject-ID abrufen
    public function getArtworksBySubjectId($subjectId)
    {
        try {
           
            $this->datenbank->connect();

           // SQL-Abfrage, um Kunstwerke für die bereitgestellte Subject-ID abzurufen
            $query = "SELECT *
            FROM artworksubjects, artworks
            WHERE artworksubjects.ArtworkID = artworks.ArtworkID
            AND artworksubjects.SubjectID = :subjectId";

            $statement = $this->datenbank->prepareStatement($query);
            $statement->bindParam(':subjectId', $subjectId);
            $statement->execute();

            // Fetch artworks related to the subject
            $artworkData = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Close the database connection
            $this->datenbank->close();

            // Return artwork data
            return $artworkData;
        } catch (PDOException $e) {
            // Handle exceptions (e.g., database connection error)
            exit('Error retrieving artworks: ' . $e->getMessage());
        }
    }

     // Themen nach Artwork-ID abrufen (!!!!für Ahmad!!!!!)
    public function getSubjectByArtworksID($artworksID)
    {
        try {
            // Verbindung zur Datenbank herstellen
            $this->datenbank->connect();

            // SQL-Abfrage, um das Thema für die bereitgestellte Artwork-ID abzurufen
            $query = "SELECT subjects.SubjectID, subjects.SubjectName
            FROM artworksubjects
            INNER JOIN subjects ON artworksubjects.SubjectID = subjects.SubjectID
            WHERE artworksubjects.ArtworkID = :artworksID";

            $statement = $this->datenbank->prepareStatement($query);
            $statement->bindParam(':artworksID', $artworksID);
            $statement->execute();

            // Themen für das Kunstwerk abrufen
            $subjectData = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Datenbankverbindung schließen
            $this->datenbank->close();

            // Themen-Daten zurückgeben
            return $subjectData;
        } catch (PDOException $e) {
            // Ausnahmen behandeln (z.B. Datenbankverbindungsfehler)
            exit('Fehler beim Abrufen der Themen: ' . $e->getMessage());
        }
    }
   
}
