<?php

require_once("../Datenbank/subjectClass.php");

class  SubjectManager
{
    private $collectionAllSubjects = array(); 
    private $datenbank; 
    private $subjecti; 

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

       
        foreach ($subjects as $subject) {
          
            if ($subject['SubjectId'] == $subjectId) {
               
                $subjectObj = Subject::fromState($subject);
                $subjectObj->outputSingleSubject();
                return; 
            }
        }

        
        echo "Subjekt mit der ID $subjectId wurde nicht gefunden.";
    }


    // Kunstwerke nach Subject-ID abrufen
    public function getArtworksBySubjectId($subjectId)
    {
        try {
           
            $this->datenbank->connect();

         
            $query = "SELECT *
            FROM artworksubjects, artworks
            WHERE artworksubjects.ArtworkID = artworks.ArtworkID
            AND artworksubjects.SubjectID = :subjectId";

            $statement = $this->datenbank->prepareStatement($query);
            $statement->bindParam(':subjectId', $subjectId);
            $statement->execute();

            
            $artworkData = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            $this->datenbank->close();

            return $artworkData;
        } catch (PDOException $e) {
            exit('Error retrieving artworks: ' . $e->getMessage());
        }
    }

     // Themen nach Artwork-ID abrufen (!!!!für Ahmad!!!!!)
    public function getSubjectByArtworksID($artworksID)
    {
        try {
           
            $this->datenbank->connect();

            
            $query = "SELECT subjects.SubjectID, subjects.SubjectName
            FROM artworksubjects
            INNER JOIN subjects ON artworksubjects.SubjectID = subjects.SubjectID
            WHERE artworksubjects.ArtworkID = :artworksID";

            $statement = $this->datenbank->prepareStatement($query);
            $statement->bindParam(':artworksID', $artworksID);
            $statement->execute();

            $subjectData = $statement->fetchAll(PDO::FETCH_ASSOC);

            $this->datenbank->close();

          
            return $subjectData;
        } catch (PDOException $e) {
    
            exit('Fehler beim Abrufen der Themen: ' . $e->getMessage());
        }
    }
   
}
