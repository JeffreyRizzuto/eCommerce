    <?php

class EUser {
    private $username;
    private $pass;
    private $email;
    private $fname;
    private $lname;
    private $phone_number;
    private $cur_address_street;
    private $cur_address_no;
    private $cur_address_city;
    private $cur_address_state;
    private $cur_address_zip;
    private $bil_address_street;
    private $bil_address_no;
    private $bil_address_city;
    private $bil_address_state;
    private $bil_address_zip;
    private $uid = -1;
    private $wishlist = -1; //oid connects wishlist order and book_order to a wishlist
    private $cart = -1; //oid connects wishlist order and book_order to a cart
    public  $address_status = false;
    public  $address_count = 0;
    public  $user_status = false;
    public  $newCart = false;

    function __construct($user, $pass, $email, $fname, $lname, $phone_number) {
        $this->email = sanitize($email);
        $this->username = sanitize($user);
        $this->pass = trim($pass);
        $this->fname = $fname;
        $this->lname = $lname;
        $this->phone_number = $phone_number;

        if(usernameExists($this->username)) {
            $this->user_status = false;
        } else if(emailExists($this->email)) {
            $this->user_status = false;
        } else {
            $this->user_status = true;
        }
    }//end of constructor

    function addAddress($type, $street, $no, $city, $state, $zip) {
        if($type == "billing" || $type == "Billing") {
            $this->bil_address_street = sanitize($street);
            $this->bil_address_no = sanitize($no);
            $this->bil_address_city = sanitize($city);
            $this->bil_address_state = sanitize($state);
            $this->bil_address_zip = sanitize($zip);
            $address_count++;
        } else if($type == "current" || $type == "Current") {
            $this->cur_address_street = sanitize($street);
            $this->cur_address_no = sanitize($no);
            $this->cur_address_city = sanitize($city);
            $this->cur_address_state = sanitize($state);
            $this->cur_address_zip = sanitize($zip);
            $address_count++;
        } else {
            $this->bil_address_street = sanitize($street);
            $this->bil_address_no = sanitize($no);
            $this->bil_address_city = sanitize($city);
            $this->bil_address_state = sanitize($state);
            $this->bil_address_zip = sanitize($zip);
            $this->cur_address_street = sanitize($street);
            $this->cur_address_no = sanitize($no);
            $this->cur_address_city = sanitize($city);
            $this->cur_address_state = sanitize($state);
            $this->cur_address_zip = sanitize($zip);
            $address_count = 2;
        }
        if($this->address_count == 2) {
            $this->address_status = true;
        }
    }//end of addAddress

