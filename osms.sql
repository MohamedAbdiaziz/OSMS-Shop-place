-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 09:46 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
-- Dumping data for table `tblcartitem`
--

INSERT INTO `tblcartitem` (`ID`, `Customer`, `Product`, `Quantity`, `Price`, `Subtotal`) VALUES
(172, 'Mohamed3882', 10, 1, '112.00', '112.00'),
(173, 'Mohamed3882', 11, 0, '7.00', '0.00'),
(174, 'Mohamed3882', 12, 0, '45.00', '0.00'),
(183, 'Yussuf488', 11, 5, '7.00', '35.00');

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

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `Name`, `Description`, `CreatedDate`, `Status`, `Image`) VALUES
(22, 'Testing', 'erfdgf', '2024-06-03', 'Active', 'img_665d6aeb985097.98606441.png'),
(23, 'Sunglasses', 'asdftyuio', '2024-06-03', 'Active', 'img_665d88f29f3363.52628307.png');

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomer`
--

CREATE TABLE `tblcustomer` (
  `Name` varchar(100) NOT NULL,
  `Username` varchar(40) NOT NULL,
  `Email` varchar(225) NOT NULL,
  `Password` varchar(26) NOT NULL,
  `JoinedDate` datetime NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Mobile` bigint(16) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcustomer`
--

INSERT INTO `tblcustomer` (`Name`, `Username`, `Email`, `Password`, `JoinedDate`, `Status`, `Mobile`, `Address`) VALUES
('Mohamed Abdiaziz', 'Mohamed3882', 'mohamedAdiaziz@gmail.com', '1234!@#$', '2024-05-21 09:18:19', 'Active', 0, ''),
('Yusuf Abdiaziz', 'Yussuf488', 'Yussuf488@mail.co', '123422311', '2024-06-07 21:55:32', 'Active', 68682323, 'Xamar Jadiid,Warta Nabadda,Banadir, Mogadishu, Somalia');

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

--
-- Dumping data for table `tblorder`
--

INSERT INTO `tblorder` (`ID`, `Customer`, `Transaction`, `Order_Date`, `Total_Amount`, `Status`) VALUES
(9, 'Mohamed3882', 39, '2024-06-11 19:39:14', '0.00', ''),
(13, 'Mohamed3882', 43, '2024-06-11 20:49:01', '605.00', ''),
(16, 'Mohamed3882', 46, '2024-06-11 20:57:21', '605.00', ''),
(27, 'Mohamed3882', 57, '2024-06-11 21:19:04', '605.00', ''),
(28, 'Mohamed3882', 58, '2024-06-11 21:28:13', '7.00', 'Pending'),
(29, 'Mohamed3882', 59, '2024-06-11 21:30:01', '84.00', 'Pending');

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
-- Dumping data for table `tblorderitem`
--

INSERT INTO `tblorderitem` (`ID`, `Order_ID`, `Product`, `Quantity`, `Price`) VALUES
(1, 9, 11, 2, '0.00'),
(7, 27, 12, 1, '45.00'),
(8, 27, 10, 3, '112.00'),
(9, 27, 11, 32, '7.00'),
(10, 28, 11, 1, '7.00'),
(11, 29, 11, 12, '7.00');

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

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`ID`, `ProductName`, `Description`, `Category`, `DateCreated`, `UpdatedDate`, `Status`, `Type`, `Color`, `Size`, `Price`, `Image`, `SalesCount`) VALUES
(10, 'Sunglasses', 'DDDkkd', 23, '2024-06-03', '2024-06-03 17:40:33', 'Active', 'Type1', 'Red', 'Small', '112.00', 'ew3.jpg', 3),
(11, 'testing Glasses', 'jkh', 22, '2024-06-03', '2024-06-03 18:03:03', 'Active', 'Type1', 'Red', 'Small', '7.00', 'ew2.jpg', 47),
(12, 'tyty', 'tyyty', 22, '2024-06-05', '2024-06-05 23:00:21', 'Active', 'Contact Lenses', 'Black', 'Small', '45.00', 'img_6660c3d3f314d1.93550532.png', 1);

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

--
-- Dumping data for table `tblstock`
--

INSERT INTO `tblstock` (`ID`, `Product`, `Quantity`, `Status`) VALUES
(4, 10, 10, 'Available'),
(6, 11, 5, ''),
(7, 12, 50, '');

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
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `customer_id`, `stripe_session_id`, `amount`, `created_at`, `status`) VALUES
(2, 'Mohamed3882', 'cs_test_b1rfFHmLdJc52yP6OCyevZBfSH7BkSWRzijQnI5xvYDlD54Ff8LoexbuBE', '451.00', '2024-06-07 16:05:14', 'Pending'),
(3, 'Mohamed3882', 'cs_test_b10jdZZYZrfyTRTxJ1PVo5eTJhPwfU5Ojf9zwIHSWOSXaw93SvNulMh0mN', '451.00', '2024-06-07 16:05:24', 'Pending'),
(4, 'Mohamed3882', 'cs_test_b1yCw9kPwZl67pFSXoni31QMVk2pvol6lOnDf4P8VUXM2Bd4CArQhrhEF9', '451.00', '2024-06-07 16:06:16', 'Pending'),
(5, 'Mohamed3882', 'cs_test_b1HlDH1KOIWCR65Tse5pgvNCkYdpLtAUttEwvUIPmpApbqsAQcM8noNIOL', '451.00', '2024-06-07 16:10:43', 'Pending'),
(6, 'Mohamed3882', 'cs_test_b13mwGdTx4m7cUbSFLGiNG7U0uLp6S5rRan20Pe3hK4EQempGH7z4QE4Pc', '451.00', '2024-06-07 16:11:28', 'Pending'),
(7, 'Mohamed3882', 'cs_test_b1NmVSTANQzGhceFaAEQpgnwnNGNrQC48A6Da1ozCrZVJGW7hMDmtmRLMS', '451.00', '2024-06-07 19:05:06', 'Pending'),
(8, 'Mohamed3882', 'cs_test_b1hHK8WppgmccBzhQePwKZpMmxKzt3Ch5eySJzE509QFFRrhI4PmAeUxSi', '451.00', '2024-06-07 19:09:25', 'completed'),
(9, 'Mohamed3882', 'cs_test_b1FHzHaRb8xINf1JH0om0n4K6IUZflSxEBVHdaGJ6oLhyrOgFnAM1978Lh', '451.00', '2024-06-07 19:20:09', 'completed'),
(10, 'Mohamed3882', 'cs_test_b1BEf57XGUQo8EceGisyseRceK0qvs5tr8prWX4yqDzcXpJdS77VWNViYH', '224.00', '2024-06-07 19:37:24', 'completed'),
(11, 'Mohamed3882', 'cs_test_b16QXXXnalYJUxkKnxVBbMGaBPrOpoq0aF9g2L9kbCSGYoNsjLWkB3ihbU', '119.00', '2024-06-07 19:42:54', 'completed'),
(12, 'Mohamed3882', 'cs_test_a1YpFRpUv8d3bDuKarSiluxP7QNIsIRhKBETi2l2IXrRcjViPAyMpUp8Hp', '112.00', '2024-06-07 19:48:07', 'completed'),
(13, 'Mohamed3882', 'cs_test_b1rCUYO3bYmLnpFFrYuX8gk65B6OKjy217l4LUqpAbDCah3BVrgtxdSvAG', '164.00', '2024-06-07 20:02:41', 'Pending'),
(14, 'Mohamed3882', 'cs_test_b1ETBW4ly9Zh9DACQtGpBNk4S7tUkxgLs8tieykA5bPhBqYhGhbvlXBWIc', '164.00', '2024-06-07 20:03:55', 'Pending'),
(15, 'Mohamed3882', 'cs_test_b1U0hkPTTXMlm8KUBoPQNsyeKiQryleh4OXcdsMIydfk5383dBPWgexseI', '164.00', '2024-06-07 20:18:33', 'Pending'),
(16, 'Mohamed3882', 'cs_test_b16T96m19ilGQpyLqxhrfbWJ9VrVFYXVoNvphlh7gevKT4oP4BnrPSFGKe', '164.00', '2024-06-07 20:20:43', 'Pending'),
(17, 'Mohamed3882', 'cs_test_b1MwCMFaMMWP5CRG8R6RfldInUenciCzpXjPBGSkBSenrKmwEUabWbJ8OE', '164.00', '2024-06-07 20:22:41', 'Pending'),
(18, 'Mohamed3882', 'cs_test_b1AcuL5lbVvRSjFvWBYXoVkZf8g3ij37u769CvFrlk9mMqiCcyKfgIpIKE', '164.00', '2024-06-09 11:14:27', 'Pending'),
(19, 'Mohamed3882', 'cs_test_b17vV4Ql8WhLBQe641K69NqBV7Ss7a5kZlLKO4MXx7TYkbl9CmUU4QpBl2', '164.00', '2024-06-09 12:29:33', 'Pending'),
(20, 'Mohamed3882', 'cs_test_b17QMtJfd1SdwyenNHcVaHZCzJLSgDzLF6KKL8j2upDUbhpEkAPWpFVECq', '164.00', '2024-06-09 20:15:08', 'Pending'),
(21, 'Mohamed3882', 'cs_test_b1P6NP9p0BRWulPLG6vvYYCmuMZbs30da2KRvPNZKr63Zz640NbCv7Nbcp', '164.00', '2024-06-09 20:15:14', 'Pending'),
(22, 'Mohamed3882', 'cs_test_b1cICPae4R7PECyhaHjiJHjkcumQGtC5ieoI5JoauNrDng4YQVupjtMgnc', '164.00', '2024-06-09 20:44:38', 'Pending'),
(23, 'Mohamed3882', 'cs_test_b1wAH3kWhvBzvXXy11moV4wbQYRy6rOZTz5P6Dxc0Ns0pzNffLxsiNWwDy', '388.00', '2024-06-10 14:02:22', 'Pending'),
(24, 'Mohamed3882', 'cs_test_b175nxlkTQKTIsupzBPPHpdteBRCsLBojHVLU3Su4oYkZkdanALqKJflg0', '402.00', '2024-06-10 14:04:03', 'Pending'),
(39, 'Mohamed3882', 'cs_test_b1fg7VwP0ryhj8Fu2zJlWptY7wB4xA11aR1lKxkpQVz318jCBnJAPP13rD', '100.00', '2024-06-11 16:39:14', 'Pending'),
(43, 'Mohamed3882', 'cs_test_b13o0c8b4xM572LsUblNzhVizTemzbiYS2pi4mOaesy7vsZXZBzk0WEIBq', '605.00', '2024-06-11 17:49:01', 'Pending'),
(46, 'Mohamed3882', 'cs_test_b15UHFjKs8tCEZCwL8tqxDvyfHv9p5QVNU31fNxd3bNz53jgoiFZtEB1OH', '605.00', '2024-06-11 17:57:21', 'Pending'),
(57, 'Mohamed3882', 'cs_test_b1JUfuPhw3Y60WnftkNE9Xu996ZbgWlwiWUVjSWzgKgWATxBaYtu2n2an1', '605.00', '2024-06-11 18:19:04', 'Pending'),
(58, 'Mohamed3882', 'cs_test_a1veFR4jycOt2ImA16di4ALq5IXcYQy4a32tUFNO2gCktZRnGdafkDgZji', '7.00', '2024-06-11 18:28:13', 'Pending'),
(59, 'Mohamed3882', 'cs_test_a1nbjgitVXfVIHLOItgVNtSLUeTaLtARTcPcvV83XOjQPwm6gowQ0CWe44', '84.00', '2024-06-11 18:30:01', 'Pending');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `tblcartitem`
--
ALTER TABLE `tblcartitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

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
  ADD CONSTRAINT `tblorderitem_ibfk_2` FOREIGN KEY (`Product`) REFERENCES `tblproduct` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
