-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3316
-- Generation Time: May 19, 2025 at 07:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbeasykcal`
--

-- --------------------------------------------------------

--
-- Table structure for table `alimentos`
--

CREATE TABLE `alimentos` (
  `idalimentos` int(11) NOT NULL,
  `nome_alimento` varchar(100) NOT NULL,
  `qtd_kcal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `alimentos`
--

INSERT INTO `alimentos` (`idalimentos`, `nome_alimento`, `qtd_kcal`) VALUES
(2, 'Açaí (100g)', 70),
(3, 'Abacate (100g)', 160),
(4, 'Abacaxi (100g)', 50),
(5, 'Alface americana (100g)', 12),
(6, 'Alface crespa (100g)', 15),
(7, 'Alface lisa (100g)', 14),
(8, 'Alho cru (100g)', 140),
(9, 'Alho-poró (100g)', 61),
(10, 'Amêndoas (100g)', 579),
(11, 'Amendoim torrado (100g)', 570),
(12, 'Arroz branco cozido (100g)', 130),
(13, 'Aspargos cozidos (100g)', 20),
(14, 'Aveia em flocos (100g)', 380),
(15, 'Azeite de oliva (1 colher de sopa)', 119),
(16, 'Azeite de soja (1 colher de sopa)', 120),
(17, 'Bacon (100g)', 541),
(18, 'Banana prata (100g)', 89),
(19, 'Batata doce cozida (100g)', 86),
(20, 'Batata inglesa cozida (100g)', 76),
(21, 'Berinjela crua (100g)', 25),
(22, 'Beringela cozida (100g)', 25),
(23, 'Beterraba cozida (100g)', 43),
(24, 'Brócolis cozido (100g)', 35),
(25, 'Caju (100g)', 43),
(26, 'Camarão cozido (100g)', 99),
(27, 'Canela em pó (1 colher de sopa)', 19),
(28, 'Canjica (100g)', 70),
(29, 'Castanha-do-pará (100g)', 656),
(30, 'Cebola cozida (100g)', 44),
(31, 'Cebola crua (100g)', 40),
(32, 'Chá verde (1 xícara)', 2),
(33, 'Chia (100g)', 486),
(34, 'Chocolate meio amargo (100g)', 525),
(35, 'Chuchu cozido (100g)', 19),
(36, 'Coco ralado (100g)', 354),
(37, 'Couve-flor cozida (100g)', 23),
(38, 'Couve manteiga crua (100g)', 35),
(39, 'Erva-doce (100g)', 31),
(40, 'Ervilha fresca cozida (100g)', 78),
(41, 'Farinha de mandioca (100g)', 340),
(42, 'Farinha de rosca (100g)', 370),
(43, 'Farinha de trigo (100g)', 364),
(44, 'Fígado bovino cozido (100g)', 135),
(45, 'Frango assado com pele (100g)', 239),
(46, 'Fubá (100g)', 361),
(47, 'Gengibre cru (100g)', 80),
(48, 'Goiaba vermelha (100g)', 68),
(49, 'Iogurte desnatado (100g)', 38),
(50, 'Iogurte natural integral (100g)', 61),
(51, 'Jiló (100g)', 30),
(52, 'Ketchup (1 colher de sopa)', 20),
(53, 'Leite desnatado (100ml)', 34),
(54, 'Leite integral (100ml)', 64),
(55, 'Lentilha cozida (100g)', 116),
(56, 'Lentilha seca (100g)', 340),
(57, 'Lichia (100g)', 66),
(58, 'Lombo de porco grelhado (100g)', 195),
(59, 'Macarrão cozido (100g)', 131),
(60, 'Mandioca cozida (100g)', 120),
(61, 'Manteiga (1 colher de sopa)', 102),
(62, 'Mamão papaya (100g)', 43),
(63, 'Manga (100g)', 60),
(64, 'Maçã com casca (100g)', 52),
(65, 'Mel (1 colher de sopa)', 64),
(66, 'Melancia (100g)', 30),
(67, 'Melão (100g)', 34),
(68, 'Milho verde cozido (100g)', 96),
(69, 'Morango (100g)', 32),
(70, 'Nozes (100g)', 654),
(71, 'Nabo cru (100g)', 28),
(72, 'Ovo de codorna cozido (1 un)', 14),
(73, 'Ovo de galinha cozido (1 un)', 68),
(74, 'Pão francês (50g)', 135),
(75, 'Pão integral (50g)', 110),
(76, 'Palmito (100g)', 26),
(77, 'Pepino cru (100g)', 16),
(78, 'Pera (100g)', 57),
(79, 'Pêssego (100g)', 39),
(80, 'Peito de frango grelhado (100g)', 165),
(81, 'Pipoca estourada sem óleo (100g)', 387),
(82, 'Presunto cozido (100g)', 145),
(83, 'Queijo minas frescal (100g)', 280),
(84, 'Queijo parmesão (100g)', 431),
(85, 'Requeijão cremoso (1 colher de sopa)', 50),
(86, 'Refrigerante cola (1 lata 350ml)', 140),
(87, 'Rúcula (100g)', 25),
(88, 'Sal (1 colher de chá)', 0),
(89, 'Salsinha fresca (100g)', 36),
(90, 'Salmão grelhado (100g)', 208),
(91, 'Sardinha enlatada (100g)', 208),
(92, 'Soja cozida (100g)', 173),
(93, 'Suco de laranja natural (200ml)', 90),
(94, 'Tapioca (100g)', 160),
(95, 'Tomate cru (100g)', 18),
(96, 'Tomate seco (100g)', 258),
(97, 'Tangerina (100g)', 53),
(98, 'Trigo para quibe (100g)', 335),
(99, 'Uva (100g)', 67),
(100, 'Vagem cozida (100g)', 35),
(101, 'Vinagre (1 colher de sopa)', 3),
(102, 'Vinho tinto (1 taça 150ml)', 125),
(107, 'Açucar (10g)', 40),
(112, 'Pão fatiado (fatia)', 75);