    function addEUser() {
        global $myQuery;

            $securepass = generateHash($this->password);

            $stmt = $myQuery->prepare("INSERT INTO user (
                username, email, fname, lname, cur_address_street, cur_address_no, cur_address_city, cur_address_state,
                cur_address_zip, phone_number, pass, bil_address_street, bil_address_no, bil_address_city,
                bil_address_state, bil_address_zip 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssssssiisssssi", $this->username, $this->email, $this->fname, $this->lname, $this->cur_address_street, $this->cur_address_no, $this->cur_address_city, $this->cur_address_state, $this->cur_address_zip, $this->phone_number, $securepass, $this->bil_address_street, $this->bil_address_no, $this->bil_address_city, $this->bil_address_state, $this->bil_address_zip);
            $stmt->execute();
            $this->uid = $myQuery->insert_id;
//            echo "$this->uid\n";
            $stmt->close();
    }//end of addEUser

    function setuid($uid) {
        $this->uid = $uid;
    }

    /*
    * Helper function to create a new order to be the user's wishlist.
    */
    function getWishlist() {
        global $myQuery;
        $wishlist = -1;

        $stmt = $myQuery->prepare("SELECT `OID` FROM `wishlist` WHERE `UID` = ?");
        $stmt->bind_param("i", $this->uid);
        $stmt->execute();
        $stmt->bind_result($wishlist);
        $stmt->fetch();
        $stmt->close();
        if($wishlist != -1) {
            $this->wishlist = $wishlist;
        } else {
            //we must create a new order to be the user's wishlist
            $stmt = $myQuery->prepare("INSERT INTO `order` (
                purchased, purchase_date, total_price
                ) VALUES (
                ?, ?, ?");
            $stmt->bind_param("isi", 0, "n/a", 0);
            $stmt->execute();
            $this->wishlist = $myQuery->insert_id;
            $stmt->close();
        }//end of finding wishlist oid
    }//end of getWishlist

    /*
    * Helper function to create a new order to be the user's cart.
    */
    function getCart() {
        global $myQuery;

        if($this->newCart == true) {
            echo "Creating a new cart Old: $this->cart<br>";
            $stmt = $myQuery->prepare("INSERT INTO `order` (purchased, purchase_date, total_price) VALUES (?, ?, ?)");
            $purchased = 0;
            $date = NULL;
            $price = 0;
            $stmt->bind_param("isd", $purchased, $date, $price);
            $stmt->execute();
            $this->cart = $myQuery->insert_id;
            $stmt->close();
            $this->newCart = false;
            echo "New: $this->cart<br>";
        } else {
            $cart = -1;
            $stmt = $myQuery->prepare("SELECT `OID` FROM `shopping_cart` WHERE `UID` = ?");
            $stmt->bind_param("i", $this->uid);
            $stmt->execute();
            $stmt->bind_result($cart);
            if($stmt->fetch()) {
                $this->cart = $cart;
                $stmt->close();
            } else {
                $stmt->close();
                //we must create a new order to be the user's cart
                $stmt = $myQuery->prepare("INSERT INTO `order` (
                    purchased,
                    purchase_date,
                    total_price
                    )
                    VALUES (
                    ?,
                    ?,
                    ?)");
                $purchased = 0;
                $date = NULL;
                $price = 0;
                $stmt->bind_param("isd", $purchased, $date, $price);
                $stmt->execute();
                $this->cart = $myQuery->insert_id;
                $stmt->close();
            }//end of finding cart oid
        }//end of else

        //put the info into shopping_cart table
        $stmt = $myQuery->prepare("INSERT IGNORE INTO `shopping_cart` ( `uid`, `oid` ) VALUES ( ?, ? )");
        $stmt->bind_param("ii", $this->uid, $this->cart);
        $stmt->execute();
        $stmt->close();
    }//end of getCart

    function addToCart($isbn, $qty) {
        global $myQuery;

        if($this->cart < 1) {
            //someone dun goofed D:<
            $this->getCart();
        }
        
        //create new book order
        $stmt = $myQuery->prepare("INSERT INTO `book_order` (
            oid,
            ISBN,
            qty,
            date_added
            )
            VALUES (
            ?,
            ?,
            ?,
            ?)");
        $stmt->bind_param("isis", $this->cart, $isbn, $qty, date("Y-m-d h:i:s"));
        $stmt->execute();
        $stmt->close();

        //get price of book
        $stmt = $myQuery->prepare("SELECT `price` FROM `books` WHERE `isbn` = ?");
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $stmt->bind_result($newPrice);
        $stmt->fetch();
        $stmt->close();
       
        //get current total_price of order
        $stmt = $myQuery->prepare("SELECT `total_price` FROM `order` WHERE `oid` = ? AND `purchased` = 0");
        $stmt->bind_param("i", $this->cart);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
       
        echo "newPrice: $newPrice qty: $qty total: $total";
        $newPrice = $newPrice * $qty + $total;


        //update total price
        $stmt = $myQuery->prepare("UPDATE `order` SET `total_price` = ? WHERE `oid` = ? AND `purchased` = 0");
        $stmt->bind_param("di", $newPrice, $this->cart);
        $stmt->execute();
        $stmt->close();

    }//end of addToCart

    function getCartInfo() {
        global $myQuery;

        $stmt = $myQuery->prepare("SELECT * FROM `book_order` WHERE `oid` = ?");
        $stmt->bind_param("i", $this->cart);
        $stmt->execute();
        $stmt->bind_result($oid, $isbn, $qty, $date);
        while($stmt->fetch()) {
            $row[] = array('oid' => $oid, 'isbn' => $isbn, 'qty' => $qty, 'date' => $date, 'pic' => '../images/'.$isbn.'.jpg');
        }
        $stmt->close();

        return $row;
    }//end of getCartInfo

    function getCartTotal() {
        global $myQuery;

        $stmt = $myQuery->prepare("SELECT `total_price` FROM `order` WHERE `oid` = ? AND `purchased` = 0");
        $stmt->bind_param("i", $this->cart);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }//end of getCartTotal

    function checkout() {
        global $myQuery;

        //set order purchased to 1
        $stmt = $myQuery->prepare("UPDATE `order` SET `purchased` = 1 WHERE `oid` = ? ");
        $stmt->bind_param("i", $this->cart);
        $stmt->execute();
        $stmt->close();

        $stmt = $myQuery->prepare("UPDATE `order` SET `purchase_date` = ? WHERE `oid` = ? ");
        $stmt->bind_param("si", date("Y-m-d h:i:s"), $this->cart);
        $stmt->execute();
        $stmt->close();

        //update cart order number
        $this->newCart = true;
        $this->getCart();
    }//end of checkout

}//end of EUser
