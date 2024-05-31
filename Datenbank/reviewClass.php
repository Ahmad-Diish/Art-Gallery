<?php

// Include necessary files
require_once("../Datenbank/datenbank.php");
//require_once("../Datenbank/reviewClass.php");
require_once("../Datenbank/reviewManager.php");

// Create a database connection
$datenbank = new datenbank();
$reviewManager = new ReviewManager($datenbank);
class Review
{
    private $reviewId;
    private $artWorkId;
    private $customerId;
    private $reviewDate;
    private $rating;
    private $comment;
    private $datenbank;

    public function __construct($reviewId, $artWorkId, $customerId, $reviewDate, $rating, $comment)
    {
        $this->reviewId = $reviewId;
        $this->artWorkId = $artWorkId;
        $this->customerId = $customerId;
        $this->reviewDate = $reviewDate;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->datenbank = new datenbank();
    }

    // Getter and Setter Methods
    public function setArtworkId($artworkId)
    {
        $this->artWorkId = $artworkId;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function getReviewId()
    {
        return $this->reviewId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getReviewDate()
    {
        return $this->reviewDate;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getComment()
    {
        return $this->comment;
    }

    // Static Methods
    public static function fromState(array $review): Review
    {
        $reviewId = $review["ReviewId"] ?? null;

        $artWorkId = $review["ArtWorkId"] ?? null;

        $customerId = $review["CustomerId"] ?? null;

        $reviewDate = $review["ReviewDate"] ?? null;

        $rating = $review["Rating"] ?? null;

        $comment = $review["Comment"] ?? null;

        return new self($reviewId, $artWorkId, $customerId, $reviewDate, $rating, $comment);
    }

    public static function getDefaultReview(): Review
    {
        return new self(-1, -1, -1, "", 0, "");
    }

    // Fetch customer details directly from the database
    private function fetchCustomerDetails($customerId)
    {
        try {
            $this->datenbank->connect();
            $anfrage = "SELECT FirstName, LastName FROM customers WHERE CustomerId = :customerId";
            $stmt = $this->datenbank->prepareStatement($anfrage);
            $stmt->bindParam(':customerId', $customerId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            return false;
        } finally {
            $this->datenbank->close();
        }
    }

    // Output the review with customer details
    public function outputReview()
    {
        $css = '
        <style>
        .review {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #fef3c7;
        }
        
        .review-author {
            color: #923f0e;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .review-date {
            color: #666;
            margin-bottom: 10px;
        }
        
        .review-comment {
            margin-bottom: 10px;
        }
        
        .rating .star {
            color: gray;
            cursor: pointer;
        }
        
        .rating .star.filled {
            color: gold;
        }
        
        .button_user_löschen {
            background-color: #ff6666;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .button_user_löschen:hover {
            background-color: #cc5555;
        }
        
        .modal-content {
            border-radius: 10px;
        }
        
        .buttons_Single_Artwork .button_style {
            background-color: #923f0e;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        .buttons_Single_Artwork .button_style:hover {
            background-color: #7b2f08;
        }
        
    
            
        </style>
        ';
            // Ausgabe des CSS
            echo $css;
            
        $reviewId = htmlspecialchars($this->getReviewId());
        $customer = $this->fetchCustomerDetails($this->getCustomerId());
        $customerName = $customer ? htmlspecialchars($customer['FirstName'] . " " . $customer['LastName']) : "Unknown Customer";
    
        $reviewDate = htmlspecialchars($this->getReviewDate());
        $rating = $this->getRating();
    
        $maxRating = 5;
    
        echo "<div class='review' id='review-$reviewId'>";
        echo "<p class='review-author'>Kommentator: $customerName</p>";
        echo "<p class='review-date'>Review Date: $reviewDate</p>";
        echo "<div class='rating'>";
        
        for ($i = 1; $i <= $maxRating; $i++) {
            echo $i <= $rating ? "<span class='star filled'>&#9733;</span>" : "<span class='star'>&#9733;</span>";
        }
    
        echo "</div>";
        echo '<p>'.$this->getComment().'</p>' ;
    
        // if (isset($_SESSION["user"])) {
        //     $isAuthor = $_SESSION["user"]->getId() === $this->getCustomerId();
        //     if ($_SESSION["user"]->isAdmin() || $isAuthor) {
        //         echo '<button type="button" class="btn btn-sm button_user_löschen" data-bs-toggle="modal" data-bs-target="#ReviewModal" data-review-id="' . $reviewId . '" data-customer-name="' . $customerName . '">Löschen</button>';
        //     }
        // }
         echo "</div>";
    
        // // Modal
        // echo '<div class="modal fade" id="ReviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        //         <div class="modal-dialog" role="document">
        //             <div class="modal-content">
        //                 <div class="modal-header">
        //                     <h5 class="modal-title" id="reviewModalLabel"></h5>
        //                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        //                 </div>
        //                 <div class="modal-body">
        //                     <form action="../php/deleteReview.php" method="get">
        //                         <input type="hidden" name="reviewId" value="">
        //                         <p>Sind Sie sicher, dass Sie den Kommentar von <span id="modalCustomerName"></span> löschen möchten?</p>
        //                         <div class="buttons_Single_Artwork">
        //                             <button type="submit" name="action" value="delete" class="btn btn-secondary button_style">Löschen</button>
        //                             <button type="button" class="btn btn-secondary button_style" data-bs-dismiss="modal">Abbrechen</button>
        //                         </div>
        //                     </form>
        //                 </div>
        //             </div>
        //         </div>
        //       </div>';
    
        // // Modal Script
        // echo "<script>
        //         document.addEventListener('DOMContentLoaded', function() {
        //             var reviewModal = document.getElementById('ReviewModal');
        //             reviewModal.addEventListener('show.bs.modal', function(event) {
        //                 var button = event.relatedTarget;
        //                 var reviewId = button.getAttribute('data-review-id');
        //                 var customerName = button.getAttribute('data-customer-name');
    
        //                 var modalTitle = reviewModal.querySelector('.modal-title');
        //                 var modalReviewIdInput = reviewModal.querySelector('input[name=\"reviewId\"]');
        //                 var modalCustomerNameSpan = reviewModal.querySelector('#modalCustomerName');
    
        //                 modalTitle.textContent = 'Bestätigung';
        //                 modalReviewIdInput.value = reviewId;
        //                 modalCustomerNameSpan.textContent = customerName;
        //             });
        //         });
        //       </script>";
    }
    

    public function showAverageRating(): void
    {
        $maxRating = 5;
        echo "<div class='average-rating'>";
        for ($i = 1; $i <= $maxRating; $i++) {
            echo $i <= $this->rating ? "<span class='star filled'>&#9733;</span>" : "<span class='star'>&#9733;</span>";
        }
        echo "</div>";
    }
}
?>


