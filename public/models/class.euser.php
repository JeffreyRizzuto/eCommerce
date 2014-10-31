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
    public  $cart = -1; //oid connects wishlist order and book_order to a cart
    public  $address_status = FALSE;
    public  $address_count = 0;
    public  $user_status = FALSE;
    public  $newCart = FALSE;

    function __construct($user, $pass, $email, $fname, $lname, $phone_number) {
        $this->email = sanitize($email);
        $this->username = sanitize($user);
        $this->pass = trim($pass);
        $this->fname = $fname;
        $this->lname = $lname;
        $this->phone_number = $phone_number;

        if(usernameExists($this->username)) {
            $this->user_status = FALSE;
        } else if(emailExists($this->email)) {
            $this->user_status = FALSE;
        } else {
            $this->user_status = TRUE;
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
            $this->address_status = TRUE;
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

            $stmt->bind_param("ssssssssiisssssi", $this->username, $this->email, $this->fname, $this->lname, $this->cur_address_street, 
                $this->cur_address_no, $this->cur_address_city, $this->cur_address_state, $this->cur_address_zip, $this->phone_number, 
                $securepass, $this->bil_address_street, $this->bil_address_no, $this->bil_address_city, $this->bil_address_state, $this->bil_address_zip);
            $stmt->execute();
            $this->uid = $myQuery->insert_id;
            $stmt->close();
    }//end of addEUser

    //sets the uid
    function setuid() {
        global $myQuery;

        $stmt = $myQuery->prepare("SELECT `UID` FROM `user` WHERE `username` = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $stmt->bind_result($uid);
        $stmt->fetch();
        $this->uid = $uid;
        //this is jeff, shouldn't there be a close here??
        //$stmt->close();
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
        $count = 0; //this counter is used to see if the user has a cart or not

        if($this->newCart == TRUE) {
            $stmt = $myQuery->prepare("INSERT INTO `order` (purchased, purchase_date, total_price) VALUES (?, ?, ?)");
            $purchased = 0;
            $date = NULL;
            $price = 0;
            $stmt->bind_param("isd", $purchased, $date, $price);
            $stmt->execute();
            $this->cart = $myQuery->insert_id;
            $stmt->close();
            $this->newCart = FALSE;
        } else {
            $stmt = $myQuery->prepare("SELECT `OID` FROM `shopping_cart` WHERE `UID` = ?");
            $stmt->bind_param("i", $this->uid);
            $stmt->execute();
            $stmt->bind_result($cart);
            while($stmt->fetch()) {
                $this->cart = $cart;
                $count++;
            } 
            $stmt->close();
            if($count == 0) {
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
        $stmt = $myQuery->prepare("INSERT IGNORE INTO `shopping_cart` (uid, oid) VALUES ( ?, ?)");
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
        $stmt = $myQuery->prepare("INSERT INTO `book_order` (oid, ISBN, qty, date_added) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $this->cart, $isbn, $qty, date("Y-m-d h:i:s"));
        $stmt->execute();
        $stmt->close();

        $this->updateData($isbn);
    }//end of addToCart

    //used as a helper function to update total in order
    function updateData() {
        global $myQuery;
        $isbns = array();
        $prices = array();
        $total = 0;

        //compile a list of all isbns of the customer's cart
        $stmt = $myQuery->prepare("SELECT `ISBN` FROM `book_order` WHERE `oid` = ?");
        $stmt->bind_param("i", $this->cart);
        $stmt->execute();
        $stmt->bind_result($isbn);
        while($stmt->fetch()) {
            $isbns[] = $isbn;
        }
        $stmt->close();

        //loop through isbns, getting list of prices tied to isbn
        foreach($isbns as $isbn) {
            //get price of book
            $stmt = $myQuery->prepare("SELECT `price` FROM `books` WHERE `isbn` = ?");
            $stmt->bind_param("s", $isbn);
            $stmt->execute();
            $stmt->bind_result($price);
            while($stmt->fetch()) {
                $prices[] = $price;
            }
            $stmt->close();
        }

        $index = 0;
        //loop through isbns, add their (price * qty) to total
        foreach ($isbns as $isbn) {
            $stmt = $myQuery->prepare("SELECT `qty` FROM `book_order` WHERE `oid` = ? AND `ISBN` = ?");
            $stmt->bind_param("is", $this->cart, $isbn);
            $stmt->execute();
            $stmt->bind_result($qty);
            $stmt->fetch();
            $stmt->close();
            $total = $total + ($prices[$index] * $qty);
            $index++;
        }

        //update total price
        $stmt = $myQuery->prepare("UPDATE `order` SET `total_price` = ? WHERE `oid` = ? AND `purchased` = 0");
        $stmt->bind_param("di", $total, $this->cart);
        $stmt->execute();
        $stmt->close();
    }//end of updateData

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
        $this->newCart = TRUE;
        $this->getCart();
    }//end of checkout

    function updateQuantity($newQty, $isbn) {
        global $myQuery;

        $stmt = $myQuery->prepare("UPDATE `book_order` SET `qty` = ? WHERE `oid` = ? AND `ISBN` = ?");
        $stmt->bind_param("iis", $newQty, $this->cart, $isbn);
        $stmt->execute();
        $stmt->close();

        $this->updateData($isbn);
    }//end of updateQuantity

    //removes the selected book from the user's cart
    function removeFromCart($isbn) {
        global $myQuery;

        $stmt = $myQuery->prepare("DELETE FROM `book_order` WHERE `oid` = ? AND `ISBN` = ?");
        $stmt->bind_param("is", $this->cart, $isbn);
        $stmt->execute();
        $stmt->close();

        $this->updateData();
    }//end of removeFromCart

    function getPastOrders() {
        global $myQuery;
        $index = 0;

        //get all oids that correspond to the customer
        $stmt = $myQuery->prepare("SELECT `oid` FROM `shopping_cart` WHERE `uid` = ?");
        $stmt->bind_param("i", $this->uid);
        $stmt->execute();
        $stmt->bind_result($oid);
        while($stmt->fetch()) {
            $order[] = $oid;
        }
        $stmt->close();

        //the last oid in the array will be the customer's current shopping cart
        array_pop($order);
        $orders = array_unique($order);

        //get the order information for orders that have been purchased
        foreach ($orders as $oid) {
            $stmt = $myQuery->prepare("SELECT `total_price` FROM `order` WHERE `oid` = ?");
            $stmt->bind_param("i", $oid);
            $stmt->execute();
            $stmt->bind_result($price);
            while($stmt->fetch()) {
                $prices[] = $price;
            }
            $stmt->close();
        }

        //loop through the customer's past orders to get the information
        foreach ($orders as $oid) {
            $stmt = $myQuery->prepare("SELECT * FROM `book_order` WHERE `oid` = ?");
            $stmt->bind_param("i", $oid);
            $stmt->execute();
            $stmt->bind_result($noid, $isbn, $qty, $date);
            while($stmt->fetch()) {
                $ret[] = array('oid' => $noid, 'o_inf' => array('order' => $noid, 'isbn' => $isbn, 'qty' => $qty, 'date' => $date), 'price' => $prices[$index]);
                $index++;
            }
            $stmt->close();
        }

        return $ret;
    }//end of getPastOrders

}//end of EUser
