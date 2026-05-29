-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: May 29, 2026 at 03:00 PM
-- Server version: 8.0.45
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nubenta`
--
CREATE DATABASE IF NOT EXISTS `nubenta` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nubenta`;

-- --------------------------------------------------------

--
-- Table structure for table `andrea`
--

DROP TABLE IF EXISTS `andrea`;
CREATE TABLE `andrea` (
  `CodUsu` int NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `contrasena` varchar(30) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `activo` varchar(25) NOT NULL,
  `moneda` varchar(5) NOT NULL,
  `fecha_accesos` varchar(80) NOT NULL,
  `fecha_desconexion` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articulos`
--

DROP TABLE IF EXISTS `articulos`;
CREATE TABLE `articulos` (
  `CodArt` int(8) UNSIGNED ZEROFILL NOT NULL,
  `CodFam` int(8) UNSIGNED ZEROFILL NOT NULL,
  `NomArt` varchar(200) DEFAULT NULL,
  `DesArt` varchar(300) DEFAULT NULL,
  `CodPro` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `precio` float(5,2) DEFAULT NULL,
  `PreCom` float(5,2) NOT NULL,
  `cantidad` int UNSIGNED DEFAULT NULL,
  `OrdMov` int UNSIGNED NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bancos`
--

DROP TABLE IF EXISTS `bancos`;
CREATE TABLE `bancos` (
  `CodBan` int(8) UNSIGNED ZEROFILL NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE `compras` (
  `NumeroFactura` varchar(12) NOT NULL,
  `OrdMov` int UNSIGNED NOT NULL,
  `CodFac` int(8) UNSIGNED ZEROFILL NOT NULL,
  `CodArt` int(8) UNSIGNED ZEROFILL NOT NULL DEFAULT '00000000',
  `NomArt` varchar(90) DEFAULT NULL,
  `DesArt` varchar(300) DEFAULT NULL,
  `precio` float(5,2) DEFAULT NULL,
  `PreCom` float(5,2) UNSIGNED NOT NULL,
  `descuento` float(5,2) UNSIGNED NOT NULL,
  `IVA` float(5,2) UNSIGNED NOT NULL,
  `recargo` float(5,2) UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `stock` int NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `ForPag` set('efectivo','tarjeta') DEFAULT 'efectivo',
  `albaran_factura` set('albaran','factura') NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cuentas`
--

DROP TABLE IF EXISTS `cuentas`;
CREATE TABLE `cuentas` (
  `CodBan` int(8) UNSIGNED ZEROFILL NOT NULL,
  `CodPro` int(8) UNSIGNED ZEROFILL NOT NULL,
  `NumCue` varchar(30) NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datos_empresa`
--

DROP TABLE IF EXISTS `datos_empresa`;
CREATE TABLE `datos_empresa` (
  `nombre` varchar(100) DEFAULT NULL,
  `NIF_CIF` varchar(12) DEFAULT NULL,
  `direccion` varchar(400) DEFAULT NULL,
  `telefono` int DEFAULT NULL,
  `ticket_grande` varchar(500) DEFAULT NULL,
  `ticket_chiquito` varchar(500) DEFAULT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faccli`
--

DROP TABLE IF EXISTS `faccli`;
CREATE TABLE `faccli` (
  `OrdMov` int UNSIGNED NOT NULL,
  `CodFac` int(8) UNSIGNED ZEROFILL NOT NULL,
  `CodArt` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `NomArt` varchar(90) DEFAULT NULL,
  `DesArt` varchar(300) DEFAULT NULL,
  `precio` float(5,2) DEFAULT NULL,
  `IVA` float(5,2) UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `stock` int UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `ForPag` set('efectivo','tarjeta') DEFAULT NULL,
  `entregado` float(7,2) UNSIGNED NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facpro`
--

DROP TABLE IF EXISTS `facpro`;
CREATE TABLE `facpro` (
  `NumeroFactura` varchar(12) NOT NULL,
  `OrdMov` int UNSIGNED NOT NULL,
  `CodFac` int(8) UNSIGNED ZEROFILL NOT NULL,
  `CodArt` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `NomArt` varchar(90) DEFAULT NULL,
  `DesArt` varchar(300) DEFAULT NULL,
  `precio` float(5,2) DEFAULT NULL,
  `PreCom` float(5,2) UNSIGNED NOT NULL,
  `descuento` float(5,2) UNSIGNED NOT NULL,
  `IVA` float(5,2) UNSIGNED NOT NULL,
  `recargo` float(5,2) UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `stock` int NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `ForPag` set('efectivo','tarjeta') DEFAULT 'efectivo',
  `albaran_factura` set('albaran','factura') DEFAULT 'albaran',
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `familias`
--

DROP TABLE IF EXISTS `familias`;
CREATE TABLE `familias` (
  `CodFam` int(8) UNSIGNED ZEROFILL NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
CREATE TABLE `paises` (
  `CodPai` varchar(5) NOT NULL,
  `NomPai` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE `proveedores` (
  `CodPro` int(8) UNSIGNED ZEROFILL NOT NULL,
  `NomPro` varchar(99) DEFAULT NULL,
  `NIF_CIF` varchar(9) NOT NULL,
  `direccion` varchar(300) NOT NULL,
  `pais` varchar(70) NOT NULL,
  `localidad` varchar(30) NOT NULL,
  `provincia` varchar(30) NOT NULL,
  `telefono` int NOT NULL,
  `email` varchar(16) NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;
CREATE TABLE `provincias` (
  `CodPai` varchar(5) NOT NULL,
  `NomProvincia` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `OrdMov` int NOT NULL,
  `CodFac` int(8) UNSIGNED ZEROFILL NOT NULL,
  `CodArt` int(8) UNSIGNED ZEROFILL NOT NULL DEFAULT '00000000',
  `NomArt` varchar(90) DEFAULT NULL,
  `DesArt` varchar(300) DEFAULT NULL,
  `precio` float(5,2) DEFAULT NULL,
  `IVA` float(5,2) UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `stock` int UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `ForPag` set('efectivo','tarjeta') DEFAULT 'efectivo',
  `entregado` float(7,2) UNSIGNED NOT NULL,
  `CodUsu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `andrea`
--
ALTER TABLE `andrea`
  ADD PRIMARY KEY (`CodUsu`);

--
-- Indexes for table `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`CodArt`);

--
-- Indexes for table `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`CodBan`);

--
-- Indexes for table `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`CodArt`);

--
-- Indexes for table `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`CodFam`);

--
-- Indexes for table `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`CodPai`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`CodPro`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`CodArt`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `andrea`
--
ALTER TABLE `andrea`
  MODIFY `CodUsu` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;