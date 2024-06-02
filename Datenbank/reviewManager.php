<?php

require_once("../Datenbank/reviewClass.php");

class ReviewManager
{


    private $collectionAllReview = array();
    private $datenbank;
    private $review;
    private $artworki;

    public function __construct($db)
    {
        $this->datenbank = $db;
        $this->getAllReview();
        $this->review = Review::getDefaultReview();
    }

    private function getAllReview()
    {
        try {
            $this->datenbank->connect();
            $anfrage = "SELECT * FROM reviews";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->execute();
            $this->collectionAllReview = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }
    }

    public function getReviewByID($reviewId)
    {
        try {
            $this->datenbank->connect();
            $anfrage = "SELECT * FROM reviews WHERE ReviewId = :reviewId";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->bindParam(':reviewId', $reviewId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }
    }

    public function getReviewsForArtwork($id)
    {
        $this->artworki = Artwork::getDefaultArtwork();
        $results = $this->getReviewsByArtworksID($id);

        if ($results === null) {
            throw new Exception('The ReviewId is not available');
        }

        foreach ($results as $result) {
            $tempReview = $this->getReviewByID($result);
            $this->artworki->setReviewsForArtwork($tempReview);
        }

        return $this->artworki->getReviewsForArtwork();
    }

    private function getReviewsByArtworksID($artworksID)
    {
        try {
            $this->datenbank->connect();
            $anfrage = "SELECT re.ReviewId 
                        FROM Artworks a
                        JOIN reviews re ON a.ArtWorkId = re.ArtWorkId
                        WHERE a.ArtWorkId = :artworksID";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->bindParam(':artworksID', $artworksID);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_column($results, 'ReviewId');
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }
    }

    public function displayReview($ArtWorkId)
    {
        $reviews = $this->getReviewsByArtworksID($ArtWorkId);

        foreach ($reviews as $reviewId) {
            $reviewData = $this->getReviewByID($reviewId);
            if ($reviewData) {
                $review = Review::fromState($reviewData);
                $review->outputReview();
            }
        }
    }

    public function displayTopReview()
    {
        $topreviews = $this->getRecentReviews();

        foreach ($topreviews as $topReview) {
            $this->review = Review::fromState($topReview);
            $this->review->outputReview();
        }
    }

    private function getRecentReviews()
    {
        try {
            $this->datenbank->connect();
            $anfrage = "SELECT ReviewId, CustomerId, ArtWorkId, Rating, Comment, ReviewDate 
                        FROM reviews 
                        ORDER BY ReviewDate DESC LIMIT 3";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }
    }
    public function addComment($ArtworkId, $customerID, $commentText, $rating)
{
    try {
        $this->datenbank->connect();
        $anfrage = "INSERT INTO reviews (ArtWorkId, CustomerId, Comment, Rating, ReviewDate) VALUES (:ArtWorkId, :customerID, :comment, :Rating, NOW())";
        $stmt = $this->datenbank->prepareStatement($anfrage);
        $stmt->bindParam(':ArtWorkId', $ArtworkId);
        $stmt->bindParam(':customerID', $customerID);
        $stmt->bindParam(':comment', $commentText);
        $stmt->bindParam(':Rating', $rating);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Fehler: " . $e->getMessage();
    } finally {
        $this->datenbank->close();
    }
}
}
