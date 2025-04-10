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

CREATE TABLE `alimentos` (
  `idalimentos` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_alimento` VARCHAR(100) NOT NULL,
  `qtd_kcal` FLOAT NOT NULL,
  PRIMARY KEY (`idalimentos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
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

CREATE TABLE `refeicao` (
  `idrefeicao` INT(11) NOT NULL AUTO_INCREMENT,
  `qtd_alimentos` INT(11) NOT NULL,
  `usuario_idusuario` INT(11) NOT NULL,
  `alimentos_idalimentos` INT(11) NOT NULL,
  PRIMARY KEY (`idrefeicao`),
  KEY `fk_refeicao_usuario_idx` (`usuario_idusuario`),
  KEY `fk_refeicao_alimentos1_idx` (`alimentos_idalimentos`),
  CONSTRAINT `fk_refeicao_usuario` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_refeicao_alimentos1` FOREIGN KEY (`alimentos_idalimentos`) REFERENCES `alimentos` (`idalimentos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
