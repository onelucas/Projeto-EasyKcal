-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3316
-- Tempo de geração: 28/03/2025 às 20:31
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dbeasykcal`
--
CREATE DATABASE IF NOT EXISTS dbeasykcal;
USE dbeasykcal;

-- --------------------------------------------------------

--
-- Estrutura para tabela `alimentos`
--

CREATE TABLE IF NOT EXISTS `alimentos` (
  `idalimentos` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_alimento` VARCHAR(100) NOT NULL,
  `qtd_kcal` FLOAT NOT NULL,
  PRIMARY KEY (`idalimentos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `meta` FLOAT NOT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `refeicao`
--

CREATE TABLE IF NOT EXISTS `refeicao` (
  `idrefeicao` INT(11) NOT NULL AUTO_INCREMENT,
  `qtd_alimentos` INT(11) NOT NULL,
  `tipo_refeicao` ENUM('cafe_da_manha','almoco', 'janta', 'lanche') NOT NULL,
  `data_refeicao` DATE NOT NULL,
  `usuario_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`idrefeicao`),
  FOREIGN KEY (usuario_idusuario) REFERENCES usuario(idusuario)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `refeicao_alimento` (
  id INT NOT NULL AUTO_INCREMENT,
  refeicao_idrefeicao INT NOT NULL,
  idalimentos INT NOT NULL,
  quantidade INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (refeicao_idrefeicao) REFERENCES refeicao(idrefeicao)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY alimentos(idalimentos) REFERENCES alimentos(idalimentos)
    ON DELETE CASCADE ON UPDATE CASCADE
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

  CREATE TABLE IF NOT EXISTS calorias_diarias (
    id INT NOT NULL AUTO_INCREMENT,
    usuario_idusuario INT NOT NULL,
    data_registro DATE NOT NULL,
    total_kcal FLOAT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_idusuario) REFERENCES usuario(idusuario)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