-- --------------------------------------------------------

--
-- Table structure for table `calorias_diarias`
--

CREATE TABLE `calorias_diarias` (
  `id` int(11) NOT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  `data_registro` date NOT NULL,
  `total_kcal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refeicao`
--

CREATE TABLE `refeicao` (
  `idrefeicao` int(11) NOT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  `data_refeicao` date NOT NULL,
  `tipo_refeicao` enum('cafe_da_manha','almoco','janta','lanche') NOT NULL,
  `idalimento` int(11) NOT NULL,
  `quantidade` float NOT NULL,
  `kcal_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nome_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `meta` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nome_usuario`, `email`, `senha`, `meta`) VALUES
(1, 'lucas', 'lucas@gmail.com', '$2y$10$rTCFAcre7TkDwqrp1BqqO.5vOQuGaiDJwC3Wy66NZzY92hiNrqSC2', 1500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alimentos`
--
ALTER TABLE `alimentos`
  ADD PRIMARY KEY (`idalimentos`);

--
-- Indexes for table `calorias_diarias`
--
ALTER TABLE `calorias_diarias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_calorias_usuario` (`usuario_idusuario`);

--
-- Indexes for table `refeicao`
--
ALTER TABLE `refeicao`
  ADD PRIMARY KEY (`idrefeicao`),
  ADD KEY `idx_refeicao_usuario` (`usuario_idusuario`),
  ADD KEY `fk_refeicao_alimento` (`idalimento`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `idalimentos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `calorias_diarias`
--
ALTER TABLE `calorias_diarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refeicao`
--
ALTER TABLE `refeicao`
  MODIFY `idrefeicao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calorias_diarias`
--
ALTER TABLE `calorias_diarias`
  ADD CONSTRAINT `calorias_diarias_ibfk_1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_calorias_diarias_usuario` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `refeicao`
--
ALTER TABLE `refeicao`
  ADD CONSTRAINT `fk_refeicao_alimento` FOREIGN KEY (`idalimento`) REFERENCES `alimentos` (`idalimentos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_refeicao_usuario` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `refeicao_ibfk_1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
