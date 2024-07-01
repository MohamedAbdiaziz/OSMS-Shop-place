-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2024 at 04:40 PM
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

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`ID`, `Username`, `Password`, `Email`, `ProfileImage`, `Created_at`, `Updated_at`, `Role`, `Status`) VALUES
(1, 'Demo', 'c20ad4d76fe97759aa27a0c99bff6710', 'cali07497@gmail.com', NULL, '2024-06-26 09:32:20', '2024-06-26 11:49:40', 'Super Admin', 'Active'),
(2, 'Test', 'c51ce410c124a10e0db5e4b97fc2af39', 'maxamedcabdicasiis910@gmail.com', NULL, '2024-06-29 19:10:36', '2024-06-29 19:15:09', 'Admin', 'Active');

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

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `Name`, `Description`, `CreatedDate`, `Status`, `Image`) VALUES
(26, 'Eyeglasses', 'Eyeglasses serve as corrective or protective eyewear, designed to improve vision or shield the eyes from harmful elements. ', '2024-07-01', 'Active', 'img_6682a3d37cdf60.21147053.jpg'),
(27, 'Sunglasses', 'Sunglasses are designed to protect the eyes from the sun\'s ultraviolet (UV) rays and reduce glare. They come in various styles, lens colors, and levels of polarization to enhance visual comfort and fashion.', '2024-07-01', 'Active', 'img_6682a418c66657.92485489.jpg'),
(28, 'Reading Glasses', 'Reading glasses are non-prescription eyeglasses designed to help with close-up tasks like reading and needlework. They are usually available in various strengths and can be purchased over the counter.', '2024-07-01', 'Active', 'img_6682a52a003619.56313591.jpg'),
(29, 'Fashion and Designer Eyewear', 'This category includes high-end frames and sunglasses from designer brands. These products often feature premium materials, unique designs, and brand logos, appealing to fashion-conscious consumers.', '2024-07-01', 'Active', 'img_6682a58fc449b0.60510271.jpg'),
(30, 'Sports Eyewear', 'Sports eyewear is tailored to the needs of athletes and can include features like impact resistance and enhanced field of vision.', '2024-07-01', 'Active', 'img_6682a5ebdf70d9.19096941.jpg');

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
('Mohamed Hassan', 'Mohamed3882', 'maxamedcabdicasiis910@gmail.com', 'e678dd79efaf31d9e055ab6941509e04', '0000-00-00 00:00:00', 'Active', 619093882, 'Warta Nabadda');

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
(56, 'Mohamed3882', 86, '2024-07-01 17:19:39', '25.61', 'Pending');

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
(39, 56, 15, 1, '11.83'),
(40, 56, 16, 1, '13.78');

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
(15, 'Ray-Ban RX5154 Clubmaster', 'The Ray-Ban RX5154 Clubmaster eyeglasses blend timeless style with modern flair. Featuring a distinctive retro-inspired design, these frames offer a sophisticated look with a nod to vintage aesthetics. The upper frame is crafted from high-quality acetate, while the lower frame and temples are made of durable metal, ensuring both comfort and longevity. The Clubmaster\'s iconic browline design, accented with subtle metal rivets, creates a striking visual appeal that suits a variety of face shapes.', 26, '2024-07-01', '2024-07-01 15:55:57', 'Active', 'Glasses', 'Black', 'Large', '11.83', 'img_6682a75d9aad27.10824329.jpg', 1),
(16, 'Oakley Crosslink', 'The Oakley Crosslink eyeglasses are designed for active lifestyles, offering a perfect blend of performance, style, and versatility. These frames are crafted from Oakley\'s proprietary O-Matter material, known for its lightweight and durable properties. ', 26, '2024-07-01', '2024-07-01 15:59:47', 'Active', 'Glasses', 'Red', 'Large', '13.78', 'img_6682a843b0d6a7.42355436.jpg', 1),
(17, 'Warby Parker Percey', 'The Warby Parker Percey eyeglasses are a classic, round-frame design that exudes timeless elegance and sophistication. Made from hand-polished cellulose acetate, these frames are lightweight yet durable, offering a comfortable fit for all-day wear.', 26, '2024-07-01', '2024-07-01 16:02:13', 'Active', 'Glasses', 'Black', 'Medium', '9.70', 'img_6682a8d5b0e955.42955803.jpg', 0),
(18, 'Ray-Ban RB2132 New Wayfarer', 'The Ray-Ban RB2132 New Wayfarer sunglasses are a modern take on the classic Wayfarer design, offering a slightly smaller frame and softer eye shape for a more contemporary look. These sunglasses feature high-quality acetate frames that are both lightweight and durable, ensuring comfortable wear all day long.', 27, '2024-07-01', '2024-07-01 16:04:35', 'Active', 'Sunglasses', 'Black', 'Large', '12.24', 'img_6682a9634ae5f4.78818237.jpg', 0),
(19, 'Maui Jim Ho\'okipa', 'The Maui Jim Ho\'okipa sunglasses are designed to provide superior eye protection and visual clarity while offering a lightweight and comfortable fit. These sporty, rimless sunglasses feature Maui Jim\'s patented PolarizedPlus2® lens technology, which not only blocks 100% of harmful UV rays but also enhances colors, reduces glare, and improves contrast, making them perfect for outdoor activities in bright sunlight.', 27, '2024-07-01', '2024-07-01 16:26:33', 'Active', 'Sunglasses', 'Brown', 'Medium', '13.78', 'img_6682ae89054f23.01173382.jpg', 0),
(20, 'Oakley Holbrook', 'The Oakley Holbrook sunglasses blend a vintage-inspired design with modern technology, making them a popular choice for both casual and active lifestyles. Featuring a classic, timeless silhouette, the Holbrook has a full-rim frame made from Oakley\'s O-Matter™ material, which is lightweight, durable, and stress-resistant, ensuring long-lasting comfort and performance.', 27, '2024-07-01', '2024-07-01 16:28:44', 'Active', 'Sunglasses', 'Black', 'Large', '11.83', 'img_6682af0cbc4255.69549783.jpg', 0),
(21, 'CliC Readers Original Expandable Front Connect', 'The CliC Readers Original Expandable Front Connect eyeglasses offer a unique and convenient design for reading enthusiasts. These reading glasses feature a magnetic front connection that allows you to easily separate and reconnect the frames, making them perfect for quick and hassle-free wear. The adjustable headband ensures a secure and comfortable fit, while the expandable temples accommodate different head sizes.', 28, '2024-07-01', '2024-07-01 16:32:33', 'Active', 'Glasses', 'Black', 'Extra Larg', '7.47', 'img_6682aff1b207e6.91111405.jpg', 0),
(22, 'ThinOptics Frontpage Brooklyn Reading Glasses', 'The ThinOptics Frontpage Brooklyn Reading Glasses are designed for maximum convenience and portability without compromising on style or functionality. These ultra-thin, lightweight reading glasses feature a sleek and modern design, making them an ideal choice for those who need reading assistance on the go. The Brooklyn model is crafted with a durable, high-quality frame and precision-engineered lenses that provide clear vision and comfort.', 28, '2024-07-01', '2024-07-01 16:36:11', 'Active', 'Glasses', 'Black', 'Extra Larg', '6.47', 'img_6682b0cbe444d0.40190660.jpg', 0),
(23, 'Chanel CH3374', 'The Chanel CH3374 eyeglasses exemplify luxury and sophistication, reflecting Chanel\'s iconic style and craftsmanship. These elegant frames feature a refined rectangular shape, meticulously crafted from high-quality acetate, known for its durability and lustrous finish. The temples are adorned with Chanel\'s signature quilted pattern and the interlocking CC logo, adding a touch of glamour and prestige.', 29, '2024-07-01', '2024-07-01 16:38:42', 'Active', 'Glasses', 'Red', 'Large', '26.32', 'img_6682b1628057c4.30818368.jpg', 0),
(24, 'Nike Skylon Ace XV Sunglasses', 'The Nike Skylon Ace XV sunglasses are designed for athletes and outdoor enthusiasts, offering a blend of performance, durability, and style. These sporty sunglasses feature a lightweight nylon frame that provides comfort and a secure fit during high-intensity activities. The ventilated nose bridge and temple arms enhance airflow, reducing fogging and ensuring stability during movement.', 30, '2024-07-01', '2024-07-01 16:41:06', 'Active', 'Glasses', 'Black', 'Medium', '5.47', 'img_6682b1f27a4002.86723675.jpg', 0),
(25, 'Oakley Flak 2.0 XL', 'The Oakley Flak 2.0 XL sunglasses are engineered for athletes and outdoor enthusiasts seeking performance and versatility. These sport sunglasses feature a lightweight O-Matter™ frame that offers durability and all-day comfort, making them ideal for active lifestyles. The semi-rimless design enhances downward visibility, making them suitable for sports like cycling, running, and golf.', 30, '2024-07-01', '2024-07-01 16:44:34', 'Active', 'Sunglasses', 'Other', 'Extra Larg', '12.24', 'img_6682b2c209cd76.06330070.jpg', 0);

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
(10, 15, 49, 'Available'),
(11, 16, 49, 'Available'),
(12, 17, 50, 'Available'),
(13, 18, 50, 'Available'),
(14, 19, 50, 'Available'),
(15, 20, 50, 'Available'),
(16, 21, 20, 'Available'),
(17, 22, 19, 'Available'),
(18, 23, 20, 'Available'),
(19, 24, 30, 'Available'),
(20, 25, 17, 'Available');

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
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `customer_id`, `stripe_session_id`, `amount`, `created_at`, `status`, `Description`) VALUES
(86, 'Mohamed3882', 'cs_test_b1LhZY3nxwcnXyQtQl66H5WSrANJetOYMhw6TXw8fQIx6iIAzMoLDB1YGp', '25.61', '2024-07-01 14:19:39', 'completed', 'pi_3PXlA708OHR1fd540eBIa3n8');

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblcartitem`
--
ALTER TABLE `tblcartitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblorder`
--
ALTER TABLE `tblorder`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tblorderitem`
--
ALTER TABLE `tblorderitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tblstock`
--
ALTER TABLE `tblstock`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

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
