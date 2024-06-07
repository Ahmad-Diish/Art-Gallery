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
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .submit-comment-delete-btn {
            background-color: #923f0e;
            color: white; 
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .submit-comment-delete-btn:hover {
            background-color: #d32f2f; 
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
        echo "<div class='review-header'>";
        echo "<p class='review-author'>Kommentator: $customerName</p>";
        if (isset($_SESSION['CustomerID'])) : ?>
            <form class="deleteForm" method="POST" action="../Datenbank/deleteReview.php" style="display:inline;">
                <input type="hidden" name="ReviewId" value="<?php echo $reviewId; ?>">
                <button type="submit" class="submit-comment-delete-btn" onclick="return confirm('Sind Sie sicher, dass Sie diesen Kommentar löschen möchten?')">Kommentar löschen</button>
            </form> <?php endif;
                echo "</div>";
                echo "<p class='review-date'>Review Date: $reviewDate</p>";
                echo "<div class='rating'>";

                for ($i = 1; $i <= $maxRating; $i++) {
                    echo $i <= $rating ? "<span class='star filled'>&#9733;</span>" : "<span class='star'>&#9733;</span>";
                }

                echo "</div>";
                echo '<p>' . $this->getComment() . '</p>';
                echo "</div>";
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
