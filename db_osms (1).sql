-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2024 at 12:59 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_osms`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateCategory` (IN `p_ID` INT, IN `p_Name` VARCHAR(100) CHARSET ascii, IN `p_Description` VARCHAR(255) CHARSET ascii, IN `p_Status` VARCHAR(15) CHARSET ascii, IN `p_Image` VARCHAR(255) CHARSET ascii)   BEGIN
    DECLARE sqlQuery TEXT;
    DECLARE params TEXT;

    SET sqlQuery = 'UPDATE tblcategory SET ';
    SET params = '';
    
    IF p_Name IS NOT NULL THEN
        SET sqlQuery = CONCAT(sqlQuery, 'Name = ?, ');
        SET params = CONCAT(params, 's', p_Name);
    END IF;

    IF p_Description IS NOT NULL THEN
        SET sqlQuery = CONCAT(sqlQuery, 'Description = ?, ');
        SET params = CONCAT(params, 's', p_Description);
    END IF;

    IF p_Status IS NOT NULL THEN
        SET sqlQuery = CONCAT(sqlQuery, 'Status = ?, ');
        SET params = CONCAT(params, 's', p_Status);
    END IF;

    IF p_Image IS NOT NULL THEN
        SET sqlQuery = CONCAT(sqlQuery, 'Image = ?, ');
        SET params = CONCAT(params, 's', p_Image);
    END IF;
    
    -- Remove trailing comma and space
    SET sqlQuery = LEFT(sqlQuery, LENGTH(sqlQuery) - 2);
    
    SET sqlQuery = CONCAT(sqlQuery, ' WHERE ID = ?');
    
    -- Prepare and execute the statement
    SET @stmt = sqlQuery;
    PREPARE stmt FROM @stmt;
    EXECUTE stmt USING p_Name, p_Description, p_Status, p_Image, p_ID;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Role` varchar(15) NOT NULL,
  `Status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcartitem`
--

CREATE TABLE `tblcartitem` (
  `ID` int(11) NOT NULL,
  `Customer` varchar(50) NOT NULL,
  `Product` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `tblcartitem`
--
DELIMITER $$
CREATE TRIGGER `check_cart_quantity` BEFORE INSERT ON `tblcartitem` FOR EACH ROW BEGIN
    DECLARE stock_quantity INT;

    -- Fetch the stock quantity for the product
    SELECT s.Quantity INTO stock_quantity
    FROM tblstock s
    WHERE s.Product = NEW.Product;

    -- Check if the new cart quantity is greater than the stock quantity
    IF NEW.Quantity > stock_quantity THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: Cart quantity is greater than available stock quantity';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_cart_quantity_before_update` BEFORE UPDATE ON `tblcartitem` FOR EACH ROW BEGIN
    DECLARE stock_quantity INT;

    -- Fetch the stock quantity for the product
    SELECT s.Quantity INTO stock_quantity
    FROM tblstock s
    WHERE s.Product = NEW.Product;

    -- Check if the new cart quantity is greater than the stock quantity
    IF NEW.Quantity > stock_quantity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Cart quantity is greater than available stock quantity';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_duplicate_product_in_cart` BEFORE INSERT ON `tblcartitem` FOR EACH ROW BEGIN
    DECLARE existing_count INT;

    -- Check if the product is already in the cart for the same user
    SELECT COUNT(*) INTO existing_count
    FROM tblcartitem
    WHERE Product = NEW.Product AND Customer = NEW.Customer;

    -- If the count is greater than 0, it means the product is already in the cart
    IF existing_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Product already in the cart for this customer';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `CreatedDate` date NOT NULL,
  `Status` varchar(15) NOT NULL,
  `Image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomer`
--

CREATE TABLE `tblcustomer` (
  `Name` varchar(100) NOT NULL,
  `Username` varchar(40) NOT NULL,
  `Email` varchar(225) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `JoinedDate` datetime NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Mobile` bigint(16) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcustomer`
--

INSERT INTO `tblcustomer` (`Name`, `Username`, `Email`, `Password`, `JoinedDate`, `Status`, `Mobile`, `Address`) VALUES
('Mohamed', 'demo', 'maxamedcabdicasiis910@gmail.com', 'fe01ce2a7fbac8fafaed7c982a04e229', '0000-00-00 00:00:00', 'Active', 619093882, 'Saysay');

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

CREATE TABLE `tblorder` (
  `ID` int(11) NOT NULL,
  `Customer` varchar(50) NOT NULL,
  `Transaction` int(11) NOT NULL,
  `Order_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Total_Amount` decimal(10,2) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblorderitem`
--

CREATE TABLE `tblorderitem` (
  `ID` int(11) NOT NULL,
  `Order_ID` int(11) NOT NULL,
  `Product` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `tblorderitem`
--
DELIMITER $$
CREATE TRIGGER `update_sales_count` AFTER INSERT ON `tblorderitem` FOR EACH ROW BEGIN
  UPDATE `tblproduct`
  SET `SalesCount` = `SalesCount` + NEW.`Quantity`
  WHERE `ID` = NEW.`Product`;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `ID` int(11) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Category` int(11) NOT NULL,
  `DateCreated` date NOT NULL,
  `UpdatedDate` datetime NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(20) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Color` varchar(50) NOT NULL,
  `Size` varchar(10) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Image` text NOT NULL,
  `SalesCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblstock`
--

CREATE TABLE `tblstock` (
  `ID` int(11) NOT NULL,
  `Product` int(11) NOT NULL,
  `Quantity` int(3) NOT NULL,
  `Status` varchar(26) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Role` varchar(15) NOT NULL,
  `Sex` varchar(10) NOT NULL,
  `BOD` date NOT NULL,
  `Password` varchar(26) NOT NULL,
  `RegisteredDate` datetime NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(100) NOT NULL,
  `stripe_session_id` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcartitem`
--
ALTER TABLE `tblcartitem`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Product` (`Product`),
  ADD KEY `Customer` (`Customer`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  ADD PRIMARY KEY (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Mobile` (`Mobile`);

--
-- Indexes for table `tblorder`
--
ALTER TABLE `tblorder`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Transaction` (`Transaction`);

--
-- Indexes for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Order_ID` (`Order_ID`),
  ADD KEY `Product` (`Product`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Category` (`Category`);

--
-- Indexes for table `tblstock`
--
ALTER TABLE `tblstock`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Product` (`Product`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`,`Email`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcartitem`
--
ALTER TABLE `tblcartitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblorder`
--
ALTER TABLE `tblorder`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblstock`
--
ALTER TABLE `tblstock`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcartitem`
--
ALTER TABLE `tblcartitem`
  ADD CONSTRAINT `tblcartitem_ibfk_2` FOREIGN KEY (`Product`) REFERENCES `tblproduct` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblcartitem_ibfk_3` FOREIGN KEY (`Customer`) REFERENCES `tblcustomer` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblorder`
--
ALTER TABLE `tblorder`
  ADD CONSTRAINT `tblorder_ibfk_1` FOREIGN KEY (`Transaction`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  ADD CONSTRAINT `tblorderitem_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `tblorder` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblorderitem_ibfk_2` FOREIGN KEY (`Product`) REFERENCES `tblproduct` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD CONSTRAINT `tblproduct_ibfk_1` FOREIGN KEY (`Category`) REFERENCES `tblcategory` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblstock`
--
ALTER TABLE `tblstock`
  ADD CONSTRAINT `tblstock_ibfk_1` FOREIGN KEY (`Product`) REFERENCES `tblproduct` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tblcustomer` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
